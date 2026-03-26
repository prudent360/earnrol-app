<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorEarning;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currencySymbol = Setting::get('currency_symbol', '£');

        $productCount = $user->digitalProducts()->count();
        $cohortCount = $user->createdCohorts()->count();
        $totalEarnings = $user->creatorEarnings()->sum('amount');
        $pendingEarnings = $user->creatorEarnings()->where('status', 'pending')->sum('amount');

        $recentEarnings = $user->creatorEarnings()
            ->with('payment.payable')
            ->latest()
            ->take(10)
            ->get();

        $pendingProducts = $user->digitalProducts()->where('approval_status', 'pending')->count();
        $pendingCohorts = $user->createdCohorts()->where('approval_status', 'pending')->count();

        return view('creator.dashboard.index', compact(
            'productCount', 'cohortCount', 'totalEarnings', 'pendingEarnings',
            'recentEarnings', 'pendingProducts', 'pendingCohorts', 'currencySymbol'
        ));
    }
}
