<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\Http\Requests\UsersManagment\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Mail\WelcomeEmailMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('role', 'student')->paginate(10);
        return response()->json([
                'data' => new UserCollection($users)
            ],200);
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

    public function instructors(): UserCollection
    {
        $this->authorize('viewInstructors', User::class);
        $users = User::where('role', 'instructor')->paginate(10);
        return new UserCollection($users);
    }

    public function admins(): UserCollection
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
        try {
            if ($user->role !== 'instructor') {
                return response()->json(['message' => 'User is not an instructor'], 403);
            }
            $userName = $user->name;
            $user->delete();
            return response()->json([
                'message' => 'Instructor deleted successfully',
                'Name' => $userName
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

    }

    public function showProfile(User $user): JsonResponse
    {
        $this->authorize('view', $user);
        if ($user->profile) {
            return response()->json([
                'profile' => $user->profile
            ]);
        }
        return response()->json(['message' => 'Profile not found'], 404);
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
        return new UserCollection($users);
    }

    public function collections(): array
    {
        return collect(['name', 'age'])->all();
    }

    public function restore(int $id): JsonResponse
    {
        $user = User::onlyTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found or not trashed.'], 404);
        }

        $this->authorize('restore', $user);

        $user->restore();

        return response()->json([
            'message' => 'Profile restored successfully',
            'data' => new UserResource($user),
        ], 200);
    }
}
