<?php

namespace App\Http\Controllers;

use App\Models\CoachingBooking;
use App\Models\CoachingService;
use App\Models\CoachingSlot;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\CoachingBookingConfirmed;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CoachingPaymentController extends Controller
{
    public function stripeCheckout(Request $request, CoachingService $coaching)
    {
        $request->validate(['slot_id' => 'required|integer']);

        $user = Auth::user();
        $slot = CoachingSlot::findOrFail($request->slot_id);

        $check = $this->preCheck($coaching, $slot, $user);
        if ($check) return $check;

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
                            'name' => $coaching->title . ' — ' . $slot->start_time->format('M d, Y g:i A'),
                            'description' => $coaching->duration_minutes . ' min session',
                        ],
                        'unit_amount' => intval($coaching->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('coaching.stripe.callback') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('coaching.show', $coaching),
                'customer_email' => $user->email,
                'metadata' => [
                    'coaching_service_id' => $coaching->id,
                    'coaching_slot_id' => $slot->id,
                    'user_id' => $user->id,
                ],
            ]);

            // Lock the slot
            $slot->update(['is_booked' => true]);

            Payment::create([
                'user_id' => $user->id,
                'payable_type' => CoachingService::class,
                'payable_id' => $coaching->id,
                'amount' => $coaching->price,
                'reference' => $session->id,
                'gateway' => 'stripe',
                'status' => 'pending',
                'currency' => $currency,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Coaching Stripe Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }
    }

    public function stripeCallback(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('coaching.index')->with('error', 'Missing session ID.');
        }

        try {
            Stripe::setApiKey(Setting::get('stripe_secret_key', config('services.stripe.secret')));
            $session = Session::retrieve($sessionId);

            if ($session && $session->payment_status === 'paid') {
                $payment = Payment::where('reference', $sessionId)->first();
                $slotId = $session->metadata->coaching_slot_id ?? null;
                return $this->finalizeBooking($payment, $session->toArray(), $slotId);
            }
        } catch (\Exception $e) {
            Log::error('Coaching Stripe Callback Error', ['message' => $e->getMessage()]);
        }

        return redirect()->route('coaching.index')->with('error', 'Payment verification failed.');
    }

    public function bankTransferForm(Request $request, CoachingService $coaching)
    {
        $request->validate(['slot_id' => 'required|integer']);

        $user = Auth::user();
        $slot = CoachingSlot::findOrFail($request->slot_id);

        $check = $this->preCheck($coaching, $slot, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $bankDetails = [
            'bank_name'       => Setting::get('bank_name', ''),
            'account_name'    => Setting::get('bank_account_name', ''),
            'sort_code'       => Setting::get('bank_sort_code', ''),
            'account_number'  => Setting::get('bank_account_number', ''),
            'iban'            => Setting::get('bank_iban', ''),
            'reference_note'  => Setting::get('bank_reference_note', ''),
            'currency_symbol' => Setting::get('currency_symbol', '£'),
        ];

        return view('coaching.bank-transfer', compact('coaching', 'slot', 'bankDetails'));
    }

    public function bankTransferSubmit(Request $request, CoachingService $coaching)
    {
        $request->validate([
            'slot_id' => 'required|integer',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes'   => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $slot = CoachingSlot::findOrFail($request->slot_id);

        $check = $this->preCheck($coaching, $slot, $user);
        if ($check) return $check;

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        // Lock the slot
        $slot->update(['is_booked' => true]);

        $payment = Payment::create([
            'user_id'      => $user->id,
            'payable_type' => CoachingService::class,
            'payable_id'   => $coaching->id,
            'amount'       => $coaching->price,
            'reference'    => 'BT-' . strtoupper(uniqid()),
            'gateway'      => 'bank_transfer',
            'status'       => 'pending',
            'currency'     => Setting::get('currency', 'GBP'),
            'receipt_path' => $receiptPath,
        ]);

        // Create a pending booking
        CoachingBooking::create([
            'user_id' => $user->id,
            'coaching_service_id' => $coaching->id,
            'coaching_slot_id' => $slot->id,
            'payment_id' => $payment->id,
            'status' => 'confirmed',
            'notes' => $request->notes,
        ]);

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new \App\Notifications\NewBankTransferAdmin($payment));

        return redirect()->route('coaching.show', $coaching)
            ->with('success', 'Receipt uploaded! Your booking is being reviewed.');
    }

    protected function preCheck(CoachingService $coaching, CoachingSlot $slot, $user)
    {
        if ($slot->coaching_service_id !== $coaching->id) {
            return redirect()->route('coaching.show', $coaching)->with('error', 'Invalid slot.');
        }

        if ($slot->is_booked) {
            return redirect()->route('coaching.show', $coaching)->with('error', 'This slot is no longer available.');
        }

        if ($coaching->status !== 'published' || $coaching->approval_status !== 'approved') {
            return redirect()->route('coaching.index')->with('error', 'This coaching service is not available.');
        }

        return null;
    }

    protected function finalizeBooking($payment, $gatewayResponse, $slotId)
    {
        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);

            $coaching = CoachingService::find($payment->payable_id);
            $slot = CoachingSlot::find($slotId);

            if ($slot) {
                $slot->update(['is_booked' => true]);
            }

            CoachingBooking::create([
                'user_id' => $payment->user_id,
                'coaching_service_id' => $payment->payable_id,
                'coaching_slot_id' => $slotId,
                'payment_id' => $payment->id,
                'status' => 'confirmed',
            ]);

            $user = User::find($payment->user_id);
            $user->notify(new CoachingBookingConfirmed($coaching, $slot));

            ReferralService::creditCommissionIfEligible($payment);
            \App\Services\CreatorEarningService::creditCreatorIfEligible($payment);

            return redirect()->route('coaching.my-bookings')
                ->with('success', 'Booking confirmed! You will receive the meeting link from the creator.');
        }

        return redirect()->route('coaching.index')->with('error', 'Payment already processed or not found.');
    }
}
