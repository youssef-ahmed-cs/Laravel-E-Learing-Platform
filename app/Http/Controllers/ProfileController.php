<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileManagement\{ProfileRequest, UpdateProfileRequest};
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Profile::class);
        return ProfileResource::collection(Profile::with('user')->paginate(5));
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

    public function show(Profile $profile)
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

    public function update(UpdateProfileRequest $request, Profile $profile): ProfileResource
    {
        $this->authorize('update', $profile);
        $profile->update($request->validated());
        return new ProfileResource($profile);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }
}
