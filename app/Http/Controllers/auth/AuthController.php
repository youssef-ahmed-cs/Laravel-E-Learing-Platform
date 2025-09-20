<?php namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\UpdatePasswordRequest;
use App\Http\Resources\AuthResource;
use App\Mail\WelcomeEmailMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request): ?JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();

            $token = JWTAuth::fromUser($user);
            $user->increment('login_count');
            return response()->json([
                'user' => new AuthResource($user),
                'token' => $token,
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token',
                'message' => $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        if (!$user) {
            return response()->json(['message' => 'User registration failed'], 500);
        }

        if ($request->hasFile('avatar')) {
            $request->validated();
            $avatarName = $user->id . '_' . $user->username . '.' . $request->file('avatar')->getClientOriginalExtension();
            $avatarPath = $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
            $user->update(['avatar' => $avatarPath]);
        }
        Mail::to($user->email)->send(new WelcomeEmailMail($user));
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout(): JsonResponse
    {
        try {
            $token = JWTAuth::getToken();
            $user = Auth::user()->name;
            if (!$token || !$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            JWTAuth::invalidate($token);
            return response()->json(['message' => "User $user successfully logged out."], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Failed to logout, token invalid or expired',
                'message' => $e->getMessage() ,
                'at line' => $e->getLine()
            ], 500);
        }
    }

    public function user(): JsonResponse
    {
        try{
            $user = Auth::user();
            $this->authorize('view', $user);
            return response()->json([
                'user' => new AuthResource($user)
            ]);
        }
        catch (JWTException $e){
            return response()->json([
                'error' => 'Failed to fetch user',
                'message' => $e->getMessage() ,
                'at line' => $e->getLine()
            ], 500);
        }
    }

    public function refreshToken(): ?JsonResponse
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }
            $newToken = JWTAuth::refresh($token);
            $user = JWTAuth::setToken($newToken)->toUser();
            return response()->json([
                'message' => 'Token refreshed successfully',
                'token' => $newToken,
                'user name' => $user->name,
                'user email' => $user->email,
                'username' => $user->username,
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to refresh token',
                'message' => $e->getMessage(), 401], 500);
        }
    }

    public function getToken(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $token,
                'user name' => $user->name,
                'user email' => $user->email,
                'username' => $user->username,
            ], 200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to generate token',
                'message' => $e->getMessage(), 401], 500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $request->validated();
        try{
            $this->authorize('update', Auth::user());
            $user = Auth::user();
            $name = $user->name;
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json(['message' => "Hi $name Your password updated successfully"]);
        }
        catch (JWTException $e){
            return response()->json(['error' => 'Failed to update password',
                'message' => $e->getMessage()], 500);
        }

    }

    public function deleteAccount(): JsonResponse
    {
        try {
            $user = Auth::user(); // current authenticated user using JWT & Facades Auth
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $userName = $user->name;
            $this->authorize('delete', $user);

            $user->forceDelete(); // delete user permanently
            // To soft delete (so the user can be restored later), use: $user->delete();

            JWTAuth::invalidate(JWTAuth::getToken()); // Invalidate the user's JWT token after account deletion

            return response()->json(['message' => "Account for $userName deleted successfully"], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to delete account', 'message' => $e->getMessage()], 500);
        }
    }
}
