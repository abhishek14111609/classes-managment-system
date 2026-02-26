<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function index()
    {
        return view('school.reports.index');
    }

    public function income(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $year = $request->input('year', now()->year);
        $month = $request->input('month');

        $report = $this->reportService->getMonthlyIncomeReport($schoolId, $year, $month);

        return view('school.reports.income', compact('report', 'year', 'month'));
    }

    public function expenses(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $report = $this->reportService->getMonthlyExpenseReport($schoolId, $year, $month);

        return view('school.reports.expenses', compact('report', 'year', 'month'));
    }

    public function attendance(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        $stats = $this->reportService->getAttendanceStats($schoolId, $startDate, $endDate);

        return view('school.reports.attendance', compact('stats', 'startDate', 'endDate'));
    }

    public function pendingFees()
    {
        $schoolId = auth()->user()->school_id;

        $query = \App\Models\Fee::where('school_id', $schoolId)
            ->whereIn('status', ['pending', 'partial', 'overdue']);

        $totalOutstanding = $query->sum(\DB::raw('total_amount + late_fee - discount - paid_amount'));

        $pendingFees = $query->with('student.user', 'student.batch')
            ->latest()
            ->paginate(15);

        return view('school.reports.pending-fees', compact('pendingFees', 'totalOutstanding'));
    }
}
