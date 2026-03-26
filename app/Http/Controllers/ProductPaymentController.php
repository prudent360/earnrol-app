<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\Payment;
use App\Models\ProductPurchase;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ProductPurchaseConfirmed;
use App\Services\Payment\StripeService;
use App\Services\Payment\PayPalService;
use App\Services\CouponService;
use App\Services\ReferralService;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductPaymentController extends Controller
{
    /**
     * Stripe checkout for product
     */
    public function stripeCheckout(Request $request, DigitalProduct $product)
    {
        $user = Auth::user();

        $check = $this->preCheck($product, $user);
        if ($check) return $check;

        // Apply coupon if provided
        $couponData = $this->applyCoupon($request, $product->price, 'product', $product->id);
        $finalAmount = $couponData['final_amount'];

        if ($finalAmount <= 0) {
            return $this->handleFreeByCoupon($user, $product, $couponData);
        }

        if (!Setting::get('stripe_enabled')) {
            return back()->with('error', 'Stripe payments are not enabled.');
        }

        try {
            Stripe::setApiKey(Setting::get('stripe_secret_key', config('services.stripe.secret')));
            $currency = Setting::get('currency', 'GBP');

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $product->title,
                            'description' => $product->description ?? 'Digital product purchase',
                        ],
                        'unit_amount' => intval($finalAmount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('products.stripe.callback') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('products.show', $product),
                'customer_email' => $user->email,
                'metadata' => [
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ],
            ]);

            Payment::create([
                'user_id' => $user->id,
                'payable_type' => DigitalProduct::class,
                'payable_id' => $product->id,
                'amount' => $finalAmount,
                'original_amount' => $couponData['coupon'] ? $product->price : null,
                'discount_amount' => $couponData['discount'],
                'coupon_id' => $couponData['coupon']?->id,
                'reference' => $session->id,
                'gateway' => 'stripe',
                'status' => 'pending',
                'currency' => $currency,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Product Stripe Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }
    }

    /**
     * Stripe callback for product
     */
    public function stripeCallback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('products.index')->with('error', 'Missing session ID.');
        }

        try {
            Stripe::setApiKey(Setting::get('stripe_secret_key', config('services.stripe.secret')));
            $session = Session::retrieve($sessionId);

            if ($session && $session->payment_status === 'paid') {
                $payment = Payment::where('reference', $sessionId)->first();
                return $this->finalizePayment($payment, $session->toArray());
            }
        } catch (\Exception $e) {
            Log::error('Product Stripe Callback Error', ['message' => $e->getMessage()]);
        }

        return redirect()->route('products.index')->with('error', 'Payment verification failed.');
    }

    /**
     * PayPal checkout for product
     */
    public function paypalCheckout(Request $request, DigitalProduct $product)
    {
        $user = Auth::user();

        $check = $this->preCheck($product, $user);
        if ($check) return $check;

        // Apply coupon if provided
        $couponData = $this->applyCoupon($request, $product->price, 'product', $product->id);
        $finalAmount = $couponData['final_amount'];

        if ($finalAmount <= 0) {
            return $this->handleFreeByCoupon($user, $product, $couponData);
        }

        if (!Setting::get('paypal_enabled')) {
            return back()->with('error', 'PayPal payments are not enabled.');
        }

        try {
            $clientId = Setting::get('paypal_client_id', '');
            $clientSecret = Setting::get('paypal_client_secret', '');
            $sandbox = Setting::get('paypal_sandbox', '1') === '1';
            $baseUrl = $sandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            $authResponse = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->post("{$baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

            $token = $authResponse->json('access_token');
            if (!$token) {
                return back()->with('error', 'Unable to connect to PayPal.');
            }

            $currency = strtoupper(Setting::get('currency', 'GBP'));

            $response = Http::withToken($token)
                ->post("{$baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => "product_{$product->id}_user_{$user->id}",
                        'description' => $product->title,
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($finalAmount, 2, '.', ''),
                        ],
                    ]],
                    'application_context' => [
                        'return_url' => route('products.paypal.callback'),
                        'cancel_url' => route('products.show', $product),
                        'brand_name' => Setting::get('app_name', 'EarnRol'),
                        'user_action' => 'PAY_NOW',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $approveUrl = collect($data['links'])->firstWhere('rel', 'approve')['href'] ?? null;

                if ($approveUrl) {
                    Payment::create([
                        'user_id' => $user->id,
                        'payable_type' => DigitalProduct::class,
                        'payable_id' => $product->id,
                        'amount' => $finalAmount,
                        'original_amount' => $couponData['coupon'] ? $product->price : null,
                        'discount_amount' => $couponData['discount'],
                        'coupon_id' => $couponData['coupon']?->id,
                        'reference' => $data['id'],
                        'gateway' => 'paypal',
                        'status' => 'pending',
                        'currency' => $currency,
                    ]);
                    return redirect($approveUrl);
                }
            }

            return back()->with('error', 'Unable to initialize PayPal payment.');
        } catch (\Exception $e) {
            Log::error('Product PayPal Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Unable to initialize PayPal payment.');
        }
    }

    /**
     * PayPal callback for product
     */
    public function paypalCallback(Request $request)
    {
        $orderId = $request->get('token');

        if (!$orderId) {
            return redirect()->route('products.index')->with('error', 'Missing PayPal order ID.');
        }

        try {
            $clientId = Setting::get('paypal_client_id', '');
            $clientSecret = Setting::get('paypal_client_secret', '');
            $sandbox = Setting::get('paypal_sandbox', '1') === '1';
            $baseUrl = $sandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            $authResponse = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->post("{$baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

            $token = $authResponse->json('access_token');
            if ($token) {
                $response = Http::withToken($token)
                    ->post("{$baseUrl}/v2/checkout/orders/{$orderId}/capture");

                if ($response->successful() && $response->json('status') === 'COMPLETED') {
                    $payment = Payment::where('reference', $orderId)->first();
                    return $this->finalizePayment($payment, $response->json());
                }
            }
        } catch (\Exception $e) {
            Log::error('Product PayPal Callback Error', ['message' => $e->getMessage()]);
        }

        return redirect()->route('products.index')->with('error', 'PayPal payment verification failed.');
    }

    /**
     * Bank transfer form for product
     */
    public function bankTransferForm(DigitalProduct $product)
    {
        $user = Auth::user();

        $check = $this->preCheck($product, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $pendingPayment = Payment::where('user_id', $user->id)
            ->where('payable_type', DigitalProduct::class)
            ->where('payable_id', $product->id)
            ->where('gateway', 'bank_transfer')
            ->where('status', 'pending')
            ->first();

        $bankDetails = [
            'bank_name'       => Setting::get('bank_name', ''),
            'account_name'    => Setting::get('bank_account_name', ''),
            'sort_code'       => Setting::get('bank_sort_code', ''),
            'account_number'  => Setting::get('bank_account_number', ''),
            'iban'            => Setting::get('bank_iban', ''),
            'reference_note'  => Setting::get('bank_reference_note', ''),
            'currency_symbol' => Setting::get('currency_symbol', '£'),
        ];

        return view('products.bank-transfer', compact('product', 'bankDetails', 'pendingPayment'));
    }

    /**
     * Submit bank transfer receipt for product
     */
    public function bankTransferSubmit(Request $request, DigitalProduct $product)
    {
        $user = Auth::user();

        $check = $this->preCheck($product, $user);
        if ($check) return $check;

        // Apply coupon if provided
        $couponData = $this->applyCoupon($request, $product->price, 'product', $product->id);
        $finalAmount = $couponData['final_amount'];

        if ($finalAmount <= 0) {
            return $this->handleFreeByCoupon($user, $product, $couponData);
        }

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'user_id'         => $user->id,
            'payable_type'    => DigitalProduct::class,
            'payable_id'      => $product->id,
            'amount'          => $finalAmount,
            'original_amount' => $couponData['coupon'] ? $product->price : null,
            'discount_amount' => $couponData['discount'],
            'coupon_id'       => $couponData['coupon']?->id,
            'reference'       => 'BT-' . strtoupper(uniqid()),
            'gateway'         => 'bank_transfer',
            'status'          => 'pending',
            'currency'        => Setting::get('currency', 'GBP'),
            'receipt_path'    => $receiptPath,
        ]);

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new \App\Notifications\NewBankTransferAdmin($payment));

        return redirect()->route('products.show', $product)
            ->with('success', 'Receipt uploaded! Your payment is being reviewed. You will get access once approved.');
    }

    /**
     * Pre-check: already purchased
     */
    protected function preCheck(DigitalProduct $product, $user)
    {
        if (ProductPurchase::where('user_id', $user->id)->where('digital_product_id', $product->id)->exists()) {
            return redirect()->route('products.show', $product)->with('error', 'You already own this product.');
        }

        if ($product->status !== 'published') {
            return redirect()->route('products.index')->with('error', 'This product is not available.');
        }

        return null;
    }

    /**
     * Finalize payment and grant access
     */
    protected function finalizePayment($payment, $gatewayResponse)
    {
        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);

            $purchase = ProductPurchase::firstOrCreate([
                'user_id' => $payment->user_id,
                'digital_product_id' => $payment->payable_id,
            ], [
                'payment_id' => $payment->id,
                'purchased_at' => now(),
            ]);

            $product = DigitalProduct::find($payment->payable_id);
            $user = User::find($payment->user_id);

            $user->notify(new ProductPurchaseConfirmed($product));

            ReferralService::creditCommissionIfEligible($payment);

            // Record coupon usage
            if ($payment->coupon_id) {
                CouponUsage::create([
                    'coupon_id'       => $payment->coupon_id,
                    'user_id'         => $payment->user_id,
                    'payment_id'      => $payment->id,
                    'discount_amount' => $payment->discount_amount,
                ]);
                $payment->coupon?->increment('used_count');
            }

            return redirect()->route('products.show', $product)
                ->with('success', 'Payment successful! You can now download ' . $product->title . '.');
        }

        return redirect()->route('products.index')->with('error', 'Payment already processed or not found.');
    }

    /**
     * Apply coupon from request
     */
    protected function applyCoupon(Request $request, float $price, string $type, int $itemId): array
    {
        $code = $request->input('coupon_code');
        if (! $code) {
            return ['coupon' => null, 'discount' => 0, 'final_amount' => $price, 'message' => ''];
        }

        $couponService = app(CouponService::class);
        $result = $couponService->validate($code, $price, $type, $itemId);
        if (! $result['valid']) {
            return ['coupon' => null, 'discount' => 0, 'final_amount' => $price, 'message' => $result['message']];
        }

        return $result;
    }

    /**
     * Handle 100% discount — grant access without payment gateway
     */
    protected function handleFreeByCoupon($user, DigitalProduct $product, array $couponData)
    {
        $payment = Payment::create([
            'user_id'         => $user->id,
            'payable_type'    => DigitalProduct::class,
            'payable_id'      => $product->id,
            'amount'          => 0,
            'original_amount' => $product->price,
            'discount_amount' => $couponData['discount'],
            'coupon_id'       => $couponData['coupon']->id,
            'reference'       => 'COUPON-' . strtoupper(uniqid()),
            'gateway'         => 'coupon',
            'status'          => 'completed',
            'currency'        => Setting::get('currency', 'GBP'),
        ]);

        return $this->finalizePayment($payment, ['coupon' => $couponData['coupon']->code]);
    }
}
