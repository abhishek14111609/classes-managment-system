<?php

namespace App\Services;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Plan;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SchoolService
{
    /**
     * Create a new school with subscription
     */
    public function createSchool(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Upload logo if provided
            $logoPath = null;
            if (isset($data['logo'])) {
                $logoPath = $data['logo']->store('schools/logos', 'public');
            }

            // Get plan
            $plan = Plan::findOrFail($data['plan_id']);

            // Calculate subscription dates
            $startDate = Carbon::now();
            $duration = (int) ($data['subscription_duration'] ?? $plan->duration_days);
            $endDate = $startDate->copy()->addDays($duration);

            // Create school
            $school = School::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'logo' => $logoPath,
                'status' => $data['status'] ?? 'active',
                'institute_type' => $data['institute_type'] ?? 'academic',
                'subscription_expires_at' => $endDate,
            ]);

            // Create subscription
            SchoolSubscription::create([
                'school_id' => $school->id,
                'plan_id' => $plan->id,
                'invoice_number' => SchoolSubscription::generateInvoiceNumber(),
                'invoice_date' => Carbon::now(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'amount_paid' => $plan->price,
                'payment_method' => $data['payment_method'] ?? 'manual',
                'transaction_id' => $data['transaction_id'] ?? null,
            ]);

            // Create school admin user
            $adminUser = User::create([
                'school_id' => $school->id,
                'name' => $data['admin_name'] ?? $data['name'] . ' Admin',
                'email' => $data['admin_email'] ?? $data['email'],
                'username' => $data['admin_username'] ?? $data['admin_email'] ?? $data['email'],
                'password' => Hash::make($data['admin_password'] ?? 'password123'),
                'is_active' => true,
            ]);

            $adminUser->assignRole('school_admin');

            // Log activity
            ActivityLog::logActivity('created', 'school', "Created school: {$school->name}");

            return $school;
        });
    }

    /**
     * Update school
     */
    public function updateSchool(School $school, array $data)
    {
        // Upload new logo if provided
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('schools/logos', 'public');
        }

        $school->update($data);

        ActivityLog::logActivity('updated', 'school', "Updated school: {$school->name}");

        return $school;
    }

    /**
     * Extend subscription
     */
    public function extendSubscription(School $school, int $days, ?Plan $plan = null, float $amount = 0, string $method = 'manual')
    {
        return DB::transaction(function () use ($school, $days, $plan, $amount, $method) {
            $currentSubscription = $school->activeSubscription;

            if ($currentSubscription) {
                $currentSubscription->update(['status' => 'expired']);
            }

            $startDate = $school->subscription_expires_at && $school->subscription_expires_at->isFuture()
                ? $school->subscription_expires_at
                : Carbon::now();

            $endDate = $startDate->copy()->addDays((int) $days);

            // Use existing plan if not provided
            $plan = $plan ?? $currentSubscription->plan;

            // Create new subscription
            $subscription = SchoolSubscription::create([
                'school_id' => $school->id,
                'plan_id' => $plan->id,
                'invoice_number' => SchoolSubscription::generateInvoiceNumber(),
                'invoice_date' => Carbon::now(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'amount_paid' => $amount > 0 ? $amount : $plan->price,
                'payment_method' => $method,
            ]);

            // Update school expiry
            $school->update([
                'subscription_expires_at' => $endDate,
                'status' => 'active',
            ]);

            ActivityLog::logActivity('extended', 'subscription', "Extended subscription for school: {$school->name} by {$days} days");

            return $subscription;
        });
    }

    /**
     * Check and update expired subscriptions
     */
    public function checkExpiredSubscriptions()
    {
        $expiredSchools = School::where('subscription_expires_at', '<', Carbon::now())
            ->where('status', 'active')
            ->get();

        foreach ($expiredSchools as $school) {
            $school->update(['status' => 'inactive']);

            SchoolSubscription::where('school_id', $school->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);
        }

        return $expiredSchools->count();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_schools' => School::count(),
                'active_schools' => School::where('status', 'active')->count(),
                'inactive_schools' => School::where('status', 'inactive')->count(),
                'expired_schools' => School::where('subscription_expires_at', '<', Carbon::now())->count(),
                'expiring_soon_count' => School::where('subscription_expires_at', '>', Carbon::now())
                    ->where('subscription_expires_at', '<=', Carbon::now()->addDays(7))
                    ->count(),
                'plan_count' => Plan::count(),
                'active_subscriptions' => SchoolSubscription::active()->count(),
                'total_revenue' => SchoolSubscription::sum('amount_paid'),
                'monthly_revenue' => SchoolSubscription::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->sum('amount_paid'),
                'users_total' => User::count(),
                'users_active' => User::where('is_active', true)->count(),
                'logs_today' => ActivityLog::whereDate('created_at', Carbon::today())->count(),
                'recent_subscriptions' => SchoolSubscription::with('school', 'plan')->latest()->take(5)->get(),
                'expiring_soon' => School::where('subscription_expires_at', '>', Carbon::now())
                    ->where('subscription_expires_at', '<=', Carbon::now()->addDays(15))
                    ->latest('subscription_expires_at')
                    ->take(5)
                    ->get(),
                'latest_logs' => ActivityLog::with(['user.roles', 'school'])->latest()->take(8)->get(),
                'recent_users' => User::with('roles', 'school')->latest()->take(5)->get(),
            ];
        });
    }
}
