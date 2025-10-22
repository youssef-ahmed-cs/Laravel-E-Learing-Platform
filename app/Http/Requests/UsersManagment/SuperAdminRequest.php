<?php

namespace App\Http\Requests\UsersManagment;

use Illuminate\Foundation\Http\FormRequest;

class SuperAdminRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required'],
            'username' => ['required', 'max:255', 'string'],
            'bio' => ['nullable', 'max:1000', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
