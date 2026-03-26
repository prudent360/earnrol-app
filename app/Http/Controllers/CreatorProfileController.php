<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;

class CreatorProfileController extends Controller
{
    public function show(string $username)
    {
        $creator = User::where('username', $username)
            ->where('is_creator', true)
            ->firstOrFail();

        $products = $creator->digitalProducts()
            ->where('status', 'published')
            ->where('approval_status', 'approved')
            ->latest()
            ->get();

        $cohorts = $creator->createdCohorts()
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->latest()
            ->get();

        $currency = Setting::get('currency', 'GBP');
        $currencySymbol = match ($currency) {
            'USD' => '$', 'EUR' => '€', 'NGN' => '₦', default => '£',
        };

        return view('creator.profile', compact('creator', 'products', 'cohorts', 'currencySymbol'));
    }
}
