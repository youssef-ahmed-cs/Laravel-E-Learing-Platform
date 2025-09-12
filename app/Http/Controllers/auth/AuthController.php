<?php namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
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

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

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
            //Mail::to($user['email'])->send(new WelcomeEmailMail($user->name));
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
        Auth::user()->tokens()->delete();
        $username = Auth::user()->username ?? 'username not found!';
        return response()->json([
            'message' => "$username Logged out successfully"
        ]);
    }

    public function user(): JsonResponse
    {
        return response()->json([
            'user' => new AuthResource(Auth::user())
        ]);
    }

    public function refreshToken(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $request->validated();
        $user = Auth::user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        return response()->json(['message' => 'Password updated successfully']);
    }
}
