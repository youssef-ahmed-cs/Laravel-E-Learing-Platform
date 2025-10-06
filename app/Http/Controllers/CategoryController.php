<?php namespace App\Http\Controllers;

use App\Http\Requests\CategoryManagement\StoreCategoryRequest;
use App\Http\Requests\CategoryMenegemnt\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::with('courses')->pluck('name', 'id');
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function show(Category $category): CategoryResource
    {
        $this->authorize('view', $category);
        $category->with('courses')->first();
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', Category::class);
        $category = Category::create($request->validated());
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->fresh()
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json([
            'message' => "Category {$category->name} deleted successfully"
        ]);
    }

    public function getCourses(Category $category): JsonResponse
    {
        $this->authorize('view', $category);
        $courses = $category->courses()->get(['id', 'title', 'description', 'status', 'level', 'duration', 'instructor_id']);
        return response()->json([
            'category' => $category->name,
            'courses' => $courses
        ]);
    }

    public function getTrashed(): JsonResponse
    {
        $this->authorize('getAllTrashed', Category::class);

        $trashedCategories = Category::onlyTrashed()->get();

        return response()->json([
            'trashed_categories' => $trashedCategories,
        ]);
    }

    public function restore($id): JsonResponse
    {
        $category = Category::withTrashed()->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $this->authorize('restore', $category);
        if ($category->trashed()) {
            $category->restore();
            return response()->json(['message' => 'Category restored successfully', 'category' => $category]);
        }
        Log::info('Category restore attempted but category is not deleted. ID: ' . $category->id);
        return response()->json(['message' => 'Category is not deleted'], 400);
    }

    public function forceDelete($id): JsonResponse
    {
        $category = Category::withTrashed()->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $this->authorize('forceDelete', $category);
        if ($category->trashed()) {
            Log::warning('Permanently deleting category ID: ' . $category->id);
            $category->forceDelete();
            return response()->json(['message' => 'Category permanently deleted']);
        }
        return response()->json(['message' => 'Category must be soft deleted first'], 400);
    }

    /**
     * Search for categories by name.
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $name): JsonResponse
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->where('name', 'LIKE', "%{$name}%")
            ->get(['id', 'name', 'description']);

        if ($categories->isEmpty()) {
            Log::info("No categories found matching the search criteria: {$name}");
            return response()->json([
                'message' => 'No categories found matching the search criteria.'
            ], 404);
        }

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function getCategoryCourses(Category $category): JsonResponse
    {
        $this->authorize('view', $category);
        $categories = $category->courses()->get(['id', 'title', 'description', 'status', 'level', 'duration', 'instructor_id']);
        return response()->json([
            'categories' => $categories
        ]);
    }
}
