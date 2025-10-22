<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'priority' => ['required', 'in:low,medium,high'],
            'content' => ['nullable', 'string', 'max:1000', 'min:3'],
            'dateline' => ['required', 'date'],
            'completed' => ['boolean', 'required'],
            'user_id' => ['required', 'exists:users,id,role,student'],
            'lesson_id' => ['required', 'exists:lessons,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'student',
            'lesson_id' => 'lesson',
            'content' => 'description',
            'title' => 'Name of task',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.min' => 'The title must be at least 3 characters.',
            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority must be one of the following: low, medium, high.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 1000 characters.',
            'content.min' => 'The content must be at least 3 characters.',
            'dateline.required' => 'The dateline field is required.',
            'dateline.date' => 'The dateline is not a valid date.',
            'completed.required' => 'The completed field is required.',
            'completed.boolean' => 'The completed field must be true or false.',
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user_id is invalid or the user is not a student.',
            'lesson_id.required' => 'The lesson_id field is required.',
            'lesson_id.exists' => 'The selected lesson_id is invalid.',
        ];
    }
}
