<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private SchoolService $schoolService)
    {
    }

    public function index()
    {
        $stats = $this->schoolService->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }

    public function export()
    {
        $stats = $this->schoolService->getDashboardStats();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.dashboard_pdf', compact('stats'));
        return $pdf->download('admin_dashboard_report_' . date('Y-m-d') . '.pdf');
    }
}
