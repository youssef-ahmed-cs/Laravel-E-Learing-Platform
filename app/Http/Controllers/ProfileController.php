<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Vonage\Users\User;

class ProfileController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', Profile::class);
        $users_profile = Profile::with('user')->get();
        return ProfileResource::collection($users_profile);
    }

    public function show(Profile $profile): ProfileResource
    {
        $this->authorize('view', $profile);
        return new ProfileResource($profile);
    }

    public function update(ProfileUpdateRequest $request, Profile $profile): JsonResponse
    {
        $this->authorize('update', $profile);
        $profile->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => new ProfileResource($profile),
        ]);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $profile->delete();

        return response()->json([
            'message' => 'Profile deleted successfully',
        ]);
    }

    public function restore(Profile $profile): JsonResponse
    {
        $this->authorize('restore', $profile);
        $profile->restore();

        return response()->json([
            'message' => 'Profile restored successfully',
            'profile' => new ProfileResource($profile),
        ]);
    }

}
