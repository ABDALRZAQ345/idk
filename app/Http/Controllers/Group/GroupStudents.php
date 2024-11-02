<?php

namespace App\Http\Controllers\Group;

use App\Exceptions\FORBIDDEN;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupStudentStoreRequest;
use App\Http\Requests\Group\GroupStudentUpdateRequest;
use App\Models\Group;
use App\Models\Student;
use App\Services\Group\GroupService;
use Illuminate\Http\JsonResponse;

class GroupStudents extends Controller
{
    //
    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @throws FORBIDDEN
     */
    public function index(Group $group): JsonResponse
    {

        $this->groupService->CheckCanAccessGroup($group);
        $students = $group->students()->paginate(15);

        return response()->json([
            'students' => $students,
        ]);
    }

    /**
     * @throws FORBIDDEN
     * @throws \Exception
     */
    public function store(GroupStudentStoreRequest $request, Group $group): JsonResponse
    {
        $this->groupService->CheckCanAccessGroup($group);
        $validated = $request->validated();

        $status = $this->groupService->addStudentsToGroup($validated['students'], $group);
        if (! $status) {
            return response()->json([
                'message' => 'something went wrong',
            ], 500);
        }

        return response()->json([
            'message' => 'student added successfully',
        ]);

    }
    ///

    /**
     * @throws FORBIDDEN
     */
    public function show(Group $group, Student $student): JsonResponse
    {
        $this->groupService->CheckCanAccessGroup($group);
        $student = $group->students()->findOrFail($student->id);

        return response()->json([
            'student' => $student,
        ]);
    }

    /**
     * @throws FORBIDDEN
     * @throws \Exception
     */
    public function delete(Group $group, Student $student): JsonResponse
    {
        $this->groupService->CheckCanAccessGroup($group);
        $this->groupService->removeStudentFromGroup($group->mosque, $student);

        return response()->json([
            'message' => 'student deleted successfully from that group ',
        ]);
    }

    /**
     * @throws FORBIDDEN
     * @throws \Exception
     */
    public function update(GroupStudentUpdateRequest $request, Group $group, Student $student): JsonResponse
    {
        $this->groupService->CheckCanAccessGroup($group);
        $validated = $request->validated();
        $student = $group->students()->findOrFail($student->id);
        $newGroup = Group::where('mosque_id', $group->mosque->id)->findOrfail($validated['group_id']);
        $this->groupService->changeStudentGroup($student, $newGroup);

        return response()->json([
            'message' => 'student `s group  updated successfully',
        ]);
    }
}
