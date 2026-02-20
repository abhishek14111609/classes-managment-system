<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
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
        $batches = Batch::active()->get();
        $batchId = $request->input('batch_id');
        $attendanceDate = $request->input('attendance_date', date('Y-m-d'));

        $students = null;
        $attendanceRecords = null;
        $selectedBatch = null;

        if ($batchId) {
            $selectedBatch = Batch::with(['students.user', 'class'])->findOrFail($batchId);
            $students = $selectedBatch->students()->active()->with('user')->get();
            $attendanceRecords = Attendance::where('batch_id', $batchId)
                ->whereDate('attendance_date', $attendanceDate)
                ->get();
        }

        return view('school.attendance.index', compact('batches', 'selectedBatch', 'students', 'attendanceRecords'));
    }

    public function create()
    {
        $batches = Batch::active()->get();

        return view('school.attendance.index', compact('batches'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        try {
            $this->attendanceService->markAttendance($request->validated());

            return redirect()->route('school.attendance.index')
                ->with('success', 'Attendance marked successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error marking attendance: ' . $e->getMessage());
        }
    }

    public function getBatchStudents(Batch $batch)
    {
        $students = $batch->students()->with('user')->active()->get();

        return response()->json($students);
    }
}
