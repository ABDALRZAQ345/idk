<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserStoreRequest;
use App\Services\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserStoreRequest $request)
    {
        return $this->userService->store($request);
    }

    public function destroy($id)
    {
        return $this->userService->destroy($id);
    }
}
