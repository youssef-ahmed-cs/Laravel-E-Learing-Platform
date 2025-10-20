<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoursesManagment\StoreCourseRequest;
use App\Http\Requests\CoursesManagment\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Jobs\SendNotificationCreateCourse;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
//        $this->authorize('viewAny', Course::class);
        $courses = Course::with('categories')->paginate(10);

        return response()->json([
            'Courses' => CourseResource::collection($courses),
        ]);
    }

    public function show(Course $course): CourseResource
    {
       // $this->authorize('view', $course);

        return new CourseResource($course);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        #$this->authorize('create', Course::class);
        $course = Course::create($request->validated());

        if ($request->hasFile('thumbnail')) {
            $thumbnailName = $course->name.'.'.$request->file('thumbnail')->getClientOriginalExtension();
            $thumbnailPath = $request->file('thumbnail')->storeAs('thumbnails', $thumbnailName, 'public');
            $course->update(['thumbnail' => $thumbnailPath]);
        }

        SendNotificationCreateCourse::dispatch($course);

        Log::info('Course created successfully', [
            'course_id' => $course->id,
            'title' => $course->title,
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResource($course),
        ],201);
    }

    public function update(UpdateCourseRequest $request, Course $course): CourseResource
    {
//        $this->authorize('update', $course);
        $course->update($request->validated());

        return new CourseResource($course);
    }

    public function destroy(Course $course): JsonResponse
    {
//        $this->authorize('delete', $course);
        $course->delete();

        Log::warning('Course deleted successfully', [
            'course_id' => $course->id,
            'title' => $course->title,
        ]);

        return response()->json([
            'message' => 'Course deleted -Forced- successfully',
            'status' => 'success',
            'course' => $course->title,
        ], 200);
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

    public function restore(int $id): JsonResponse
    {
        $course = Course::onlyTrashed()->find($id);

        if (! $course) {
            return response()->json(['message' => 'corse not found or not trashed.'], 404);
        }

//        $this->authorize('restore', $course);

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/courses.log'),
        ])->info('Restoring course', ['course_id' => $course->id, 'title' => $course->title]);

        $course->restore();

        return response()->json([
            'message' => 'Profile restored successfully',
            'data' => new CourseResource($course),
        ], 200);
    }
}
