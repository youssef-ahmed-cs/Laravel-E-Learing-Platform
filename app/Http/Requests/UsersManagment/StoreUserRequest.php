<?php

namespace App\Http\Requests\UsersManagment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'email' => strtolower($this->email),
            'username' => strtolower($this->username),
            'name' => ucwords($this->name),
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
            'phone' => 'nullable|string|unique:users,phone|',
            'username' => ['required', 'string', 'max:255', 'min:3', 'unique:users,username'],
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048|file',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not exceed 255 characters',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.string' => 'Email must be a string',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email must not exceed 255 characters',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.confirmed' => 'Password confirmation does not match',
            'role.required' => 'Role is required',
            'role.in' => 'Role must be one of the following: admin, instructor, student',
            'bio.string' => 'Bio must be a string',
            'bio.max' => 'Bio must not exceed 255 characters',
            'bio.min' => 'Bio must be at least 10 characters',
            'phone.string' => 'Phone must be a string',
            'phone.unique' => 'Phone already exists',
            'username.required' => 'Username is required',
            'username.string' => 'Username must be a string',
            'username.max' => 'Username must not exceed 255 characters',
            'username.min' => 'Username must be at least 3 characters',
            'username.unique' => 'Username already exists',
            'avatar.image' => 'Avatar must be an image',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, jpg, png',
            'avatar.max' => 'Avatar must not exceed 2MB in size',
        ];
    }
}
