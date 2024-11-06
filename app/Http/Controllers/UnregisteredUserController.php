<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnregisteredUsers\UnregisteredUserPhoneRequest;
use App\Http\Requests\UnregisteredUsers\UnregisteredUserStoreRequest;
use App\Services\UnregisteredUserService;
use Illuminate\Http\Request;

class UnregisteredUserController extends Controller
{
    private UnregisteredUserService $unregisteredUserService;

    public function __construct(UnregisteredUserService $unregisteredUserService)
    {
        $this->unregisteredUserService = $unregisteredUserService;
    }

    public function phone(UnregisteredUserPhoneRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $validated = $request->validated();

        return $this->unregisteredUserService->phone($validated['phone_number']);
    }

    public function store(UnregisteredUserStoreRequest $request)
    {
        return $this->unregisteredUserService->store($request);
    }

    public function destroy($id)
    {
        return $this->unregisteredUserService->destroy($id);
    }
}
