<?php

namespace App\Services;

use App\Models\School;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Expense;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get school dashboard statistics
     */
    public function getSchoolDashboardStats($schoolId)
    {
        $school = School::findOrFail($schoolId);

        return [
            'total_students' => $school->students()->active()->count(),
            'total_batches' => $school->batches()->active()->count(),
            'total_teachers' => $school->teachers()->active()->count(),
            'total_classes' => $school->classes()->active()->count(),

            // Fee statistics
            'total_fees' => Fee::where('school_id', $schoolId)->sum('total_amount'),
            'collected_fees' => Fee::where('school_id', $schoolId)->sum('paid_amount'),
            'pending_fees' => Fee::where('school_id', $schoolId)->pending()->sum('total_amount'),
            'monthly_collection' => Fee::where('school_id', $schoolId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('paid_amount'),

            // Expense statistics
            'total_expenses' => Expense::where('school_id', $schoolId)->sum('amount'),
            'monthly_expenses' => Expense::where('school_id', $schoolId)
                ->whereMonth('expense_date', Carbon::now()->month)
                ->sum('amount'),

            // Subscription info
            'subscription_expires_at' => $school->subscription_expires_at,
            'days_until_expiry' => $school->subscription_expires_at
                ? Carbon::now()->diffInDays($school->subscription_expires_at, false)
                : null,
            'is_subscription_active' => $school->isSubscriptionActive(),
        ];
    }

    /**
     * Get monthly income report
     */
    public function getMonthlyIncomeReport($schoolId, $year, $month = null)
    {
        $query = Fee::where('school_id', $schoolId)
            ->whereYear('created_at', $year);

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $fees = $query->get();

        return [
            'total_generated' => $fees->sum('total_amount'),
            'total_collected' => $fees->sum('paid_amount'),
            'total_pending' => $fees->where('status', '!=', 'paid')->sum('total_amount'),
            'total_discount' => $fees->sum('discount'),
            'total_late_fee' => $fees->sum('late_fee'),
        ];
    }

    /**
     * Get monthly expense report by category
     */
    public function getMonthlyExpenseReport($schoolId, $year, $month)
    {
        return Expense::where('school_id', $schoolId)
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();
    }

    /**
     * Get attendance statistics
     */
    public function getAttendanceStats($schoolId, $startDate, $endDate)
    {
        $attendances = Attendance::where('school_id', $schoolId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        $totalRecords = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $lateCount = $attendances->where('status', 'late')->count();

        return [
            'total_records' => $totalRecords,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'present_percentage' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0,
        ];
    }

    /**
     * Get fee collection chart data
     */
    public function getFeeCollectionChartData($schoolId, $year)
    {
        $months = [];
        $collected = [];
        $pending = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create($year, $month)->format('M');
            $months[] = $monthName;

            $monthlyFees = Fee::where('school_id', $schoolId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();

            $collected[] = $monthlyFees->sum('paid_amount');
            $pending[] = $monthlyFees->sum('total_amount') - $monthlyFees->sum('paid_amount');
        }

        return [
            'labels' => $months,
            'collected' => $collected,
            'pending' => $pending,
        ];
    }

    /**
     * Get student enrollment trend
     */
    public function getStudentEnrollmentTrend($schoolId, $year)
    {
        $months = [];
        $enrollments = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create($year, $month)->format('M');
            $months[] = $monthName;

            $count = Student::where('school_id', $schoolId)
                ->whereYear('admission_date', $year)
                ->whereMonth('admission_date', $month)
                ->count();

            $enrollments[] = $count;
        }

        return [
            'labels' => $months,
            'enrollments' => $enrollments,
        ];
    }

    /**
     * Get top fee defaulters
     */
    public function getTopDefaulters($schoolId, $limit = 5)
    {
        return Fee::where('school_id', $schoolId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->with('student.user')
            ->select('student_id', DB::raw('SUM(total_amount + late_fee - discount - paid_amount) as balance'), DB::raw('COUNT(*) as fee_count'))
            ->groupBy('student_id')
            ->orderByDesc('balance')
            ->take($limit)
            ->get();
    }

    /**
     * Get count of overdue fees
     */
    public function getOverdueFeesCount($schoolId)
    {
        return Fee::where('school_id', $schoolId)
            ->where('status', 'overdue')
            ->count();
    }
}
