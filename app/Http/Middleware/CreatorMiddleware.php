<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isCreator()) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'You need to be a creator to access this page.');
    }
}
