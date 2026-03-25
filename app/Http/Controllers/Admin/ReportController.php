<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CohortEnrollment;
use App\Models\DigitalProduct;
use App\Models\Payment;
use App\Models\ProductPurchase;
use App\Models\ReferralEarning;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Cohort;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'overview');
        $period = $request->query('period', '30');
        $startDate = now()->subDays((int) $period)->startOfDay();

        $data = match ($tab) {
            'revenue'   => $this->revenueData($startDate, $period),
            'users'     => $this->usersData($startDate, $period),
            'cohorts'   => $this->cohortsData($startDate, $period),
            'products'  => $this->productsData($startDate, $period),
            'referrals' => $this->referralsData($startDate, $period),
            default     => $this->overviewData($startDate, $period),
        };

        return view('admin.reports.index', array_merge($data, [
            'tab'    => $tab,
            'period' => $period,
        ]));
    }

    private function overviewData(Carbon $startDate, string $period): array
    {
        $completedPayments = Payment::where('status', 'completed');

        return [
            'totalUsers'         => User::count(),
            'newUsers'           => User::where('created_at', '>=', $startDate)->count(),
            'totalRevenue'       => (clone $completedPayments)->sum('amount'),
            'periodRevenue'      => (clone $completedPayments)->where('created_at', '>=', $startDate)->sum('amount'),
            'totalEnrollments'   => CohortEnrollment::count(),
            'periodEnrollments'  => CohortEnrollment::where('created_at', '>=', $startDate)->count(),
            'totalProductSales'  => ProductPurchase::count(),
            'periodProductSales' => ProductPurchase::where('created_at', '>=', $startDate)->count(),

            // Revenue chart data (daily for selected period)
            'revenueChart' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),

            // Signups chart
            'signupsChart' => User::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),

            // Recent activity
            'recentPayments' => Payment::with('user')
                ->where('status', 'completed')
                ->latest()
                ->take(5)
                ->get(),

            'recentSignups' => User::latest()->take(5)->get(),
        ];
    }

    private function revenueData(Carbon $startDate, string $period): array
    {
        $completed = Payment::where('status', 'completed');

        return [
            'totalRevenue'     => (clone $completed)->sum('amount'),
            'periodRevenue'    => (clone $completed)->where('created_at', '>=', $startDate)->sum('amount'),
            'pendingRevenue'   => Payment::where('status', 'pending')->sum('amount'),

            // By gateway
            'byGateway' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->select('gateway', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('gateway')
                ->get(),

            // By source (cohort vs product)
            'cohortRevenue'  => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->where('payable_type', 'App\\Models\\Cohort')
                ->sum('amount'),
            'productRevenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->where('payable_type', 'App\\Models\\DigitalProduct')
                ->sum('amount'),

            // Daily revenue chart
            'revenueChart' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),

            // Recent payments
            'recentPayments' => Payment::with('user')
                ->latest()
                ->take(10)
                ->get(),

            // Payment status breakdown
            'statusBreakdown' => Payment::where('created_at', '>=', $startDate)
                ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('status')
                ->get(),
        ];
    }

    private function usersData(Carbon $startDate, string $period): array
    {
        return [
            'totalUsers'      => User::count(),
            'newUsers'        => User::where('created_at', '>=', $startDate)->count(),
            'verifiedUsers'   => User::whereNotNull('email_verified_at')->count(),
            'unverifiedUsers' => User::whereNull('email_verified_at')->count(),
            'adminUsers'      => User::where('role', 'admin')->count(),

            // Signups chart
            'signupsChart' => User::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),

            // Users with most enrollments
            'topEnrollers' => User::withCount('cohortEnrollments')
                ->orderByDesc('cohort_enrollments_count')
                ->take(10)
                ->get(),

            // Users with most purchases
            'topBuyers' => User::withCount('productPurchases')
                ->orderByDesc('product_purchases_count')
                ->take(10)
                ->get(),

            // Recent signups
            'recentSignups' => User::latest()->take(10)->get(),
        ];
    }

    private function cohortsData(Carbon $startDate, string $period): array
    {
        return [
            'totalCohorts'      => Cohort::count(),
            'activeCohorts'     => Cohort::where('status', 'active')->count(),
            'totalEnrollments'  => CohortEnrollment::count(),
            'periodEnrollments' => CohortEnrollment::where('created_at', '>=', $startDate)->count(),

            // Most popular cohorts
            'popularCohorts' => Cohort::withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->take(10)
                ->get(),

            // Revenue per cohort
            'cohortRevenue' => Cohort::select('cohorts.*')
                ->leftJoin('payments', function ($join) {
                    $join->on('payments.payable_id', '=', 'cohorts.id')
                         ->where('payments.payable_type', 'App\\Models\\Cohort')
                         ->where('payments.status', 'completed');
                })
                ->selectRaw('COALESCE(SUM(payments.amount), 0) as total_revenue')
                ->selectRaw('COUNT(DISTINCT payments.id) as payment_count')
                ->groupBy('cohorts.id')
                ->orderByDesc('total_revenue')
                ->take(10)
                ->get(),

            // Enrollment chart
            'enrollmentChart' => CohortEnrollment::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),
        ];
    }

    private function productsData(Carbon $startDate, string $period): array
    {
        return [
            'totalProducts'     => DigitalProduct::count(),
            'publishedProducts' => DigitalProduct::where('status', 'published')->count(),
            'totalSales'        => ProductPurchase::count(),
            'periodSales'       => ProductPurchase::where('created_at', '>=', $startDate)->count(),
            'totalDownloads'    => DigitalProduct::sum('download_count'),

            // Top selling products
            'topProducts' => DigitalProduct::withCount('purchases')
                ->orderByDesc('purchases_count')
                ->take(10)
                ->get(),

            // Product revenue
            'productRevenue' => DigitalProduct::select('digital_products.*')
                ->leftJoin('payments', function ($join) {
                    $join->on('payments.payable_id', '=', 'digital_products.id')
                         ->where('payments.payable_type', 'App\\Models\\DigitalProduct')
                         ->where('payments.status', 'completed');
                })
                ->selectRaw('COALESCE(SUM(payments.amount), 0) as total_revenue')
                ->selectRaw('COUNT(DISTINCT payments.id) as payment_count')
                ->groupBy('digital_products.id')
                ->orderByDesc('total_revenue')
                ->take(10)
                ->get(),

            // Sales chart
            'salesChart' => ProductPurchase::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),
        ];
    }

    private function referralsData(Carbon $startDate, string $period): array
    {
        return [
            'totalEarnings'     => ReferralEarning::sum('amount'),
            'periodEarnings'    => ReferralEarning::where('created_at', '>=', $startDate)->sum('amount'),
            'totalReferrals'    => User::whereNotNull('referred_by')->count(),
            'periodReferrals'   => User::whereNotNull('referred_by')->where('created_at', '>=', $startDate)->count(),
            'totalWithdrawals'  => Withdrawal::where('status', 'approved')->sum('amount'),
            'pendingWithdrawals'=> Withdrawal::where('status', 'pending')->sum('amount'),

            // Top referrers
            'topReferrers' => User::withCount('referrals')
                ->having('referrals_count', '>', 0)
                ->orderByDesc('referrals_count')
                ->take(10)
                ->get(),

            // Top earners
            'topEarners' => User::select('users.*')
                ->join('referral_earnings', 'referral_earnings.user_id', '=', 'users.id')
                ->selectRaw('SUM(referral_earnings.amount) as total_earned')
                ->selectRaw('COUNT(referral_earnings.id) as earnings_count')
                ->groupBy('users.id')
                ->orderByDesc('total_earned')
                ->take(10)
                ->get(),

            // Earnings chart
            'earningsChart' => ReferralEarning::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray(),
        ];
    }
}
