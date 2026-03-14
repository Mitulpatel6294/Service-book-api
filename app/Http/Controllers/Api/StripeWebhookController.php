<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\BrevoMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('stripe webhook hit');
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;


            if (!$bookingId) {
                return response()->json(['error' => 'Missing booking id'], 400);
            }
            $booking = Booking::find($bookingId);

            if (!$booking || $booking->payment_status === 'paid') {
                return response()->json(['already paid' => true]);
            }
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->quoted_price,
                'transaction_id' => $session->payment_intent,
                'status' => 'paid',
                'paid_at' => now()
            ]);
            $brevo = new BrevoMailService();
            $html = view('emails.bookingconfirm', ['booking' => $booking])->render();
            $brevo->send(
                $booking->provider->email,
                'Booking confirmed with payment',
                $html
            );
            $brevo->send(
                $booking->customer->email,
                'Booking confirmed with payment',
                $html
            );
        }
        if ($event->type === 'payment_intent.payment_failed') {

            $intent = $event->data->object;
            $bookingId = $intent->metadata->booking_id ?? null;

            $booking = Booking::find($bookingId);

            if ($booking && $booking->payment_status !== 'paid') {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'accepted'
                ]);

                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->quoted_price,
                    'transaction_id' => $intent->id,
                    'status' => 'failed'
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
}
