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
        $batch = $student->batch;

        $canUpload = false;
        $uploadMessage = '';
        $now = Carbon::now();

        if ($batch && $batch->start_time) {
            $batchTime = Carbon::parse($batch->start_time);

            // Just matching time, regardless of date, since it's daily
            $startWindow = $batchTime->copy()->subMinutes(15);
            $endWindow = $batchTime->copy()->addMinutes(15);

            $currentTimeStr = $now->format('H:i:s');
            $startTimeStr = $startWindow->format('H:i:s');
            $endTimeStr = $endWindow->format('H:i:s');

            // check if today's attendance already uploaded/marked
            $attendanceToday = \App\Models\Attendance::where('student_id', $student->id)
                ->where('batch_id', $batch->id)
                ->whereDate('attendance_date', $now->toDateString())
                ->first();

            if ($attendanceToday) {
                if ($attendanceToday->photo_path && $attendanceToday->verification_status === 'pending') {
                    $uploadMessage = 'Your photo is pending verification by the instructor.';
                } else {
                    $uploadMessage = 'Attendance for today is already marked.';
                }
            } else {
                if ($currentTimeStr >= $startTimeStr && $currentTimeStr <= $endTimeStr) {
                    $canUpload = true;
                    $uploadMessage = "Upload window is open until " . $endWindow->format('h:i A');
                } else if ($currentTimeStr < $startTimeStr) {
                    $uploadMessage = "Upload window opens at " . $startWindow->format('h:i A');
                } else {
                    $uploadMessage = "Upload window closed at " . $endWindow->format('h:i A');
                }
            }
        } else {
            $uploadMessage = 'No active batch schedule found.';
        }

        $startDate = $request->input('start_date', $now->copy()->startOfMonth());
        $endDate = $request->input('end_date', $now->copy());

        $report = $this->attendanceService->getStudentAttendanceReport($student, $startDate, $endDate);

        return view('student.attendance-index', [
            'attendances' => $report['attendances'],
            'summary' => $report['summary'],
            'startDate' => Carbon::parse($startDate),
            'endDate' => Carbon::parse($endDate),
            'batch' => $batch,
            'canUpload' => $canUpload,
            'uploadMessage' => $uploadMessage,
            'now' => $now,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $student = auth()->user()->student;
        $batch = $student->batch;

        if (!$batch) {
            return back()->with('error', 'You are not assigned to any batch.');
        }

        $now = Carbon::now();
        $batchTime = Carbon::parse($batch->start_time);
        $startWindow = $batchTime->copy()->subMinutes(15)->format('H:i:s');
        $endWindow = $batchTime->copy()->addMinutes(15)->format('H:i:s');
        $currentTimeStr = $now->format('H:i:s');

        if ($currentTimeStr < $startWindow || $currentTimeStr > $endWindow) {
            return back()->with('error', 'Attendance upload window is currently closed.');
        }

        $attendanceToday = \App\Models\Attendance::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        if ($attendanceToday) {
            return back()->with('error', 'You have already submitted attendance for today.');
        }

        $path = $request->file('photo')->store('attendance_photos', 'public');

        \App\Models\Attendance::create([
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'attendance_date' => $now->toDateString(),
            'status' => 'pending', // Temporary status
            'photo_path' => $path,
            'photo_submitted_at' => $now,
            'verification_status' => 'pending',
        ]);

        return back()->with('success', 'Photo submitted successfully. Awaiting instructor verification.');
    }
}
