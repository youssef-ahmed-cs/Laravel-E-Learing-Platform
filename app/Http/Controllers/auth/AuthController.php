<?php namespace App\Http\Controllers\auth;

use App\Events\UserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\UpdatePasswordRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\CourseResource;
use App\Mail\OtpMail;
use App\Mail\WelcomeEmailMail;
use App\Models\User;
use App\Models\Course;
use App\Notifications\SendTwoFactorCode;
use App\Traits\UploadImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\Events\Registered;
use SadiqSalau\LaravelOtp\Facades\Otp;
use App\Otp\UserRegistrationOtp;

class AuthController extends Controller
{
    use UploadImage;

    public function login(Request $request): ?JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                Log::error('Login attempt failed for email: ' . $request->email);
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();

            $token = JWTAuth::fromUser($user);
            $user->increment('login_count');
            Log::info('Login success for email: ' . $request->email);
            return response()->json([
                'user' => new AuthResource($user),
                'token' => $token,
            ]);
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Could not create token',
                'message' => $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        if (!$user) {
            Log::error('User registration failed for email: ' . $request->email);
            return response()->json(['message' => 'User registration failed'], 500);
        }

        if ($request->hasFile('avatar')) {
            $request->validated();
            $avatarName = $user->id . '_' . $user->username . '.' . $request->file('avatar')?->getClientOriginalExtension();
            $avatarPath = $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
            $user->update(['avatar' => $avatarPath]);
        }
        $token = JWTAuth::fromUser($user);
//        UserRegisteredEvent::dispatch($user);
//        event(new Registered($user));
//        $user->generateTwoFactorCode();
//        $user->notify(new SendTwoFactorCode());
//        event();
        Mail::to($user->email)->send(new WelcomeEmailMail($user->name));
//            Mail::to($user->email)->send(new OtpMail($user));
//        $otp = Otp::identifier($request->email)->send(
//            new UserRegistrationOtp(
//                name: $request->name,
//                email: $request->email,
//                password: $request->password
//            ),
//            Notification::route('mail', $request->email)
//        );
//        Log::info('OTP: ' . $otp['status'] . ' sent to ' . $request->email . ' at ' . now());
//        return __($otp['status']);
        return response()->json([
            'message' => 'User registered successfully',
            'user' => new AuthResource($user),
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
            Log::info('User logged out: ' . $user);
            return response()->json(['message' => "User $user successfully logged out."], 200);
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Failed to logout, token invalid or expired',
                'message' => $e->getMessage(),
                'at line' => $e->getLine()
            ], 500);
        }
    }

    public function user(): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->authorize('view', $user);
            Log::info('Fetched user details for: ' . $user->email);
            return response()->json([
                'user' => new AuthResource($user)
            ]);
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch user',
                'message' => $e->getMessage(),
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
            Log::error('Token refresh failed: ' . $e->getMessage());
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
        try {
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
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update password',
                'message' => $e->getMessage()], 500);
        }
    }

    public function deleteAccount(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $userName = $user->name;
            $this->authorize('delete', $user);

            $user->forceDelete(); // delete user permanently
            if (!empty($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            Log::warning("User $userName account deleted permanently.");
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => "Account for $userName deleted successfully"], 200);
        } catch (JWTException $e) {
            Log::error('Account deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete account', 'message' => $e->getMessage()], 500);
        }
    }


    public function guestCourses(): JsonResponse
    {
        $courses = Course::paginate(10);
        return response()->json([
            'courses' => CourseResource::collection($courses),
            'pagination' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
                'next_page_url' => $courses->nextPageUrl(),
                'prev_page_url' => $courses->previousPageUrl()
            ]
        ]);
    }

    public function getUserStats(): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->authorize('view', $user);

            $stats = [
                'total_courses' => $user->courses()->count(),
                'completed_courses' => $user->courses()->wherePivot('completed', true)->count(),
                'login_count' => $user->login_count,
                'account_created' => $user->created_at->diffForHumans(),
                'last_login' => $user->updated_at->diffForHumans()
            ];

            return response()->json(['stats' => $stats]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        }

    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link resent!']);

        Log::notice('Verification email resent to: ' . $user->email . ' at ' . now());
        return response()->json(['message' => 'Verification email resent'], 200);
    }

    public function verifyEmail($id, $hash, Request $request)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            Log::error('Email verification failed for user ID: ' . $id);
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->markEmailAsVerified();
        Log::info('Email verified for user: ' . $user->email);
        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        if (app('otp')->check($request->otp, $request->email)) {
            $user = User::where('email', $request->email)->firstOrFail();

            $user->update(['email_verified_at' => now()]);
            Auth::login($user);

            return response()->json([
                'message' => 'Email verified successfully',
                'user' => $user,
            ]);
        }

        return response()->json([
            'message' => 'Invalid or expired OTP',
        ], 422);
    }


    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email']
        ]);

        $otp = Otp::identifier($request->email)->update();

        if ($otp['status'] !== Otp::OTP_SENT) {
            return response()->json([
                'message' => __($otp['status'])
            ], 422);
        }

        return response()->json([
            'message' => __($otp['status'])
        ], 200);
    }

    public function isVerified(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        Log::info('Email verification status checked for: ' . $user->email);
        return response()->json([
            'Name' => $user->name,
            'Email' => $user->email,
            'email_verified' => $user->hasVerifiedEmail()
        ], 200);
    }
}
