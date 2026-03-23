<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
