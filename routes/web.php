<?php

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment-success', function (Request $request) {

    $booking = Booking::find($request->booking_id);

    if ($booking && $booking->payment_status === 'paid') {
        return "<h2>Payment Successful</h2><p>Your booking is confirmed.</p>";
    }

    return "<h2>Processing Payment</h2><p>Please wait and refresh.</p>";
});

Route::get('/payment-cancel', function () {
    return "<h2>Payment Cancelled</h2><p>You can retry payment from your dashboard.</p>";
});