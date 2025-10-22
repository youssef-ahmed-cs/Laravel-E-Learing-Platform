<?php

namespace App\Http\Requests\LessonManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'video_url' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'is_free' => 'required|boolean',
            'duration' => 'required|integer|min:1',
        ];
    }
}
