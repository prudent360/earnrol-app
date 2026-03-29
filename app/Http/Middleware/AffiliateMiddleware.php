<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AffiliateMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isAffiliate()) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'You need to be an affiliate to access this page.');
    }
}
