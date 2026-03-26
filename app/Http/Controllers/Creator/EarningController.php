<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currencySymbol = Setting::get('currency_symbol', '£');

        $totalEarnings = $user->creatorEarnings()->sum('amount');
        $pendingEarnings = $user->creatorEarnings()->where('status', 'pending')->sum('amount');
        $paidEarnings = $user->creatorEarnings()->where('status', 'paid')->sum('amount');
        $walletBalance = $user->wallet_balance;

        $earnings = $user->creatorEarnings()
            ->with('payment.payable')
            ->latest()
            ->paginate(20);

        return view('creator.earnings.index', compact(
            'earnings', 'totalEarnings', 'pendingEarnings', 'paidEarnings',
            'walletBalance', 'currencySymbol'
        ));
    }
}
