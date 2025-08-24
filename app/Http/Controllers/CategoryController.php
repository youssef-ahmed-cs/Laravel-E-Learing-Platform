<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryManagement\StoreCategoryRequest;
use App\Http\Requests\CategoryMenegemnt\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseResource;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;

class CategoryController extends Controller
{
    public function index(){
        $this->authorize('viewAny', Category::class);
        $categories = Category::with('courses')->pluck('name', 'id');
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function show($id){
        $category = Category::with('courses')->findOrFail($id);
        $this->authorize('view', $category);
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request){
        $this->authorize('create', Category::class);
        $category = Category::create($request->validated());
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->fresh()
        ]);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json([
            'message' => "Category {$category->name} deleted successfully"
        ]);
    }

    public function getCourses(Category $category)
    {
        $this->authorize('view', $category);
        $courses = $category->courses()->get(['id', 'title', 'description','status', 'level', 'duration', 'instructor_id']);
        return response()->json([
            'category' => $category->name,
            'courses' => $courses
        ]);
    }
}
