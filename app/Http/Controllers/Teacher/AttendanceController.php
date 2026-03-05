<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService)
    {
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;
        $batches = $teacher->batches()
            ->select('batches.*')
            ->with(['class'])
            ->withCount('students')
            ->get();

        // Calculate actual health score for each batch
        $batches->each(function ($batch) {
            $totalAtt = \App\Models\Attendance::where('batch_id', $batch->id)->count();
            $present = \App\Models\Attendance::where('batch_id', $batch->id)->where('status', 'present')->count();
            $batch->health_score = $totalAtt > 0 ? round(($present / $totalAtt) * 100) : 0;
        });

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
        $pendingReviews = null;

        if ($batchId) {
            $selectedBatch = $teacher->batches()->findOrFail($batchId);
            $students = $selectedBatch->students()->active()->with('user')->get();
            $attendanceRecords = Attendance::where('batch_id', '=', $batchId)
                ->whereDate('attendance_date', $attendanceDate)
                ->get();
            $pendingReviews = Attendance::where('batch_id', $batchId)
                ->where('verification_status', '=', 'pending')
                ->orderByDesc('photo_submitted_at')
                ->with('student.user')
                ->get();
        }

        return view('teacher.attendance.create', compact('batches', 'selectedBatch', 'students', 'attendanceRecords', 'pendingReviews'));
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

    public function approvePhoto(Attendance $attendance)
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher->batches()->whereKey($attendance->batch_id)->exists()) {
            abort(403);
        }

        if ($attendance->verification_status !== 'pending') {
            return back()->with('error', 'This photo is already reviewed.');
        }

        $attendance->update([
            'status' => 'present',
            'verification_status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => Carbon::now(),
            'marked_by' => auth()->id(),
        ]);

        return back()->with('success', 'Photo approved successfully.');
    }

    public function rejectPhoto(Attendance $attendance)
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher->batches()->whereKey($attendance->batch_id)->exists()) {
            abort(403);
        }

        if ($attendance->verification_status !== 'pending') {
            return back()->with('error', 'This photo is already reviewed.');
        }

        $attendance->update([
            'status' => 'absent',
            'verification_status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => Carbon::now(),
            'marked_by' => auth()->id(),
        ]);

        return back()->with('success', 'Photo rejected successfully.');
    }
}
