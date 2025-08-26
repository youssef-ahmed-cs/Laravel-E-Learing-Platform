<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewManagement\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReview;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::with(['user', 'course'])->latest()->paginate(10);
        return ReviewResource::collection($reviews);
    }

    public function destroy(Review $review, User $user)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json([
            'message' => 'Review deleted successfully',
            'review' => new ReviewResource($review)
        ]);
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);
        return new ReviewResource($review);
    }

    public function store(StoreReviewRequest $request, Review $review)
    {
        $this->authorize('create', $review);
        $review = Review::create($request->validated());
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin)
            $admin->notify(new NewReview($review));

        return new ReviewResource($review);
    }

    public function update(StoreReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        $review->update($request->validated());
        return new ReviewResource($review);
    }

    public function getReviewsSummary()
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::with(['user', 'course'])->latest()->get();

        $summary = [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating'),
        ];

        return response()->json($summary);
    }

    public function getReviewStudents(Review $review)
    {
        $this->authorize('view', $review);
        $students = $review->user->get();
        return response()->json($students);
    }

    public function getReviewCourses(Review $review)
    {
        $this->authorize('view', $review);
        $courses = $review->course->get();
        return response()->json($courses);
    }
}
