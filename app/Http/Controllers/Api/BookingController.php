<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Services\BookingServices;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(BookingRequest $request, BookingServices $service)
    {
        return response()->json(
            $service->create(
                auth()->id(),
                $request->validated(),
                $request->file('image')
            )
        );
    }
    public function quote($id, Request $request, BookingServices $service)
    {
        $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'quoted_duration' => 'required|integer|min:1'
        ]);

        return response()->json(
            $service->quote($id, auth()->id(), $request->only('quoted_price', 'quoted_duration'))
        );
    }
    public function accept($id, BookingServices $service)
    {
        return response()->json($service->acceptQuote($id, auth()->id()));
    }

    public function reject($id, BookingServices $service)
    {
        return response()->json($service->rejectQuote($id, auth()->id()));
    }

    public function paid($id, BookingServices $service)
    {
        return response()->json($service->markPaid($id));
    }
}
