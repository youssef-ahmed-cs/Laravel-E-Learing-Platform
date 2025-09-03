<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\{
    CategoryController,
    CourseController,
    EnrollmentController,
    LessonController,
    NotificationController,
    ReviewController,
    auth\UserController
};

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'user');
        Route::post('/refresh-token', 'refreshToken');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/instructors', 'instructors');
        Route::get('/users/admins', 'admins');
        Route::get('/users/admin/{id}', 'showAdmin');
        Route::get('/users/instructor/{instructor}', 'showInstructor');
        Route::delete('/users/admin/{id}', 'destroyAdmin');
        Route::delete('/users/instructor/{id}', 'deleteInstructor');
        Route::get('/users/search/{name}', 'search');
        Route::apiResource('users', UserController::class);

    });

    Route::controller(CourseController::class)->group(function () {
        Route::get('/courses/{course}/category', 'getCategories');
        Route::get('/courses/{id}/lessons', 'getLessons');
        Route::apiResource('courses', CourseController::class);
    });

    Route::controller(EnrollmentController::class)->group(function () {
        Route::get('enrollments/{enrollment}/courses', 'getCourses');
        Route::get('enrollments/{enrollment}/students', 'getStudents');
        Route::apiResource('enrollments', EnrollmentController::class);
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/{category}/courses', 'getCourses');
        Route::apiResource('categories', CategoryController::class);
    });

    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews/{review}/students', 'getReviewStudents');
        Route::get('/reviews/{review}/courses', 'getReviewCourses');
        Route::get('/reviews/summary', 'getReviewsSummary');
        Route::apiResource('reviews', ReviewController::class);
    });

    Route::controller(LessonController::class)->group(function () {
        Route::get('/lessons/{lesson}/courses', 'getCourses');
        Route::get('/lessons/{lesson}/reviews', 'getReviews');
        Route::get('/lessons/{lesson}/students', 'getStudents');
        Route::get('/lessons/{lesson}/enrollments', 'getEnrollments');
        Route::apiResource('lessons', LessonController::class);

    });

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });
});

