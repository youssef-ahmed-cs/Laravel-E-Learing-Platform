<?php

namespace App\Http\Requests\ProfileManagement;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'age' => ['required', 'integer'],
            'address' => ['required'],
            'bio' => ['required', 'string', 'max:1000' , 'min:10'],
            'user_id' => ['required', 'exists:users,id', 'unique:profiles,user_id' ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
