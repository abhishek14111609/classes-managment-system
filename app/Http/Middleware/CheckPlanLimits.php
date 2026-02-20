<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limitType): Response
    {
        $user = $request->user();

        // Super admin bypasses plan limits
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user && $user->school_id) {
            $school = $user->school;
            $subscription = $school->activeSubscription;

            if (!$subscription) {
                return redirect()->back()
                    ->with('error', 'No active subscription found.');
            }

            $plan = $subscription->plan;

            // Check student limit
            if ($limitType === 'students') {
                $currentCount = $school->students()->where('is_active', true)->count();

                if ($currentCount >= $plan->student_limit) {
                    return redirect()->back()
                        ->with('error', "Student limit reached. Your plan allows maximum {$plan->student_limit} students. Please upgrade your plan.");
                }
            }

            // Check batch limit
            if ($limitType === 'batches') {
                $currentCount = $school->batches()->where('is_active', true)->count();

                if ($currentCount >= $plan->batch_limit) {
                    return redirect()->back()
                        ->with('error', "Batch limit reached. Your plan allows maximum {$plan->batch_limit} batches. Please upgrade your plan.");
                }
            }
        }

        return $next($request);
    }
}
