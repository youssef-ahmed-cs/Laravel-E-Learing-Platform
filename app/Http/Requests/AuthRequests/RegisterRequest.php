<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => Str::lower($this->email),
            'username' => Str::lower($this->username),
            'name' => Str::ucfirst($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,instructor,student',
            'bio' => 'nullable|max:255|min:10',
            'phone' => 'nullable|string|unique:users,phone',
            'username' => ['required', 'string', 'max:255', 'min:3', 'unique:users,username'],
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048|file',
            'is_premium' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Full Name',
            'email' => 'E-mail',
            'password' => 'Password',
            'username' => 'Username',
        ];
    }
}
