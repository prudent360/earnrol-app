<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Mail\TemplateMail;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewBankTransferAdmin;
use App\Notifications\NewEnrollmentAdmin;
use App\Services\Payment\StripeService;
use App\Services\Payment\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    protected StripeService $stripe;
    protected PayPalService $paypal;

    public function __construct(StripeService $stripe, PayPalService $paypal)
    {
        $this->stripe = $stripe;
        $this->paypal = $paypal;
    }

    /**
     * Stripe checkout
     */
    public function stripeCheckout(Cohort $cohort)
    {
        $user = Auth::user();

        $check = $this->preCheck($cohort, $user);
        if ($check) return $check;

        if (!Setting::get('stripe_enabled')) {
            return back()->with('error', 'Stripe payments are not enabled.');
        }

        $session = $this->stripe->createCheckoutSession($cohort, $user);

        if ($session) {
            Payment::create([
                'user_id' => $user->id,
                'payable_type' => Cohort::class,
                'payable_id' => $cohort->id,
                'amount' => $cohort->price,
                'reference' => $session->id,
                'gateway' => 'stripe',
                'status' => 'pending',
                'currency' => Setting::get('currency', 'GBP'),
            ]);
            return redirect($session->url);
        }

        return back()->with('error', 'Unable to initialize Stripe payment. Please try again.');
    }

    /**
     * Stripe callback
     */
    public function stripeCallback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Missing session ID.');
        }

        $session = $this->stripe->verifySession($sessionId);

        if ($session) {
            $payment = Payment::where('reference', $sessionId)->first();
            return $this->finalizePayment($payment, $session->toArray());
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }

    /**
     * PayPal checkout
     */
    public function paypalCheckout(Cohort $cohort)
    {
        $user = Auth::user();

        $check = $this->preCheck($cohort, $user);
        if ($check) return $check;

        if (!Setting::get('paypal_enabled')) {
            return back()->with('error', 'PayPal payments are not enabled.');
        }

        $order = $this->paypal->createOrder($cohort, $user);

        if ($order && $order['approve_url']) {
            Payment::create([
                'user_id' => $user->id,
                'payable_type' => Cohort::class,
                'payable_id' => $cohort->id,
                'amount' => $cohort->price,
                'reference' => $order['id'],
                'gateway' => 'paypal',
                'status' => 'pending',
                'currency' => Setting::get('currency', 'GBP'),
            ]);
            return redirect($order['approve_url']);
        }

        return back()->with('error', 'Unable to initialize PayPal payment. Please try again.');
    }

    /**
     * PayPal callback
     */
    public function paypalCallback(Request $request)
    {
        $orderId = $request->get('token');
        $cohortId = $request->get('cohort_id');

        if (!$orderId) {
            return redirect()->route('dashboard')->with('error', 'Missing PayPal order ID.');
        }

        $result = $this->paypal->captureOrder($orderId);

        if ($result) {
            $payment = Payment::where('reference', $orderId)->first();
            return $this->finalizePayment($payment, $result);
        }

        return redirect()->route('dashboard')->with('error', 'PayPal payment verification failed.');
    }

    /**
     * Show bank transfer details page
     */
    public function bankTransferForm(Cohort $cohort)
    {
        $user = Auth::user();

        $check = $this->preCheck($cohort, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        // Check if user already has a pending transfer for this cohort
        $pendingPayment = Payment::where('user_id', $user->id)
            ->where('payable_type', Cohort::class)
            ->where('payable_id', $cohort->id)
            ->where('gateway', 'bank_transfer')
            ->where('status', 'pending')
            ->first();

        $bankDetails = [
            'bank_name'        => Setting::get('bank_name', ''),
            'account_name'     => Setting::get('bank_account_name', ''),
            'sort_code'        => Setting::get('bank_sort_code', ''),
            'account_number'   => Setting::get('bank_account_number', ''),
            'iban'             => Setting::get('bank_iban', ''),
            'reference_note'   => Setting::get('bank_reference_note', ''),
            'currency_symbol'  => Setting::get('currency_symbol', '£'),
        ];

        return view('payments.bank-transfer', compact('cohort', 'bankDetails', 'pendingPayment'));
    }

    /**
     * Submit bank transfer receipt
     */
    public function bankTransferSubmit(Request $request, Cohort $cohort)
    {
        $user = Auth::user();

        $check = $this->preCheck($cohort, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'user_id'       => $user->id,
            'payable_type'  => Cohort::class,
            'payable_id'    => $cohort->id,
            'amount'        => $cohort->price,
            'reference'     => 'BT-' . strtoupper(uniqid()),
            'gateway'       => 'bank_transfer',
            'status'        => 'pending',
            'currency'      => Setting::get('currency', 'GBP'),
            'receipt_path'  => $receiptPath,
        ]);

        // Notify all admins
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new NewBankTransferAdmin($payment));

        return redirect()->route('dashboard')
            ->with('success', 'Receipt uploaded! Your payment is being reviewed. You will be enrolled once approved.');
    }

    /**
     * Pre-check: already enrolled or cohort full
     */
    protected function preCheck(Cohort $cohort, $user)
    {
        if ($user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'You are already enrolled in this cohort.');
        }

        if ($cohort->isFull()) {
            return redirect()->route('dashboard')->with('error', 'This cohort is full.');
        }

        return null;
    }

    /**
     * Finalize payment and create enrollment
     */
    protected function finalizePayment($payment, $gatewayResponse)
    {
        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);

            CohortEnrollment::firstOrCreate([
                'user_id' => $payment->user_id,
                'cohort_id' => $payment->payable_id,
            ], [
                'payment_id' => $payment->id,
                'enrolled_at' => now(),
            ]);

            $cohort = Cohort::find($payment->payable_id);
            $user = User::find($payment->user_id);

            // Send enrollment confirmation email
            try {
                Mail::to($user->email)->send(new TemplateMail('enroll', [
                    'name' => $user->name,
                    'cohort_name' => $cohort->title ?? 'the cohort',
                    'dashboard_url' => route('dashboard'),
                ]));
            } catch (\Exception $e) {}

            // Notify student + admins
            $user->notify(new EnrollmentConfirmed($cohort));
            $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
            Notification::send($admins, new NewEnrollmentAdmin($user, $cohort, $payment->gateway));

            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! You are now enrolled in ' . ($cohort->title ?? 'the cohort') . '.');
        }

        return redirect()->route('dashboard')->with('error', 'Payment already processed or not found.');
    }
}
