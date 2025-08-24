<?php

namespace App\Http\Requests\CoursesManagment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|string|in:beginner,intermediate,advanced',
            'status' => 'required|string|in:draft,published,archived',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
