<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        // Check if user has school
        if ($user && $user->school_id) {
            $school = $user->school;

            // Check if school is active — redirect without logout so the user can contact support
            if ($school->status !== 'active') {
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
