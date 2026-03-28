<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $period = $request->query('period', '30');
        $startDate = now()->subDays((int) $period)->startOfDay();
        $currencySymbol = Setting::get('currency_symbol', '£');

        // Customer data
        $customerData = $this->customerData($user, $startDate);

        // Creator data (if user is a creator)
        $creatorData = $user->isCreator() ? $this->creatorData($user, $startDate) : null;

        return view('report.index', array_merge($customerData, [
            'creatorData' => $creatorData,
            'currencySymbol' => $currencySymbol,
            'period' => $period,
            'user' => $user,
        ]));
    }

    private function customerData($user, Carbon $startDate): array
    {
        $payments = $user->payments()->where('status', 'completed');

        return [
            'totalSpent' => (clone $payments)->sum('amount'),
            'periodSpent' => (clone $payments)->where('created_at', '>=', $startDate)->sum('amount'),
            'totalEnrollments' => $user->cohortEnrollments()->count(),
            'periodEnrollments' => $user->cohortEnrollments()->where('created_at', '>=', $startDate)->count(),
            'totalPurchases' => $user->productPurchases()->count(),
            'periodPurchases' => $user->productPurchases()->where('created_at', '>=', $startDate)->count(),
            'totalReferrals' => $user->referrals()->count(),
            'referralEarningsTotal' => $user->referralEarnings()->sum('amount'),

            // Spending chart
            'spendingChart' => $user->payments()
                ->where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),

            // Recent payments
            'recentPayments' => $user->payments()
                ->with('payable')
                ->latest()
                ->take(10)
                ->get(),
        ];
    }

    private function creatorData($user, Carbon $startDate): array
    {
        $creatorPayments = Payment::where('status', 'completed')
            ->where(function ($q) use ($user) {
                $q->where(function ($q2) use ($user) {
                    $q2->where('payable_type', 'App\\Models\\Cohort')
                        ->whereIn('payable_id', $user->createdCohorts()->pluck('id'));
                })->orWhere(function ($q2) use ($user) {
                    $q2->where('payable_type', 'App\\Models\\DigitalProduct')
                        ->whereIn('payable_id', $user->digitalProducts()->pluck('id'));
                });
            });

        return [
            'totalRevenue' => (clone $creatorPayments)->sum('amount'),
            'periodRevenue' => (clone $creatorPayments)->where('created_at', '>=', $startDate)->sum('amount'),
            'totalStudents' => $user->createdCohorts()->withCount('enrollments')->get()->sum('enrollments_count'),
            'totalProductSales' => $user->digitalProducts()->withCount('purchases')->get()->sum('purchases_count'),

            // Revenue chart
            'revenueChart' => (clone $creatorPayments)
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),
        ];
    }
}
