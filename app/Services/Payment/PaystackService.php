<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
    }

    /**
     * Initialize a transaction.
     *
     * @param array $data [email, amount, reference, callback_url, metadata]
     * @return array|null
     */
    public function initializeTransaction(array $data): ?array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transaction/initialize", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paystack Initialization Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Paystack Initialization Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verify a transaction.
     *
     * @param string $reference
     * @return array|null
     */
    public function verifyTransaction(string $reference): ?array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paystack Verification Error', [
                'status' => $response->status(),
                'reference' => $reference,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Paystack Verification Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
