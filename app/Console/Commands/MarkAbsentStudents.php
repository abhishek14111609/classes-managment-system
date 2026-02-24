<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class MarkAbsentStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically marks students absent if they missed their batch photo upload window';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Find all active batches
        $batches = Batch::where('is_active', true)->whereNotNull('start_time')->get();

        $markedCount = 0;

        foreach ($batches as $batch) {
            $batchTime = Carbon::parse($batch->start_time);
            $endWindow = $batchTime->copy()->addMinutes(15)->format('H:i:s');

            // Only process if the upload window has strictly closed recently
            // Let's say we check if current time is realistically past the window
            if ($currentTime > $endWindow) {

                // Get all active students in this batch
                $students = $batch->students()->where('is_active', true)->get();

                foreach ($students as $student) {

                    // Check if they already have an attendance record for today (either pending photo or already marked)
                    $attendanceExists = Attendance::where('student_id', $student->id)
                        ->where('batch_id', $batch->id)
                        ->whereDate('attendance_date', $today)
                        ->exists();

                    if (!$attendanceExists) {
                        // Create an absent record automatically
                        Attendance::create([
                            'school_id' => $student->school_id,
                            'student_id' => $student->id,
                            'batch_id' => $batch->id,
                            'attendance_date' => $today,
                            'status' => 'absent',
                            'remarks' => 'Auto-marked absent: Missed live photo window',
                            'verification_status' => 'rejected', // Auto rejected since no photo
                        ]);
                        $markedCount++;
                    }
                }
            }
        }

        $this->info("Successfully auto-marked {$markedCount} students as absent.");
    }
}
