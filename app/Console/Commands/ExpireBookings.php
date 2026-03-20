<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire old bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Booking::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->whereNotIn('status', ['confirmed', 'cancelled', 'expired'])
            ->update(['status' => 'expired']);

        $this->info('Expired bookings processed');

    }
}
