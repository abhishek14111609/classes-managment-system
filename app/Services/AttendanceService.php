<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Batch;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Mark attendance for batch
     */
    public function markAttendance(array $data)
    {
        return DB::transaction(function () use ($data) {
            $batch = Batch::findOrFail($data['batch_id']);
            $attendanceDate = $data['attendance_date'];

            foreach ($data['attendances'] as $attendanceData) {
                // Check if this is a photo verification (existing pending record)
                $existing = Attendance::where('student_id', '=', $attendanceData['student_id'])
                    ->whereDate('attendance_date', $attendanceDate)
                    ->first();

                $verificationStatus = 'approved';
                $shouldReview = $existing && $existing->verification_status === 'pending';

                if ($shouldReview && $attendanceData['status'] === 'absent') {
                    $verificationStatus = 'rejected';
                }

                $updateData = [
                    'school_id' => auth()->user()->school_id,
                    'batch_id' => $batch->id,
                    'status' => $attendanceData['status'],
                    'remarks' => $attendanceData['remarks'] ?? null,
                    'marked_by' => auth()->id(),
                    'verification_status' => $verificationStatus,
                ];

                if ($shouldReview) {
                    $updateData['reviewed_by'] = auth()->id();
                    $updateData['reviewed_at'] = Carbon::now();
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'attendance_date' => $attendanceDate,
                    ],
                    $updateData
                );
            }

            ActivityLog::logActivity('marked', 'attendance', "Marked attendance for batch: {$batch->name} on {$attendanceDate}");

            return true;
        });
    }

    /**
     * Get attendance report for batch
     */
    public function getBatchAttendanceReport(Batch $batch, $startDate, $endDate)
    {
        $students = $batch->students()->active()->get();
        $attendanceData = [];

        foreach ($students as $student) {
            $attendances = Attendance::where('student_id', '=', $student->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->get();

            $totalDays = $attendances->count();
            $presentDays = $attendances->where('status', 'present')->count();
            $absentDays = $attendances->where('status', 'absent')->count();
            $lateDays = $attendances->where('status', 'late')->count();

            $attendanceData[] = [
                'student' => $student,
                'total_days' => $totalDays,
                'present' => $presentDays,
                'absent' => $absentDays,
                'late' => $lateDays,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0,
            ];
        }

        return $attendanceData;
    }

    /**
     * Get attendance report for student
     */
    public function getStudentAttendanceReport(Student $student, $startDate = null, $endDate = null)
    {
        $query = Attendance::where('student_id', '=', $student->id);

        if ($startDate && $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $excusedDays = $attendances->where('status', 'excused')->count();

        return [
            'attendances' => $attendances,
            'summary' => [
                'total_days' => $totalDays,
                'present' => $presentDays,
                'absent' => $absentDays,
                'late' => $lateDays,
                'excused' => $excusedDays,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get today's attendance for batch
     */
    public function getTodayAttendance(Batch $batch)
    {
        $today = Carbon::today();

        return Attendance::where('batch_id', '=', $batch->id)
            ->whereDate('attendance_date', $today)
            ->with('student.user')
            ->get();
    }
}
