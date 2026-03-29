<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!auth()->check() || !$user->isCreator()) {
            return redirect()->route('dashboard')->with('error', 'You need to be a creator to access this page.');
        }

        if (Setting::get('creator_subscription_required', false)) {
            if (!$user->hasActiveCreatorSubscription()) {
                return redirect()->route('creator.plans.index')
                    ->with('warning', 'Please subscribe to a creator plan to continue.');
            }
        }

        return $next($request);
    }
}
