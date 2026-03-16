<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = env('PAYSTACK_SECRET_KEY');
        $this->baseUrl = env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co');
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/transaction/initialize', $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Paystack initialization failed: ' . $response->body());
        return null;
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/transaction/verify/' . $reference);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Paystack verification failed: ' . $response->body());
        return null;
    }
}
