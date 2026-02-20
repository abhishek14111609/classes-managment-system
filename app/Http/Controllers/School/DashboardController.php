<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $stats = $this->reportService->getSchoolDashboardStats($schoolId);
        $feeChart = $this->reportService->getFeeCollectionChartData($schoolId, now()->year);
        $enrollmentTrend = $this->reportService->getStudentEnrollmentTrend($schoolId, now()->year);
        $topDefaulters = $this->reportService->getTopDefaulters($schoolId);
        $overdueCount = $this->reportService->getOverdueFeesCount($schoolId);

        return view('school.dashboard', compact('stats', 'feeChart', 'enrollmentTrend', 'topDefaulters', 'overdueCount'));
    }
}
