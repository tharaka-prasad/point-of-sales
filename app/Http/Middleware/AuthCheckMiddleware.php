<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthCheckMiddleware{
    //Allow multiple levels, e.g. ->middleware('authcheck:1,2')
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        $user = Auth::user();

        // If no user logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Current user level (from your 'current_team_id')
        $userLevel = $user->current_team_id;

        // Check if user’s level is in the allowed list
        if (in_array($userLevel, $levels)) {
            return $next($request);
        }

        // If not authorized, redirect to dashboard
        return redirect()->route('dashboard.index');
    }
}
