<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\Payment\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
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
        $reference = 'ENR-' . strtoupper(bin2hex(random_bytes(6)));

        $data = [
            'email' => $user->email,
            'amount' => intval($course->price * 100), // Kobo for Paystack
            'reference' => $reference,
            'callback_url' => route('payments.callback'),
            'metadata' => [
                'course_id' => $course->id,
                'user_id' => $user->id,
            ],
        ];

        $response = $this->paystack->initializeTransaction($data);

        if ($response && isset($response['data']['authorization_url'])) {
            // Create a pending payment record
            Payment::create([
                'user_id' => $user->id,
                'payable_type' => Course::class,
                'payable_id' => $course->id,
                'amount' => $course->price,
                'reference' => $reference,
                'gateway' => 'paystack',
                'status' => 'pending',
            ]);

            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize payment. Please try again or contact support.');
    }

    /**
     * Handle payment callback.
     */
    public function callback(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect()->route('dashboard')->with('error', 'No reference found.');
        }

        $response = $this->paystack->verifyTransaction($reference);

        if ($response && $response['data']['status'] === 'success') {
            $payment = Payment::where('reference', $reference)->first();

            if ($payment && $payment->status !== 'completed') {
                $payment->update([
                    'status' => 'completed',
                    'gateway_response' => $response['data'],
                ]);

                // Create enrollment
                Enrollment::firstOrCreate([
                    'user_id' => $payment->user_id,
                    'course_id' => $payment->payable_id,
                ], [
                    'progress' => 0,
                ]);

                $course = Course::find($payment->payable_id);
                $course->increment('student_count');

                return redirect()->route('courses.show', $course)
                    ->with('success', 'Payment successful! You are now enrolled in ' . $course->title);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }
}
