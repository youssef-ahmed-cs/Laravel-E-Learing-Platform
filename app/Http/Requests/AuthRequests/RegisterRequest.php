<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => 'required|string|in:student,admin,instructor',
            'username' => 'required|string|max:255|unique:users',
            'bio' => 'nullable|string',
            'phone' => 'nullable|numeric',
        ];
    }
}
