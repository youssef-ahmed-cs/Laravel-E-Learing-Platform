<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewManagement\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReview;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::with(['user', 'course'])->latest()->paginate(10);
        return ReviewResource::collection($reviews);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Review $review, User $user): JsonResponse
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json([
            'message' => 'Review deleted successfully',
            'review' => new ReviewResource($review)
        ]);
    }

    public function show(Review $review): ReviewResource
    {
        $this->authorize('view', $review);
        return new ReviewResource($review);
    }

    public function store(StoreReviewRequest $request, Review $review): ReviewResource
    {
        $this->authorize('create', $review);
        $review = Review::create($request->validated());
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewReview($review));
        return new ReviewResource($review);
    }

    public function update(StoreReviewRequest $request, Review $review): ReviewResource
    {
        $this->authorize('update', $review);
        $review->update($request->validated());
        return new ReviewResource($review);
    }

    public function getReviewsSummary(): JsonResponse
    {
        //$this->authorize('viewAny', Review::class);
        $this->authorize('is_Admin');
        $summary = [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating') ?: 0, 2),
        ];
        return response()->json($summary);
    }


    public function getReviewStudents(Review $review): JsonResponse
    {
        $this->authorize('view', $review);
        $students = $review->user->get();
        return response()->json($students);
    }

    public function getReviewCourses(Review $review): JsonResponse
    {
        $this->authorize('view', $review);
        $courses = $review->course()->get();
        return response()->json($courses);
    }
}
