<?php

namespace App\Http\Controllers;

use App\Mail\TemplateMail;
use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\EnrollmentConfirmed;
use App\Notifications\NewEnrollmentAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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
            if (\App\Services\Mail\TemplateService::isEnabled('enroll')) {
                Mail::to($user->email)->send(new TemplateMail('enroll', [
                    'name' => $user->name,
                    'cohort_name' => $cohort->title,
                    'dashboard_url' => route('dashboard'),
                ]));
            }
        } catch (\Exception $e) {}

        // Notify student + admins
        $user->notify(new EnrollmentConfirmed($cohort));
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new NewEnrollmentAdmin($user, $cohort, 'free'));

        return redirect()->route('dashboard')->with('success', 'You are now enrolled in ' . $cohort->title . '!');
    }
}
