<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\AffiliateSale;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currencySymbol = Setting::get('currency_symbol', '£');

        // Get all affiliate sales for this creator's items
        $productIds = $user->digitalProducts()->pluck('id')->toArray();
        $cohortIds = $user->createdCohorts()->pluck('id')->toArray();
        $membershipIds = $user->membershipPlans()->pluck('id')->toArray();
        $coachingIds = $user->coachingServices()->pluck('id')->toArray();

        $sales = AffiliateSale::with(['affiliate', 'buyer', 'affiliateLink.affiliable', 'payment'])
            ->whereHas('payment', function ($q) use ($productIds, $cohortIds, $membershipIds, $coachingIds) {
                $q->where(function ($q2) use ($productIds) {
                    $q2->where('payable_type', 'App\\Models\\DigitalProduct')->whereIn('payable_id', $productIds);
                })->orWhere(function ($q2) use ($cohortIds) {
                    $q2->where('payable_type', 'App\\Models\\Cohort')->whereIn('payable_id', $cohortIds);
                })->orWhere(function ($q2) use ($membershipIds) {
                    $q2->where('payable_type', 'App\\Models\\MembershipPlan')->whereIn('payable_id', $membershipIds);
                })->orWhere(function ($q2) use ($coachingIds) {
                    $q2->where('payable_type', 'App\\Models\\CoachingService')->whereIn('payable_id', $coachingIds);
                });
            })
            ->latest()
            ->paginate(20);

        $totalAffiliateSales = $sales->total();

        return view('creator.affiliate-sales.index', compact('sales', 'totalAffiliateSales', 'currencySymbol'));
    }
}
