<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\Cohort;
use App\Models\CoachingService;
use App\Models\DigitalProduct;
use App\Models\MembershipPlan;
use App\Models\Setting;
use Illuminate\Support\Facades\Cookie;

class AffiliateTrackingController extends Controller
{
    public function track(string $code)
    {
        $link = AffiliateLink::where('code', $code)->first();

        if (!$link) {
            return redirect('/');
        }

        // Increment click counter
        $link->increment('clicks');

        // Set affiliate cookie
        $days = (int) Setting::get('affiliate_cookie_days', 30);
        $cookie = Cookie::make('affiliate_ref', $code, $days * 24 * 60);

        // Redirect to the item's page
        $redirect = $this->resolveRedirect($link);

        return redirect($redirect)->withCookie($cookie);
    }

    private function resolveRedirect(AffiliateLink $link): string
    {
        $item = $link->affiliable;

        if (!$item) {
            return url('/');
        }

        return match ($link->affiliable_type) {
            DigitalProduct::class => route('products.show', $item),
            Cohort::class => route('cohorts.show', $item),
            MembershipPlan::class => route('memberships.show', $item),
            CoachingService::class => route('coaching.show', $item),
            default => url('/'),
        };
    }
}
