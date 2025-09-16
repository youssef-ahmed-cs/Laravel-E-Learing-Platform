<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationCreateCourse;
use App\Http\Requests\CoursesManagment\{StoreCourseRequest, UpdateCourseRequest};
use App\Http\Resources\CourseResource;
use App\Models\{User, Course};
use App\Notifications\CourseCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Course::class);
        $courses = Course::with('categories')->paginate(10);

        return response()->json([
            'Courses' => CourseResource::collection($courses),
        ]);
    }

    public function show(Course $course): CourseResource
    {
        $this->authorize('view', $course);

        return new CourseResource($course);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $this->authorize('create', Course::class);
        $course = Course::create($request->validated());

//        Notification::send($users, new SendNotificationCreateCourse($course));
        SendNotificationCreateCourse::dispatch( $course);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResource($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): CourseResource
    {
        $this->authorize('update', $course);
        $course->update($request->validated());

        return new CourseResource($course);
    }

    public function destroy(Course $course): JsonResponse
    {
        $this->authorize('delete', $course);
        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully',
            'status' => 'success',
            'course' => $course->title,
        ], 204);
    }

    public function getCategories(Course $course): JsonResponse
    {
        $this->authorize('view', $course);
        $categories = $course->categories()->pluck('name', 'id');

        return response()->json([
            "your course: {$course->title} belongsTo " => $categories,
        ]);
    }

    public function getLessons(Course $course): JsonResponse
    {
        $this->authorize('view', $course);
        $lessons = $course->lessons()->get(['id', 'title', 'description', 'status', 'level', 'duration', 'instructor_id']);

        return response()->json([
            'lesson' => $course->title,
            'courses' => $lessons,
        ]);
    }
}
