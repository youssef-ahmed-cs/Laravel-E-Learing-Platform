<?php

namespace App\Http\Requests\CodeEditorManagement;

use Illuminate\Foundation\Http\FormRequest;

class CodeEditorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:10000',
            'language' => ['required', 'string', \App\Enums\CodeLanguage::rule()],
            'version' => 'required|string|max:20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
