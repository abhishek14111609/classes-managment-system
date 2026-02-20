<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private AttendanceService $attendanceService
    ) {}

    public function index()
    {
        $student = auth()->user()->student;
        $stats = $this->studentService->getStudentStats($student);
        $recentAttendance = $student->attendances()
            ->latest('attendance_date')
            ->take(5)
            ->get();
        $ledger = $this->studentService->getStudentLedger($student);

        $balance = $stats['pending_fees'];
        $paidFees = $stats['paid_fees'];
        $attendanceRate = $stats['attendance_percentage'];
        $presentDays = $student->attendances()->where('status', 'present')->count();

        return view('student.dashboard', compact(
            'student',
            'stats',
            'balance',
            'paidFees',
            'attendanceRate',
            'presentDays',
            'recentAttendance',
            'ledger'
        ));
    }
}
