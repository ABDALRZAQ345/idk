<?php

namespace App\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Requests\Tasks\TaskRequest;
use App\Models\Mosque;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Mosque $mosque)
    {
        return $this->taskService->index($mosque);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Mosque $mosque, TaskRequest $request)
    {
        return $this->taskService->store($mosque, $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mosque $mosque, Task $task)
    {
        return $this->taskService->show($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Mosque $mosque, Task $task, TaskRequest $request, )
    {
        return $this->taskService->update($task, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mosque $mosque, Task $task)
    {
        return $this->taskService->destroy($task);
    }
}
