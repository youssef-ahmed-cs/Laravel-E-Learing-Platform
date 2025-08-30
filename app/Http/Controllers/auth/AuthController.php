<?php namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            $user->increment('login_count');

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $request->validated();
        if ($user = User::create($request->validated())) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token
            ], 201);
        }
        return response()->json(['message' => 'User registration failed'], 500);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete(); #delete token for current user
        $username = auth()->user()->username ?? 'username not found!';
        return response()->json([
            'message' => "$username Logged out successfully"
        ]);
    }

    public function user(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user()
        ]);
    }
    #Auth()::user() ==> return current authenticated user using facade declaration
    #auth()->user() ==> return current authenticated user using helper function declaration

    public function refreshToken(): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token
        ]);
    }

}
