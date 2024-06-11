<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class Admin
 * 
 * This class represents the middleware for checking if the user is an admin.
 */
class Admin
{
    /**
     * Handle an incoming request.
     *
     * Check if the user is an admin. If not, abort with a 403 status code.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role_int < 1) {
            abort(403);
        }

        return $next($request);
    }
}
