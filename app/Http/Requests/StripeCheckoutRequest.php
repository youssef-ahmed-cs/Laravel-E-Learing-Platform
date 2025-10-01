<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StripeCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'integer', 'min:50'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'currency' => ['sometimes', 'string', 'in:usd,eur,gbp'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'The amount must be at least $0.50.',
            'quantity.max' => 'Quantity cannot exceed 100 items.',
            'currency.in' => 'Currency must be USD, EUR, or GBP.',
        ];
    }
}
