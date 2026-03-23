<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Setting;
use App\Notifications\EnrolledInCourse;
use App\Services\Payment\PaystackService;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaystackService $paystack;
    protected StripeService $stripe;

    public function __construct(PaystackService $paystack, StripeService $stripe)
    {
        $this->paystack = $paystack;
        $this->stripe = $stripe;
    }

    /**
     * Initialize payment for a course.
     */
    public function initialize(Course $course)
    {
        if ($course->is_free || $course->price <= 0) {
            return redirect()->route('courses.enroll', $course);
        }

        $user = Auth::user();
        
        // Determine preferred gateway (standard UK is Stripe)
        $gateway = Setting::get('stripe_enabled') === '1' ? 'stripe' : (Setting::get('paystack_enabled') === '1' ? 'paystack' : null);

        if (!$gateway) {
            return back()->with('error', 'No payment gateway is currently enabled. Please contact support.');
        }

        if ($gateway === 'stripe') {
            $session = $this->stripe->createCheckoutSession($course, $user);
            if ($session) {
                // Record pending payment
                Payment::create([
                    'user_id' => $user->id,
                    'payable_type' => Course::class,
                    'payable_id' => $course->id,
                    'amount' => $course->price,
                    'reference' => $session->id,
                    'gateway' => 'stripe',
                    'status' => 'pending',
                    'currency' => Setting::get('currency', 'GBP'),
                ]);
                return redirect($session->url);
            }
        } else {
            // Paystack logic
            $reference = 'ENR-' . strtoupper(bin2hex(random_bytes(6)));
            $data = [
                'email' => $user->email,
                'amount' => intval($course->price * 100),
                'reference' => $reference,
                'callback_url' => route('payments.callback') . '?gateway=paystack',
                'metadata' => [
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                ],
            ];

            $response = $this->paystack->initializeTransaction($data);

            if ($response && isset($response['data']['authorization_url'])) {
                Payment::create([
                    'user_id' => $user->id,
                    'payable_type' => Course::class,
                    'payable_id' => $course->id,
                    'amount' => $course->price,
                    'reference' => $reference,
                    'gateway' => 'paystack',
                    'status' => 'pending',
                    'currency' => Setting::get('currency', 'GBP'),
                ]);
                return redirect($response['data']['authorization_url']);
            }
        }

        return back()->with('error', 'Unable to initialize payment. Please try again or contact support.');
    }

    /**
     * Handle payment callback.
     */
    public function callback(Request $request)
    {
        $gateway = $request->get('gateway', 'stripe');
        
        if ($gateway === 'stripe') {
            $sessionId = $request->get('session_id');
            if (!$sessionId) return redirect()->route('dashboard')->with('error', 'Missing session ID.');
            
            $session = $this->stripe->verifySession($sessionId);
            if ($session) {
                $payment = Payment::where('reference', $sessionId)->first();
                return $this->finalizePayment($payment, $session->toArray());
            }
        } else {
            $reference = $request->get('reference');
            if (!$reference) return redirect()->route('dashboard')->with('error', 'No reference found.');

            $response = $this->paystack->verifyTransaction($reference);
            if ($response && $response['data']['status'] === 'success') {
                $payment = Payment::where('reference', $reference)->first();
                return $this->finalizePayment($payment, $response['data']);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }

    protected function finalizePayment($payment, $gatewayResponse)
    {
        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);

            // Create enrollment
            Enrollment::firstOrCreate([
                'user_id' => $payment->user_id,
                'course_id' => $payment->payable_id,
            ], [
                'progress' => 0,
            ]);

            $course = Course::find($payment->payable_id);
            if ($course) {
                $course->increment('student_count');
                
                // Send Enrollment Notification (Database + Email)
                $payment->user->notify(new EnrolledInCourse($course));
                
                return redirect()->route('courses.show', $course)
                    ->with('success', 'Payment successful! You are now enrolled in ' . $course->title);
            }
        }
        
        return redirect()->route('dashboard')->with('error', 'Payment already processed or not found.');
    }
}
