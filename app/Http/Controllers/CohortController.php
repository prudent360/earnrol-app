<?php

namespace App\Http\Controllers;

use App\Mail\TemplateMail;
use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CohortController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = CohortEnrollment::where('user_id', $user->id)
            ->with('cohort')
            ->latest('enrolled_at')
            ->get();

        return view('cohorts.index', compact('enrollments'));
    }

    public function show(Cohort $cohort)
    {
        $user = Auth::user();
        $isEnrolled = $user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists();

        $stripeEnabled  = (bool) Setting::get('stripe_enabled');
        $paypalEnabled  = (bool) Setting::get('paypal_enabled');
        $paymentEnabled = $stripeEnabled || $paypalEnabled;

        return view('cohorts.detail', compact('cohort', 'isEnrolled', 'stripeEnabled', 'paypalEnabled', 'paymentEnabled'));
    }

    public function enrollFree(Cohort $cohort)
    {
        $user = Auth::user();

        if ($user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'You are already enrolled in this cohort.');
        }

        if ($cohort->isFull()) {
            return redirect()->route('dashboard')->with('error', 'This cohort is full.');
        }

        CohortEnrollment::create([
            'user_id' => $user->id,
            'cohort_id' => $cohort->id,
            'enrolled_at' => now(),
        ]);

        // Send enrollment confirmation email
        try {
            Mail::to($user->email)->send(new TemplateMail('enroll', [
                'name' => $user->name,
                'cohort_name' => $cohort->title,
                'dashboard_url' => route('dashboard'),
            ]));
        } catch (\Exception $e) {
            // Don't block enrollment if email fails
        }

        return redirect()->route('dashboard')->with('success', 'You are now enrolled in ' . $cohort->title . '!');
    }
}
