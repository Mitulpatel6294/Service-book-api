<?php

namespace App\services;

use Illuminate\Support\Facades\Http;

class BrevoMailService
{
    /**
     * Create a new class instance.
     */
    public function send($to, $subject, $html)
    {
        Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'email' => env('MAIL_FROM_ADDRESS'),
                        'name' => env('MAIL_FROM_NAME')
                    ],
                    'to' => [
                        ['email' => $to]
                    ],
                    'subject' => $subject,
                    'htmlContent' => $html
                ]);
    }
}
