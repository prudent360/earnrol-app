<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrolledCohorts = CohortEnrollment::where('user_id', $user->id)
            ->with('cohort')
            ->latest('enrolled_at')
            ->get();

        $activeCohort = $enrolledCohorts
            ->filter(fn($e) => $e->cohort && $e->cohort->status === 'active')
            ->first();

        $enrolledCohortIds = $enrolledCohorts->pluck('cohort_id');

        $availableCohorts = Cohort::whereIn('status', ['upcoming', 'active'])
            ->whereNotIn('id', $enrolledCohortIds)
            ->orderBy('start_date')
            ->get();

        $paymentEnabled = (bool) Setting::get('stripe_enabled', false);

        return view('dashboard.index', compact(
            'enrolledCohorts', 'activeCohort', 'availableCohorts', 'paymentEnabled'
        ));
    }
}
