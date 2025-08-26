<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

    Route::get('/users/instructors', [UserController::class, 'instructors']);
    Route::get('/users/admins', [UserController::class, 'admins']);
    Route::get('/users/admin/{id}', [UserController::class, 'showAdmin']);
    Route::get('/users/instructor/{instructor}', [UserController::class, 'showInstructor']);
    Route::delete('/users/admin/{id}', [UserController::class, 'destroyAdmin']);
    Route::delete('/users/instructor/{id}', [UserController::class, 'deleteInstructor']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('enrollments',EnrollmentController::class);
    Route::apiResource('reviews', ReviewController::class);

    Route::get('/categories/{category}/courses', [CategoryController::class, 'getCourses']);
    Route::get('/courses/{course}/category', [CourseController::class, 'getCategories']);

    Route::get('enrollments/{enrollment}/courses',[EnrollmentController::class,'getCourses']);
    Route::get('enrollments/{enrollment}/students',[EnrollmentController::class,'getStudents']);

    Route::get('/reviews/summary', [ReviewController::class, 'getReviewsSummary']);
    Route::get('/reviews/{review}/students', [ReviewController::class,'getReviewStudents']);
    Route::get('/reviews/{review}/courses', [ReviewController::class,'getReviewCourses']);
});

