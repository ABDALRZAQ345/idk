<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class GroupController extends Controller
{
    //
    protected GroupService $groupService;
    public function __construct(GroupService $groupService){
        $this->groupService = $groupService;
    }
    public function index(){

       $groups=$this->groupService->groups();
        return response()->json($groups);

    }
    public function store(GroupRequest $request){
        $validated=$request->validated();

        $group=Group::create([
            'mosque_id' => (Auth::user())->mosque->id,
            'user_id' => $validated['user_id'],
        ]);
        return response()->json([
            'message' => 'create success',
            'group' => $group
        ]);
    }
    public function delete(Group $group){
        if(!Auth::user()->mosque==$group->mosque){
            return response()->json([
                'status'=> 'fail',
                'message' => 'you cannot delete group'
            ],403);
        }
        $group->delete();
        return response()->json([
            'message' => 'delete success'
        ]);

    }
}
