<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralEarning;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role_id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user's profile.
     */
    public function show(User $user)
    {
        $currencySymbol = Setting::get('currency_symbol', '£');

        $user->loadCount(['payments', 'referrals', 'productPurchases']);

        $enrolledCohorts = $user->enrolledCohorts()->withPivot('enrolled_at')->latest('cohort_enrollments.created_at')->get();
        $payments = $user->payments()->with('payable')->latest()->take(10)->get();
        $purchases = $user->productPurchases()->with('product')->latest()->take(10)->get();
        $referrals = $user->referrals()->select('id', 'name', 'email', 'created_at')->latest()->take(10)->get();
        $earnings = $user->referralEarnings()->latest()->take(10)->get();

        $totalSpent = $user->payments()->where('status', 'completed')->sum('amount');
        $totalEarnings = $user->referralEarnings()->sum('amount');

        return view('admin.users.show', compact(
            'user', 'currencySymbol', 'enrolledCohorts', 'payments',
            'purchases', 'referrals', 'earnings', 'totalSpent', 'totalEarnings'
        ));
    }

    /**
     * Impersonate the specified user.
     */
    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        session()->put('impersonator_id', auth()->id());
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating and return to admin account.
     */
    public function stopImpersonating()
    {
        $adminId = session()->pull('impersonator_id');

        if (!$adminId) {
            return redirect()->route('dashboard');
        }

        Auth::login(User::findOrFail($adminId));

        return redirect()->route('admin.users.index')->with('success', 'Impersonation ended. Welcome back!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->assignRole($request->role_id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Manually credit commission to a user's wallet.
     */
    public function creditWallet(Request $request, User $user)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        $amount = round((float) $request->amount, 2);

        // Create a referral_earnings record for audit trail (payment_id = null = manual)
        ReferralEarning::create([
            'user_id'          => $user->id,
            'referred_user_id' => $user->id, // self-reference for manual credits
            'payment_id'       => null,
            'amount'           => $amount,
            'commission_rate'  => 0, // manual — not percentage-based
            'note'             => $request->note ?? 'Manual credit by admin',
        ]);

        $user->increment('wallet_balance', $amount);

        return back()->with('success', \App\Models\Setting::get('currency_symbol', '£') . number_format($amount, 2) . ' credited to ' . $user->name . "'s wallet.");
    }

    public function ban(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', $user->name . ' has been banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', $user->name . ' has been unbanned.');
    }
}
