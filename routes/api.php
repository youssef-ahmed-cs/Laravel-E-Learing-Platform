<?php

use App\Http\Controllers\{auth\AuthController,
    auth\UserController,
    CategoryController,
    CourseController,
    EnrollmentController,
    FileController,
    ForgetPasswordController,
    LessonController,
    NotificationController,
    ProfileController,
    ReviewController,
    SendSmsController,
    TaskController,
    VerifyEmailController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

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


Route::middleware(['auth:api', 'verified', 'throttle:premium'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'user');
        Route::post('/refresh-token', 'refreshToken');
        Route::post('update-password', 'updatePassword');
        Route::post('/force-delete-user', 'deleteAccount');
        Route::get('/user-stats', 'getUserStats');
        Route::get('guest-user', 'guestCourses')->withoutMiddleware(['auth:api' , 'verified']);
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
        Route::get('/users/{id}/restore', 'restore');
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
        Route::get('/courses/{id}/restore', 'restore');
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
Route::get('verify-middleware-example', static function () {
    return response()->json(['message' => 'Middleware active'], 200);
})->middleware('verified');
Route::get('/ping-01', static fn() => response()->json(['message' => 'pong'], 200));
//Route::apiResource('tasks', TaskController::class);
Route::post('/filer', FileController::class);
Route::get('/sms', SendSmsController::class);
//Route::get('/http-client', [LearnHttpController::class]);
Route::get('/users-recent', [UserController::class, 'recentFirstUsers'])
    ->middleware('throttle:5,1');


