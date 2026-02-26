<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Classes;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AttendancePhotoReviewTest extends TestCase
{
    use RefreshDatabase;

    private function setupStudentWithBatch(): array
    {
        $school = School::create([
            'name' => 'Test School',
            'email' => 'school@example.com',
            'status' => 'active',
            'subscription_expires_at' => now()->addDays(7),
        ]);

        /** @var User $teacherUser */
        $teacherUser = User::factory()->create([
            'school_id' => $school->id,
        ]);

        $this->actingAs($teacherUser);

        $class = Classes::create([
            'school_id' => $school->id,
            'name' => 'Class A',
            'type' => 'academic',
            'is_active' => true,
        ]);

        $batch = Batch::create([
            'school_id' => $school->id,
            'class_id' => $class->id,
            'name' => 'Batch 1',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'capacity' => 30,
            'is_active' => true,
        ]);

        /** @var User $studentUser */
        $studentUser = User::factory()->create([
            'school_id' => $school->id,
        ]);

        $student = Student::create([
            'school_id' => $school->id,
            'user_id' => $studentUser->id,
            'batch_id' => $batch->id,
            'roll_number' => 'A-001',
            'admission_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        return [$teacherUser, $batch, $student];
    }

    public function test_teacher_approves_pending_photo_sets_review_fields(): void
    {
        [$teacherUser, $batch, $student] = $this->setupStudentWithBatch();

        $attendanceDate = Carbon::today()->toDateString();

        Attendance::create([
            'school_id' => $teacherUser->school_id,
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'attendance_date' => $attendanceDate,
            'status' => 'pending',
            'photo_path' => 'attendance_photos/test.jpg',
            'photo_submitted_at' => Carbon::now(),
            'verification_status' => 'pending',
        ]);

        $service = app(AttendanceService::class);
        $service->markAttendance([
            'batch_id' => $batch->id,
            'attendance_date' => $attendanceDate,
            'attendances' => [
                [
                    'student_id' => $student->id,
                    'status' => 'present',
                ],
            ],
        ]);

        $updated = Attendance::where('student_id', '=', $student->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->first();

        $this->assertNotNull($updated);
        $this->assertSame('approved', $updated->verification_status);
        $this->assertSame('present', $updated->status);
        $this->assertSame($teacherUser->id, $updated->reviewed_by);
        $this->assertNotNull($updated->reviewed_at);
    }

    public function test_teacher_rejects_pending_photo_sets_review_fields(): void
    {
        [$teacherUser, $batch, $student] = $this->setupStudentWithBatch();

        $attendanceDate = Carbon::today()->toDateString();

        Attendance::create([
            'school_id' => $teacherUser->school_id,
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'attendance_date' => $attendanceDate,
            'status' => 'pending',
            'photo_path' => 'attendance_photos/test.jpg',
            'photo_submitted_at' => Carbon::now(),
            'verification_status' => 'pending',
        ]);

        $service = app(AttendanceService::class);
        $service->markAttendance([
            'batch_id' => $batch->id,
            'attendance_date' => $attendanceDate,
            'attendances' => [
                [
                    'student_id' => $student->id,
                    'status' => 'absent',
                ],
            ],
        ]);

        $updated = Attendance::where('student_id', '=', $student->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->first();

        $this->assertNotNull($updated);
        $this->assertSame('rejected', $updated->verification_status);
        $this->assertSame('absent', $updated->status);
        $this->assertSame($teacherUser->id, $updated->reviewed_by);
        $this->assertNotNull($updated->reviewed_at);
    }
}
