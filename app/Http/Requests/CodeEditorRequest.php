<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeEditorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:10000',
            'lang' => ['required', 'string', \App\Enums\CodeLanguage::rule()],
            'version' => 'nullable|string|max:20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
