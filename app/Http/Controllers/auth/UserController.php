<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UsersManagment\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Mail\WelcomeEmailMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('role', 'student')->paginate(10);
        return new UserCollection($users);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        if ($user->role !== 'student') {
            return response()->json(['message' => 'User not found or is not a student.'], 404);
        }
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $user = User::create($request->validated());
        Mail::to($user->email)->send(new WelcomeEmailMail($user->name));
        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user)
        ], 201);
    }

    public function instructors()
    {
        $this->authorize('viewInstructors', User::class);
        $users = User::where('role', 'instructor')->paginate(10);
        return UserResource::collection($users);
    }

    public function admins()
    {
        $this->authorize('viewAdmins', User::class);
        $users = User::where('role', 'admin')->paginate(10);
        return new UserCollection($users);
    }

    public function showInstructor(User $user): JsonResponse|UserResource
    {
        $this->authorize('view', $user);
        if ($user->role !== 'instructor') {
            return response()->json(['message' => 'User not found or is not an instructor.'], 404);
        }
        return new UserResource($user);
    }

    public function showAdmin(User $user): JsonResponse|UserResource
    {
        $this->authorize('viewAdmins', $user);
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'User not found or is not an admin.'], 404);
        }
        return new UserResource($user);
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
            ], 403);
        }
        $userName = $user->name;
        $user->delete();
        return response()->json([
            'message' => 'Student deleted successfully',
            'Name' => $userName
        ]);
    }


    public function deleteInstructor(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        if ($user->role !== 'instructor') {
            return response()->json(['message' => 'User is not an instructor'], 403);
        }
        $userName = $user->name;
        $user->delete();
        return response()->json([
            'message' => 'Instructor deleted successfully',
            'Name' => $userName
        ]);
    }

    public function destroyAdmin(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'User is not an admin'], 403);
        }
        $userName = $user->name;
        $user->delete();
        return response()->json([
            'message' => 'Admin deleted successfully',
            'Name' => $userName
        ]);
    }

    public function search($name)
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('name', 'like', '%' . $name . '%')->get();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found',
            ], 404);
        }

        return UserResource::collection($users);
    }

    public function collections(): array
    {
        $users = collect([
            ['name' => 'Alice'],
            ['name' => 'Bob']
        ]);
        return $users->firstWhere('name', 'Alice');
    }
}
