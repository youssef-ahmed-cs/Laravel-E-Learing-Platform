<?php

namespace App\Http\Requests\EnrollmenttManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
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
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'enrolled_at' => 'sometimes|required|date',
            'completed_at' => 'sometimes|required|date',
            'progress_percentage' => 'sometimes|required|integer|min:0|max:100',
        ];
    }
}
