<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{auth\AuthController,
    auth\UserController,
    CategoryController,
    CourseController,
    EnrollmentController,
    LessonController,
    NotificationController,
    ProfileController,
    ReviewController
};

Route::prefix('v1')->middleware(['guest', 'throttle:60,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'user');
        Route::post('/refresh-token', 'refreshToken');
        Route::post('update-password', 'updatePassword');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/instructors', 'instructors');
        Route::get('/users/{id}/profile', 'showProfile');
        Route::get('/users/admins', 'admins');
        Route::get('/users/admin/{id}', 'showAdmin');
        Route::get('/users/instructor/{instructor}', 'showInstructor');
        Route::delete('/users/admin/{id}', 'destroyAdmin');
        Route::delete('/users/instructor/{id}', 'deleteInstructor');
        Route::get('users/{id}/enrollments', 'showUserEnrollment');
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
    });
    Route::apiResource('courses', CourseController::class);

    Route::controller(EnrollmentController::class)->group(function () {
        Route::get('enrollments/{enrollment}/courses', 'getCourses');
        Route::get('enrollments/{enrollment}/students', 'getStudents');
    });
    Route::apiResource('enrollments', EnrollmentController::class);

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/{category}/courses', 'getCourses');
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
        Route::get('/lessons/{lesson}/reviews', 'getReviews');
        Route::get('/lessons/{lesson}/students', 'getStudents');
        Route::get('/lessons/{lesson}/enrollments', 'getEnrollments');
    });
    Route::apiResource('lessons', LessonController::class);

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index');
        Route::post('/notifications/{id}/read', 'markAsRead');
    });
});

Route::fallback(static function () {
    return response()->json(['message' => 'Resource not found.'], 404);
});

//Route::get('try', static fn() => response()->json(['GPA' => '3.60', 'department' => 'CS'], 200))
//->name('try');
//Route::redirect('old-route', 'https://laravel.com/docs/12.x/structure#the-root-directory', 301);
Route::get('/collection', [UserController::class, 'collections'])
    ->middleware('policeman');
//Route::delete('/ping', static fn() => response()->json(['message' => 'pong'], 200));
//Route::post('set-local', static function (Request $request) {
//    return response()->json(['message' => 'Locale set to ',
//        $request->header()], 200);
//})->middleware('setLocal');


