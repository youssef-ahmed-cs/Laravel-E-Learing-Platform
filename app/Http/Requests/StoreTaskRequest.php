<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255' , 'min:3' ],
            'priority' => ['required', 'in:low,medium,high'],
            'content' => ['nullable', 'string', 'max:1000' , 'min:3' ],
            'dateline' => ['required', 'date'],
            'completed' => ['boolean' , 'required'],
            'user_id' => ['required', 'exists:users,id'],
            'lesson_id' => ['required', 'exists:lessons,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
