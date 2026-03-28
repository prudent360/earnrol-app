<?php

namespace App\Http\Controllers;

use App\Models\CoachingService;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CoachingController extends Controller
{
    public function index()
    {
        $services = CoachingService::published()
            ->with('creator')
            ->withCount('availableSlots')
            ->latest()
            ->paginate(12);

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('coaching.index', compact('services', 'currencySymbol'));
    }

    public function show(CoachingService $coaching)
    {
        $coaching->load('creator');
        $currencySymbol = Setting::get('currency_symbol', '£');
        $availableSlots = $coaching->availableSlots()->orderBy('start_time')->get();
        $reviews = $coaching->approvedReviews()->with('user')->latest()->get();
        $averageRating = $coaching->averageRating();
        $reviewCount = $reviews->count();

        $userReview = null;
        $hasBooked = false;
        if (Auth::check()) {
            $userReview = $coaching->reviews()->where('user_id', Auth::id())->first();
            $hasBooked = $coaching->bookings()->where('user_id', Auth::id())->exists();
        }

        return view('coaching.show', compact(
            'coaching', 'currencySymbol', 'availableSlots',
            'reviews', 'averageRating', 'reviewCount', 'userReview', 'hasBooked'
        ));
    }

    public function myBookings()
    {
        $bookings = Auth::user()->coachingBookings()
            ->with(['service.creator', 'slot'])
            ->latest()
            ->get();

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('coaching.my-bookings', compact('bookings', 'currencySymbol'));
    }
}
