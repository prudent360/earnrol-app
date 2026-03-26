<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Cohort;
use App\Notifications\CertificateIssued;

class CertificateController extends Controller
{
    public function index(Cohort $cohort)
    {
        $certificates = $cohort->certificates()->with('user')->latest('issued_at')->get();
        $enrollments = $cohort->enrollments()->with('user')->get();
        $certifiedUserIds = $certificates->pluck('user_id')->toArray();

        return view('admin.cohorts.certificates.index', compact('cohort', 'certificates', 'enrollments', 'certifiedUserIds'));
    }

    public function issue(Cohort $cohort)
    {
        abort_if($cohort->status !== 'completed' || !$cohort->certificate_enabled, 403, 'Certificates cannot be issued for this cohort.');

        $certifiedUserIds = $cohort->certificates()->pluck('user_id')->toArray();
        $pendingEnrollments = $cohort->enrollments()
            ->with('user')
            ->whereNotIn('user_id', $certifiedUserIds)
            ->get();

        $count = 0;
        foreach ($pendingEnrollments as $enrollment) {
            $certificate = Certificate::create([
                'user_id' => $enrollment->user_id,
                'cohort_id' => $cohort->id,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'issued_at' => now(),
            ]);

            $enrollment->user->notify(new CertificateIssued($certificate));
            $count++;
        }

        return back()->with('success', "{$count} certificate(s) issued successfully.");
    }
}
