<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService)
    {
    }

    public function index(Request $request)
    {
        $student = auth()->user()->student;
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        $report = $this->attendanceService->getStudentAttendanceReport($student, $startDate, $endDate);

        return view('student.attendance-index', [
            'attendances' => $report['attendances'],
            'summary' => $report['summary'],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
