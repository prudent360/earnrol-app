<?php

namespace App\Services\Payment;

use App\Models\Cohort;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = Setting::get('paypal_client_id', '');
        $this->clientSecret = Setting::get('paypal_client_secret', '');
        $sandbox = Setting::get('paypal_sandbox', '1') === '1';
        $this->baseUrl = $sandbox
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    protected function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('PayPal Auth Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Auth Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function createOrder(Cohort $cohort, User $user): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        try {
            $currency = strtoupper(Setting::get('currency', 'GBP'));

            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => "cohort_{$cohort->id}_user_{$user->id}",
                        'description' => $cohort->title,
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($cohort->price, 2, '.', ''),
                        ],
                    ]],
                    'application_context' => [
                        'return_url' => route('payments.paypal.callback') . '?cohort_id=' . $cohort->id,
                        'cancel_url' => route('dashboard'),
                        'brand_name' => Setting::get('app_name', 'EarnRol'),
                        'user_action' => 'PAY_NOW',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $approveUrl = collect($data['links'])->firstWhere('rel', 'approve')['href'] ?? null;

                return [
                    'id' => $data['id'],
                    'approve_url' => $approveUrl,
                ];
            }

            Log::error('PayPal Create Order Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Create Order Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function captureOrder(string $orderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'COMPLETED') {
                    return $data;
                }
            }

            Log::error('PayPal Capture Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Capture Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
