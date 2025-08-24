<?php namespace App\Http\Controllers;


use App\Http\Requests\EnrollmenttManagement\StoreEnrollmentRequest;
use App\Http\Requests\EnrollmenttManagement\UpdateEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function index() //show all enrollment
    {
        $this->authorize('viewAny', Enrollment::class);
        $enrollments = Enrollment::with('user', 'course')->get();
        return EnrollmentResource::collection($enrollments);
    }

    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);
        return new EnrollmentResource($enrollment);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $this->authorize('create', Enrollment::class);
        $request->validated();
        $enrollment = Enrollment::create($request->validated());
        return response()->json([
            'message' => 'Enrollment created successfully',
            'Info' => new EnrollmentResource($enrollment)
        ]);
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        $enrollment->update($request->validated());
        return response()->json([
            'message' => 'Enrollment updated successfully',
            'Info' => new EnrollmentResource($enrollment)
        ]);
    }

    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);
        $enrollment->delete();
        return response()->json([
            'message' => 'Enrollment deleted successfully',
            'some info' => "student name: {$enrollment->user->name} and course title is {$enrollment->course->title}"
        ]);
    }

    public function getCourses($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $this->authorize('view', $enrollment);
        $course = $enrollment->course;
        return response()->json([
            'course' => $course
        ]);
    }

    public function getStudents($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $this->authorize('view', $enrollment);
        $student = $enrollment->user;
        return response()->json([
            'student' => $student
        ]);
    }
}
