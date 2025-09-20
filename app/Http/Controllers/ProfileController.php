<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\ProfileManagement\{ProfileRequest, UpdateProfileRequest};
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Profile::class);
        return ProfileResource::collection(Profile::load('user')->paginate(5));
    }

    public function store(ProfileRequest $request): JsonResponse
    {
        $this->authorize('create', Profile::class);
        $profile = Profile::create($request->validated());

        return response()->json([
            'message' => 'Profile created successfully',
            'data' => new ProfileResource($profile),
        ], 201);
    }

    public function show(Profile $profile): ProfileResource
    {
        $this->authorize('view', $profile);
        return new ProfileResource($profile);
    }

    public function showByUser()
    {
        $user = auth()->user();

        if (!$user?->profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $this->authorize('view', $user->profile);
        return new ProfileResource($user->profile);
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $this->authorize('update', $profile);
        $profile->update($request->validated());
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new ProfileResource($profile),
        ]);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $profile->delete();
        $name = $profile->user?->name;
        return response()->json(['message' => "Profile related $name deleted successfully"]);
    }


    public function restore(int $id): JsonResponse
    {
        $profile = Profile::onlyTrashed()->find($id);

        if (!$profile) {
            return response()->json(['message' => 'Profile not found or not trashed.'], 404);
        }

        $this->authorize('restore', $profile);

        $profile->restore();

        return response()->json([
            'message' => 'Profile restored successfully',
            'data' => new ProfileResource($profile),
        ], 200);
    }


}
