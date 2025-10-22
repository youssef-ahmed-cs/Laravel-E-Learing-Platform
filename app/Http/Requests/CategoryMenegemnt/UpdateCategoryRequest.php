<?php

namespace App\Http\Requests\CategoryMenegemnt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
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
            'name' => 'sometimes|required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'slug' => 'sometimes|required|string|max:255|unique:categories,slug',
        ];
    }
}
