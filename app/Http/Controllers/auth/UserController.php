<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersManagment\StoreUserRequest;
use App\Http\Requests\UsersManagment\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('role', 'student')->paginate(10);
        if (!$users) {
            return response()->json([
                'message' => 'No users found',
            ], 404);
        }
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $request->validated();
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function instructors()
    {
        $this->authorize('viewInstructors', User::class);
        $users = User::where('role', 'instructor')->paginate(10);
        if (!$users) {
            return response()->json([
                'message' => 'No instructors found',
            ], 404);
        }
        return UserResource::collection($users);
    }

    public function admins()
    {
        $this->authorize('viewAdmins', User::class);
        $users = User::where('role', 'admin')->paginate(10);
        if (!$users) {
            return response()->json([
                'message' => 'No admins found',
            ], 404);
        }
        return UserResource::collection($users);
    }

    public function showInstructor(User $user)
    {
        $this->authorize('view', $user);
        $instructor = $user->where('role', 'instructor')->first();
        return new UserResource($instructor);
    }

    public function showAdmin(User $user)
    {
        $this->authorize('viewAdmins', $user);
        $admin = $user->where('role', 'admin')->first();
        return new UserResource($admin);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function deleteInstructor(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json([
            'message' => 'Instructor deleted successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function destroyAdmin(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json([
            'message' => 'Instructor deleted successfully',
            'user' => new UserResource($user)
        ]);
    }
}
