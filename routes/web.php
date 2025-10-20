<?php

use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\LearnHttpController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SocialiteGoogleController;
use App\Http\Controllers\StripeController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('send-otp', [OtpController::class, 'sendOtp']);

Route::get('/', [StripeController::class, 'index'])->name('stripe.index');
Route::get('/success', [StripeController::class, 'success'])->name('stripe.success');
Route::post('/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::post('/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::get('users', [UserController::class, 'to_view'])->middleware('throttle:5,1');
Route::get('url', LearnHttpController::class);

Route::get('/users/{roles}', static function (?string $roles) {
    return response()->json([
        'roles' => $roles
    ]);
})->whereIn('roles', \App\Enums\User::cases());

//require __DIR__ . '/auth.php';
Route::get('users/{user}', static function (User $user) {
    if (!request()->hasValidSignature()) {
        abort(401);
    }
    return response()->json([
        'user' => $user
    ]);
})->name('users.show02');

Route::redirect('youssef', 'https://x.com/', 301);

Route::controller(SocialiteGoogleController::class)->name('google.')->group(function () {
    Route::get('/google/login', 'login')->name('redirect');
    Route::get('/google/callback', 'callback')->name('callback');
});

Route::get('users-caching', static function () {
    # Using the cache helper function
    $users = Cache::remember('users', 60, static function () {
        return User::all();
    });
    return view('users', compact('users'));
})->name('users.show');

Route::get('/caps/{text}', static function (string $text) {
    return Response::caps($text);
});

Route::get('/generate-qrcode', QrCodeController::class);
Route::get('show-ip', static function (Request $request) {
    dd($request->method());
});
