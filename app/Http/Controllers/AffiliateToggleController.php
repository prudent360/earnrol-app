<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AffiliateToggleController extends Controller
{
    public function become()
    {
        $user = Auth::user();

        if ($user->isAffiliate()) {
            return redirect()->route('affiliate.dashboard')->with('info', 'You are already an affiliate.');
        }

        $user->update([
            'is_affiliate' => true,
            'active_mode' => 'affiliate',
        ]);

        return redirect()->route('affiliate.dashboard')->with('success', 'Welcome! You are now an affiliate. Start promoting products to earn commissions.');
    }
}
