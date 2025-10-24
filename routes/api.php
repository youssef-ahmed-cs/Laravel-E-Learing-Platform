<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\CodeEditorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\VerifyEmailController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

// Route::prefix('google')->name('google.')->group(function () {
//    Route::controller(SocialiteController::class)->group(function () {
//        Route::get('/redirect', 'redirect')->name('redirect');
//        Route::get('/login', 'login')->name('login');
//    });
// });

Route::prefix('v1')->middleware(['throttle:10,1'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });
    // Google Socialite Routes <-- Example: /api/v1/google/redirect -->

    Route::controller(ForgetPasswordController::class)->group(function () {
        Route::post('forgot-password-by-otp', 'forgotPassword');
        Route::post('verify-forgot-password-otp', 'verifyOtp');
        Route::post('reset-password-by-otp', 'resetPassword');
    });

    Route::controller(VerifyEmailController::class)->group(function () {
        Route::post('/auth/verify-email-otp', 'verifyEmailOtp')->name('verification.verify');
        Route::post('/auth/resend-verification-email-otp', 'resendEmail')->name('verification.resend');
        Route::get('/auth/is-verified', 'isVerified');
    });
});

//Route::middleware(['auth:api', 'verified', 'throttle:premium'])->group(function () {
Route::middleware('throttle:premium')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'user');
        Route::post('/refresh-token', 'refreshToken');
        Route::post('update-password', 'updatePassword');
        Route::post('/force-delete-user', 'deleteAccount');
        Route::get('/user-avatar', 'getAvatar');
        Route::get('/user-stats', 'getUserStats');
        Route::get('guest-user', 'guestCourses')->withoutMiddleware(['auth:api', 'verified']);
        Route::get('/get-token', 'getToken');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/instructors', 'instructors');
        Route::get('/users/{id}/profile', 'showProfile');
        Route::get('user/{user}/tasks', 'userTasks');
        Route::get('/users/admins', 'admins');
        Route::get('/users/admin/{id}', 'showAdmin');
        Route::get('/users/instructor/{instructor}', 'showInstructor');
        Route::delete('/users/admin/{id}', 'destroyAdmin');
        Route::delete('/users/instructor/{id}', 'deleteInstructor');
        Route::get('users/{id}/enrollments', 'showUserEnrollment');
        Route::get('/user/avatar', 'getAvatar');
        Route::get('/users/search/{name}', 'search');
        Route::get('/users/{id}/restore', 'restore')->name('users.restore');
    });
    Route::apiResource('users', UserController::class);

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profiles/user/{user}', 'showByUser');
        Route::get('/profiles/{id}/restore', 'restore');
    });
    Route::apiResource('profiles', ProfileController::class);

    Route::controller(CourseController::class)->group(function () {
        Route::get('/courses/{course}/category', 'getCategories');
        Route::get('/courses/{id}/lessons', 'getLessons');
        Route::get('/courses/{id}/restore', 'restore')->name('courses.restore');
    });
    Route::apiResource('courses', CourseController::class);

    Route::controller(EnrollmentController::class)->group(function () {
        Route::get('enrollments/{enrollment}/courses', 'getCourses');
        Route::get('enrollments/{enrollment}/students', 'getStudents');
    });
    Route::apiResource('enrollments', EnrollmentController::class);

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/{category}/courses', 'getCourses');
        Route::get('/categories/{id}/restore', 'restore');
        Route::delete('/categories/{id}/force-delete', 'forceDelete');
        Route::get('/categories/trashed', 'getTrashed');
        Route::get('/categories/search/{name}', 'search');
        Route::get('/categories/{id}/exists', 'checkExists');
        //        Route::get('/categories/{id}/courses', 'getCourses');
    });
    Route::apiResource('categories', CategoryController::class);

    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews/{review}/students', 'getReviewStudents');
        Route::get('/reviews/{review}/courses', 'getReviewCourses');
        Route::get('/reviews/summary', 'getReviewsSummary');
    });
    Route::apiResource('reviews', ReviewController::class);

    Route::controller(LessonController::class)->group(function () {
        Route::get('/lessons/{lesson}/courses', 'getCourses');
        Route::get('/lessons/{id}/restore', 'restore');
        Route::get('/lessons/{lesson}/reviews', 'getReviews');
        Route::get('/lessons/{lesson}/tasks', 'lessonTasks');
        Route::get('/lessons/{lesson}/students', 'getStudents');
        Route::get('/lessons/{lesson}/enrollments', 'getEnrollments');
    });
    Route::apiResource('lessons', LessonController::class);

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index');
        Route::post('/notifications/{id}/read', 'markAsRead');
    });

    Route::controller(TaskController::class)->group(function () {
        Route::get('/tasks/{id}/restore', 'restore');
        Route::delete('/tasks/{id}/force-delete', 'forceDelete');
        Route::get('/tasks/{task}/users', 'getUsersByTask');
    });
    Route::apiResource('tasks', TaskController::class);
});

