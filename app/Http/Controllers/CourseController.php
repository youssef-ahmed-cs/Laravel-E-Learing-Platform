<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoursesManagment\StoreCourseRequest;
use App\Http\Requests\CoursesManagment\UpdateCourseRequest;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use App\Models\User;
use App\Notifications\CourseCreated;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $courses = Course::with('categories')->paginate(10);

        return response()->json([
            'Courses' => CourseResource::collection($courses),
        ]);
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        return new CourseResource($course);
    }

    public function store(StoreCourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $course = Course::create($request->validated());
        $studentsAndInstructors = User::whereIn('role', ['student', 'instructor'])->get();

        Notification::send($studentsAndInstructors, new CourseCreated($course));

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResource($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        $course->update($request->validated());

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully',
            'status' => 'success',
            'course' => $course->title,
        ], 204);
    }

    public function getCategories(Course $course)
    {
        $this->authorize('view', $course);
        $categories = $course->categories()->pluck('name', 'id');

        return response()->json([
            "your course: {$course->title} belongsTo " => $categories,
        ]);
    }

    public function getLessons(Course $course)
    {
        $this->authorize('view', $course);
        $lessons = $course->lessons()->get(['id', 'title', 'description', 'status', 'level', 'duration', 'instructor_id']);

        return response()->json([
            'lesson' => $course->title,
            'courses' => $lessons,
        ]);
    }
}
