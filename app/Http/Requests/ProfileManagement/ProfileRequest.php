<?php

namespace App\Http\Requests\ProfileManagement;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['nullable', 'string', 'max:50'],
            'age' => ['nullable', 'integer'],
            'address' => ['nullable'],
            'bio' => ['nullable', 'string', 'max:1000', 'min:10'],
            'user_id' => ['nullable', 'exists:users,id', 'unique:profiles,user_id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
