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

    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
            'username' => strtolower($this->username),
            'name' => ucwords($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => 'required|string|in:student,admin,instructor',
            'username' => 'required|string|max:255|unique:users',
            'bio' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'avatar' => 'file|image|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ];
    }
}
