<?php

namespace App\Http\Requests\ForgetPasswordRequests;

use Illuminate\Foundation\Http\FormRequest;

class resetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'two_factor_code' => 'required|digits:4',
            'password' => 'required|min:8',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
