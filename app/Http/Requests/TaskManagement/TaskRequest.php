<?php

namespace App\Http\Requests\TaskManagement;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255' , 'min:3' ],
            'priority' => ['sometimes', 'in:low,medium,high'],
            'content' => ['sometimes', 'string', 'max:1000' , 'min:3' ],
            'dateline' => ['sometimes', 'date'],
            'completed' => ['boolean' , 'sometimes'],
            'user_id' => ['sometimes', 'exists:users,id'],
            'lesson_id' => ['sometimes', 'exists:lessons,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
