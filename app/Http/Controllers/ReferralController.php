<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $referrals = User::where('referred_by', $user->id)->latest()->get();
        $earnings = $user->referralEarnings()->with('referredUser', 'payment')->latest()->get();
        $withdrawals = $user->withdrawals()->latest()->get();
        $currencySymbol = Setting::get('currency_symbol', '£');
        $minWithdrawal = (float) Setting::get('referral_min_withdrawal', 1000);
        $commissionRate = Setting::get('referral_commission', 10);

        return view('referral.index', compact(
            'user', 'referrals', 'earnings', 'withdrawals',
            'currencySymbol', 'minWithdrawal', 'commissionRate'
        ));
    }

    public function updateBankDetails(Request $request)
    {
        $data = $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_sort_code' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($data);

        return back()->with('success', 'Bank details updated successfully.');
    }

    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();
        $minWithdrawal = (float) Setting::get('referral_min_withdrawal', 1000);

        $request->validate([
            'amount' => 'required|numeric|min:' . $minWithdrawal,
        ]);

        $amount = (float) $request->amount;

        if ($amount > $user->wallet_balance) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        if (!$user->bank_name || !$user->bank_account_name || !$user->bank_account_number) {
            return back()->with('error', 'Please add your bank details first.');
        }

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'pending',
            'bank_name' => $user->bank_name,
            'bank_account_name' => $user->bank_account_name,
            'bank_account_number' => $user->bank_account_number,
            'bank_sort_code' => $user->bank_sort_code,
        ]);

        $user->decrement('wallet_balance', $amount);

        // Notify admins
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new WithdrawalRequested($withdrawal));

        return back()->with('success', 'Withdrawal request submitted. You will be notified once it is processed.');
    }
}
