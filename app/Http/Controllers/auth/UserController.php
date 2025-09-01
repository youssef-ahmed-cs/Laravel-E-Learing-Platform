<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersManagment\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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

    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        $user = $user->where('role', 'student')->first();
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request): JsonResponse
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

    public function showInstructor(User $user): UserResource
    {
        $this->authorize('view', $user);
        $instructor = $user->where('role', 'instructor')->first();
        return new UserResource($instructor);
    }

    public function showAdmin(User $user): UserResource
    {
        $this->authorize('viewAdmins', $user);
        $admin = $user->where('role', 'admin')->first();
        return new UserResource($admin);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);
        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user)
        ]);
    }
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'User is not a student'
            ], 400);
        }
        $user->delete();
        return response()->json([
            'message' => 'Student deleted successfully',
            'Name' => (string)$user->name
        ]);
    }


    public function deleteInstructor(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->where('role', 'instructor')->delete();
        return response()->json([
            'message' => 'Instructor deleted successfully',
            'Name' => (string)$user->name
        ]);
    }

    public function destroyAdmin(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->where('role', 'admin')->delete();
        return response()->json([
            'message' => 'Instructor deleted successfully',
            'Name' => (string)$user->name
        ]);
    }

    public function search($name)
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('username', 'like', '%' . $name . '%')
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found',
            ], 404);
        }

        return UserResource::collection($users);
    }
}
