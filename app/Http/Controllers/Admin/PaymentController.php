<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TemplateMail;
use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewEnrollmentAdmin;
use App\Notifications\PaymentApproved;
use App\Notifications\PaymentRejected;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'payable'])
            ->latest();

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->get('gateway')) {
            $query->where('gateway', $request->get('gateway'));
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment is not pending.');
        }

        $payment->update(['status' => 'completed']);

        // Create enrollment
        CohortEnrollment::firstOrCreate([
            'user_id'   => $payment->user_id,
            'cohort_id' => $payment->payable_id,
        ], [
            'payment_id'  => $payment->id,
            'enrolled_at' => now(),
        ]);

        // Send enrollment email
        $cohort = Cohort::find($payment->payable_id);
        $user = $payment->user;

        try {
            Mail::to($user->email)->send(new TemplateMail('enroll', [
                'name'          => $user->name,
                'cohort_name'   => $cohort->title ?? 'the cohort',
                'dashboard_url' => route('dashboard'),
            ]));
        } catch (\Exception $e) {
            // Don't block approval if email fails
        }

        // Credit referral commission if eligible
        ReferralService::creditCommissionIfEligible($payment);

        // Notify student + admins
        $user->notify(new PaymentApproved($payment));
        $user->notify(new EnrollmentConfirmed($cohort));
        $admins = User::whereIn('role', ['admin', 'superadmin'])->where('id', '!=', auth()->id())->get();
        Notification::send($admins, new NewEnrollmentAdmin($user, $cohort, $payment->gateway));

        return back()->with('success', $user->name . ' has been enrolled in ' . ($cohort->title ?? 'the cohort') . '.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment is not pending.');
        }

        $payment->update([
            'status'     => 'failed',
            'admin_note' => $request->get('admin_note', 'Payment rejected by admin.'),
        ]);

        // Notify student
        $payment->user->notify(new PaymentRejected($payment));

        return back()->with('success', 'Payment rejected.');
    }
}
