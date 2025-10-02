<?php

namespace App\Http\Controllers;

use App\Http\Requests\StripeCheckoutRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.sk'));
    }

    public function index(): View
    {
        return view('stripe');
    }

    public function success(): View
    {
        return view('stripe-success');
    }

    public function cancel(): View
    {
        return view('stripe-cancel');
    }

    public function checkout(StripeCheckoutRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $validated['currency'] ?? 'usd',
                        'product_data' => [
                            'name' => $validated['product_name'] ?? 'T-shirt',
                        ],
                        'unit_amount' => $validated['amount'] ?? 2000,
                    ],
                    'quantity' => $validated['quantity'] ?? 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success'),
                'cancel_url' => route('stripe.success'),
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
        }
    }
}
