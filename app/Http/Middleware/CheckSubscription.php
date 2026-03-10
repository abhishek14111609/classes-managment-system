<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Super admin bypasses subscription check
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user && $user->school_id) {
            $school = $user->school;

            if (!$school) {
                // School record missing — log out and show error
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school account could not be found. Please contact support.');
            }

            // Check if school is active
            if ($school->status !== 'active') {
                // Log out the user so they don't get stuck in a redirect loop
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school account has been deactivated. Please contact your administrator.');
            }

            // Check if subscription is active
            if (!$school->isSubscriptionActive()) {
                return redirect()->route('subscription.expired')
                    ->with('error', 'Your school subscription has expired. Please contact your administrator.');
            }
        }

        return $next($request);
    }
}
