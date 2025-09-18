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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,instructor,student',
            'bio' => 'nullable|string|max:255|min:10',
            'phone' => 'nullable|string|unique:users,phone|',
            'username' => ['required', 'string', 'max:255', 'min:3', 'unique:users,username'],
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048|file',
        ];
    }
}
