<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Http\Requests\StoreAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService)
    {
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;
        $batches = $teacher->batches()->select('batches.*')->with('students.user')->get();

        return view('teacher.attendance.index', compact('batches'));
    }

    public function create(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $batches = $teacher->batches()->select('batches.*')->get();
        $batchId = $request->input('batch_id');
        $attendanceDate = $request->input('attendance_date', date('Y-m-d'));

        $students = null;
        $attendanceRecords = null;
        $selectedBatch = null;

        if ($batchId) {
            $selectedBatch = $teacher->batches()->findOrFail($batchId);
            $students = $selectedBatch->students()->active()->with('user')->get();
            $attendanceRecords = \App\Models\Attendance::where('batch_id', $batchId)
                ->whereDate('attendance_date', $attendanceDate)
                ->get();
        }

        return view('teacher.attendance.create', compact('batches', 'selectedBatch', 'students', 'attendanceRecords'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        try {
            $this->attendanceService->markAttendance($request->validated());

            return redirect()->route('teacher.attendance.index')
                ->with('success', 'Attendance marked successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error marking attendance: ' . $e->getMessage());
        }
    }
}
