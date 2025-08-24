<?php

namespace App\Http\Requests\EnrollmenttManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'enrolled_at' => 'required|date',
            'completed_at' => 'nullable|date',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ];
    }
}
