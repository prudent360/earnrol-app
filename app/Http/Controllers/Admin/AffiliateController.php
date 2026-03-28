<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateSale;
use App\Models\AffiliateLink;
use App\Models\Setting;

class AffiliateController extends Controller
{
    public function index()
    {
        $currencySymbol = Setting::get('currency_symbol', '£');

        $totalSales = AffiliateSale::where('status', 'completed')->count();
        $totalCommissions = AffiliateSale::where('status', 'completed')->sum('affiliate_commission');
        $totalPlatformFees = AffiliateSale::where('status', 'completed')->sum('admin_commission');
        $totalLinks = AffiliateLink::count();

        $recentSales = AffiliateSale::with(['affiliate', 'buyer', 'affiliateLink.affiliable', 'payment'])
            ->latest()
            ->paginate(20);

        return view('admin.affiliates.index', compact(
            'totalSales', 'totalCommissions', 'totalPlatformFees', 'totalLinks',
            'recentSales', 'currencySymbol'
        ));
    }
}
