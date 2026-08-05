<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request and check if authenticated user has required role.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this portal.');
        }

        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized access to this portal.');
        }

        return $next($request);
    }
}
