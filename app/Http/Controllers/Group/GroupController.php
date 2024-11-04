<?php

namespace App\Http\Controllers\Group;

use App\Exceptions\FORBIDDEN;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupRequest;
use App\Models\Group;
use App\Services\Group\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    //
    protected GroupService $groupService;

    protected $max_groups;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->max_groups = config('app.data.max_groups');

    }

    public function index(): JsonResponse
    {

        $groups = $this->groupService->groups();

        return response()->json([
            $groups,
        ]);

    }

    public function store(GroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (Group::where('mosque_id', (Auth::user())->mosque->id)->count() >= $this->max_groups) {
            return response()->json([
                'message' => 'you can not create more than '.$this->max_groups.' groups for the mosque . ',
            ], 403);
        }
        $group = Group::create([
            'mosque_id' => (Auth::user())->mosque->id,
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
        ]);

        return response()->json([
            'message' => 'create success',
            'group' => $group,
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function update(GroupRequest $request, Group $group): JsonResponse
    {
        $this->groupService->CheckCanAccessGroup($group);

        $validated = $request->validated();

        $group->update([
            'user_id' => $validated['user_id'],
        ]);

        return response()->json([
            'message' => 'update success',
            'group' => $group,
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function delete(Group $group): JsonResponse
    {

        $this->groupService->CheckCanAccessGroup($group);

        $group->delete();

        return response()->json([
            'message' => 'delete success',
        ]);

    }
}
