<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Validation\ValidationException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentService
{
    /**
     * Create a new class instance.
     */
    public function createCheckout($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        if ($booking->payment_status === 'paid') {
            throw ValidationException::withMessages(['payment' => 'Already paid']);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'metadata' => [
                'booking_id' => $booking->id
            ],

            'payment_intent_data' => [
                'metadata' => [
                    'booking_id' => $booking->id
                ]
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => $booking->service->title,
                        ],
                        'unit_amount' => $booking->quoted_price * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'success_url' => config('app.url') . '/payment-success?booking_id=' . $booking->id,
            'cancel_url' => config('app.url') . '/payment-cancel?booking_id=' . $booking->id,
        ]);

        return $session->url;
    }

}