Route::fallback(static function () {
    Log::warning('API Route not found: ' . request()->url() . ' Method: ' . request()->method() . ', IP: ' . request()->ip());

    return response()->json(['message' => 'Resource not found.'], 404);
});

Route::get('try', static fn() => response()->json(['GPA' => '3.60', 'department' => 'CS'], 200))->name('try');
Route::redirect('old-route', 'https://laravel.com/docs/12.x/structure#the-root-directory', 301);
Route::get('/collection', [UserController::class, 'collections'])->middleware('policeman');
Route::delete('/ping', static fn() => response()->json(['message' => 'pong'], 200));

Route::get('string', static function () {
    // Using the Str facades to get the part of the string before the "@" character
    //    $slice = Str::beforeLast('youssef.ahmed.fci@gmail.com', '@');
    // Using the Str facades to get the part of the string between two strings
    //    $slice = Str::between('This is my name', 'This', 'name');
    // Using the Str facades to convert a string to camel case
    //    $converted = Str::camel('youssef_ahmed_fci');
    // Using the Str facades to convert a string to title case
    //    $url = Str::chopStart('https://laravel.com', ['https://', 'http://']);
    // Using the Str facades to remove a part of the string from the end
    //    $url = Str::chopEnd('app/Models/Photograph.php', '.php');
    // Using the Str facades to check if a string contains a specific substring
    //    $contains = Str::contains('This is my name', 'MY', ignoreCase: true);
    // Using the Str facades to check if a string does not contain a specific substring
    //    $doesntContain = Str::doesntContain('This is name', ['my', 'your']);
    // remove duplicate spaces
    //    $result = Str::deduplicate('The   Laravel   Framework');
    // check if a string ends with a specific substring
    //    $result = Str::endsWith('This is my name', 'name');
    // add a specific substring to the end of a string if it doesn't already end with it
    //    $adjusted = Str::finish('this/string', '/');
    // convert a string to pascal case
    //    $converted = Str::pascal('YoussefAhmedFci');
    // convert a string to a more human-readable format
    //    $headline = Str::headline('EmailNotificationSent');
    // check if a string matches a specific pattern
    //    $matches = Str::is('*
    // check if a string is a valid URL
    //    $isUrl = Str::isUrl('http://example.com');
    // get the length of a string
    //    $len = Str::length('Hello World!');
    // limit the number of characters in a string
    //    $truncated = Str::limit('The quick brown fox jumps over the lazy dog', 20);
    // convert a string to lowercase
    //    $converted = Str::lower('LARAVEL');
    // convert a string to uppercase
    //    $converted = Str::upper('youssef');
    // convert a string to snake case
    //    $converted = Str::snake('YoussefAhmedFci');
    // convert markdown to HTML
    //    $html = Str::markdown('# Laravel');
    // mask a portion of a string with a specific character
    $string = Str::mask('taylor@example.com', '*', 3);

    return response()->json(['Your string is: ' => $string], 200);
});

Route::get('/ping-01', static fn() => response()->json(['message' => 'pong'], 200));
// Route::apiResource('tasks', TaskController::class);
Route::post('/filer', FileController::class);
Route::get('/sms', SendSmsController::class);
// Route::get('/http-client', [LearnHttpController::class]);
Route::get('/users-recent', [UserController::class, 'recentFirstUsers'])
    ->middleware('throttle:5,1');

// Example of logging SQL queries for debugging purposes
Route::get('sql-log', static function () {
    DB::enableQueryLog(); // Enable query logging
    User::query()->take(5)->pluck('name'); // Sample query to be logged
    $queries = DB::getQueryLog(); // Retrieve the logged queries

    return response()->json($queries, 200);
});

Route::get('/users/try/v10', static function () {
    $users = DB::table('users')->simplePaginate(10);

    return response()->json($users, 200);
});

Route::get('try-throttle', static function () {
    return response()->json(['message' => 'This is a throttled route'], 200);
});

Route::options('/your-endpoint', function () {
    return response()->json(['methods' => ['GET', 'POST', 'PUT', 'DELETE']], 200)
        ->header('Allow', 'GET, POST, PUT, DELETE, OPTIONS');
});

Route::get('/my-endpoint', static function () {
    $admins = User::isPremium()->get(['id', 'name', 'email']);

    return response()->json([
        'admins with premium' => $admins,
    ], 200);
})->name('my-endpoint');

Route::post('code-editor', CodeEditorController::class);


//Route::post('/run-code', function (Request $request) {
//
//});
