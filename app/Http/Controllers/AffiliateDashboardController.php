<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\AffiliateProduct;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currencySymbol = Setting::get('currency_symbol', '£');

        $totalEarnings = $user->affiliateSales()->where('status', 'completed')->sum('affiliate_commission');
        $totalSales = $user->affiliateSales()->where('status', 'completed')->count();
        $totalClicks = $user->affiliateLinks()->sum('clicks');
        $linksCount = $user->affiliateLinks()->count();

        $recentSales = $user->affiliateSales()
            ->with(['affiliateLink.affiliable', 'buyer'])
            ->latest()
            ->take(10)
            ->get();

        return view('affiliate.dashboard', compact(
            'totalEarnings', 'totalSales', 'totalClicks', 'linksCount',
            'recentSales', 'currencySymbol'
        ));
    }

    public function products()
    {
        $currencySymbol = Setting::get('currency_symbol', '£');

        $affiliateProducts = AffiliateProduct::enabled()
            ->with('affiliable')
            ->get()
            ->filter(fn ($ap) => $ap->affiliable !== null);

        return view('affiliate.products', compact('affiliateProducts', 'currencySymbol'));
    }

    public function generateLink(Request $request)
    {
        $request->validate([
            'affiliable_type' => 'required|string',
            'affiliable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Verify the item has affiliate enabled
        $affiliateProduct = AffiliateProduct::where('affiliable_type', $request->affiliable_type)
            ->where('affiliable_id', $request->affiliable_id)
            ->where('affiliate_enabled', true)
            ->first();

        if (!$affiliateProduct) {
            return back()->with('error', 'This item does not have affiliate promotion enabled.');
        }

        // Prevent creators from being affiliates for their own items
        $item = $affiliateProduct->affiliable;
        $creatorId = $item->user_id ?? $item->creator_id ?? null;
        if ($creatorId === $user->id) {
            return back()->with('error', 'You cannot create affiliate links for your own items.');
        }

        $link = AffiliateLink::firstOrCreate([
            'user_id' => $user->id,
            'affiliable_type' => $request->affiliable_type,
            'affiliable_id' => $request->affiliable_id,
        ], [
            'code' => AffiliateLink::generateCode(),
        ]);

        return back()->with('success', 'Affiliate link generated! Your link: ' . $link->url);
    }

    public function myLinks()
    {
        $currencySymbol = Setting::get('currency_symbol', '£');

        $links = Auth::user()->affiliateLinks()
            ->with('affiliable')
            ->withCount('sales')
            ->withSum('sales', 'affiliate_commission')
            ->latest()
            ->get();

        return view('affiliate.links', compact('links', 'currencySymbol'));
    }

    public function earnings()
    {
        $currencySymbol = Setting::get('currency_symbol', '£');

        $sales = Auth::user()->affiliateSales()
            ->with(['affiliateLink.affiliable', 'buyer', 'payment'])
            ->latest()
            ->paginate(20);

        $totalEarnings = Auth::user()->affiliateSales()->where('status', 'completed')->sum('affiliate_commission');

        return view('affiliate.earnings', compact('sales', 'totalEarnings', 'currencySymbol'));
    }
}
