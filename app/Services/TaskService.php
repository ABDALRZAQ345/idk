<?php

namespace App\Services;

use App\Enums\Permission;
use App\Exceptions\AccessDeniedException;
use App\Http\Requests\Tasks\TaskRequest;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Mosque;
use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    private function mosqueTasks(Mosque $mosque): Collection
    {
        return Task::whereBelongsTo($mosque)->get();
    }

    private function groupTasks(User $user): Collection
    {
        return Task::whereRelation('students.groups.user', 'id', $user->id)->get();
    }

    private function studentTasks(Student $student): Collection
    {
        return Task::whereRelation('students', 'student_id', $student->id)->get();
    }

    private function taskBelongsToUser(User $user, Task $task): bool
    {
        return $this->groupTasks($user)->contains($task);
    }

    public function index(Mosque $mosque)
    {
        $user = Auth::user();

        if ($user instanceof Student) {
            $tasks = $this->studentTasks($user);
        } else if ($user->hasPermission(Permission::MOSQUE_TASK_READ)) {
            $tasks = $this->mosqueTasks($mosque);
        } else if ($user->hasPermission(Permission::GROUP_TASK_READ)) {
            $tasks = $this->groupTasks($user);
        } else {
            throw new AccessDeniedException;
        }

        return response()->json([
            'message' => 'Successfully',
            'total tasks' => $tasks->count(),
            'tasks' => $tasks,
        ]);
    }

    public function store(Mosque $mosque, TaskRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        if (!$user->hasPermission(Permission::TASK_STORE)) {
            throw new AccessDeniedException;
        }

        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'mosque_id' => $mosque->id,
            'user_id' => $user->id,
        ]);

        $task->students()->attach($validated['students']);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], Response::HTTP_CREATED);
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        if ($user instanceof Student) {

            if (!$task->students()->where('student_id', $user->id)->exists()) {
                throw new AccessDeniedException;
            }

        } else {

            if (!$user->hasPermission(Permission::MOSQUE_TASK_READ) && !$this->taskBelongsToUser($user, $task)) {
                throw new AccessDeniedException;
            }

        }

        return response()->json([
            'message' => 'Successfully',
            'task' => $task,
        ]);
    }

    public function update(Task $task, TaskRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        if (!$user->hasPermission(Permission::TASK_UPDATE) && !$this->taskBelongsToUser($user, $task)) {
            throw new AccessDeniedException;
        }

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $task->students()->detach();

        $task->students()->attach($validated['students']);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        if (!$user->hasPermission(Permission::TASK_DELETE) && !$this->taskBelongsToUser($user, $task)) {
            throw new AccessDeniedException;
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}