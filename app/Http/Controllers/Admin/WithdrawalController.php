<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalApproved;
use App\Notifications\WithdrawalRejected;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('user')->latest();

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $withdrawals = $query->paginate(20)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This withdrawal is not pending.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        $withdrawal->user->notify(new WithdrawalApproved($withdrawal));

        return back()->with('success', 'Withdrawal approved.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This withdrawal is not pending.');
        }

        $withdrawal->update([
            'status' => 'rejected',
            'admin_note' => $request->get('admin_note', 'Withdrawal rejected by admin.'),
            'processed_at' => now(),
        ]);

        // Refund to wallet
        $withdrawal->user->increment('wallet_balance', $withdrawal->amount);

        $withdrawal->user->notify(new WithdrawalRejected($withdrawal));

        return back()->with('success', 'Withdrawal rejected and amount refunded to user wallet.');
    }
}
