<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordByOtp extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'new_password' => ['required', 'string'],
            'confirm_new_password' => ['required', 'same:new_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required',
            'email.email' => 'The email must be a valid email address',
            'token.required' => 'The token field is required',
            'new_password.required' => 'The new password field is required',
            'confirm_new_password.required' => 'The confirm new password field is required',
            'confirm_new_password.same' => 'The confirm new password and new password must match',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
