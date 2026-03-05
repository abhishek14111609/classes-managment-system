<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    /**
     * Create a new student
     */
    public function createStudent(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Upload photo if provided
            $photoPath = null;
            if (isset($data['photo'])) {
                $photoPath = $data['photo']->store('students/photos', 'public');
            }

            // Create user account
            $user = User::create([
                'school_id' => auth()->user()->school_id,
                'name' => $data['name'],
                'username' => $data['username'] ?? $data['email'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'is_active' => true,
            ]);

            $user->assignRole('student');

            // Generate Roll Number if empty
            $instituteType = auth()->user()->school->institute_type ?? 'academic';
            $schoolName = auth()->user()->school->name ?? 'INS';
            $schoolPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $schoolName), 0, 3));
            $rollNumber = $data['roll_number'] ?? null;
            if (empty($rollNumber)) {
                $prefix = $schoolPrefix . ($instituteType === 'sport' ? '-ATH-' : '-STU-');
                $rollNumber = $prefix . date('y') . strtoupper(\Illuminate\Support\Str::random(4));
            }

            // Create student record
            $student = Student::create([
                'school_id' => auth()->user()->school_id,
                'user_id' => $user->id,
                'course_id' => $data['course_id'] ?? null,
                'batch_id' => $data['batch_id'] ?? null,
                'roll_number' => $rollNumber,
                'birth_date' => $data['birth_date'] ?? null,
                'previous_school' => $data['previous_school'] ?? null,
                'address' => $data['address'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'photo' => $photoPath,
                'admission_date' => $data['admission_date'],
                'is_active' => true,
            ]);

            // Sync batches
            $batchIds = $data['batch_ids'] ?? (isset($data['batch_id']) ? [$data['batch_id']] : []);
            if (!empty($batchIds)) {
                $student->batches()->sync($batchIds);

                // Handle Batch-Specific Fees (Supports multiple fees per sport)
                if (isset($data['batch_fees']) && is_array($data['batch_fees'])) {
                    foreach ($data['batch_fees'] as $batchId => $plans) {
                        if (empty($plans) || !in_array($batchId, $batchIds))
                            continue;

                        $planIds = is_array($plans) ? $plans : [$plans];
                        foreach ($planIds as $planId) {
                            $feePlan = \App\Models\FeePlan::find($planId);
                            if ($feePlan) {
                                \App\Models\Fee::create([
                                    'school_id' => auth()->user()->school_id,
                                    'student_id' => $student->id,
                                    'batch_id' => $batchId,
                                    'fee_plan_id' => $feePlan->id,
                                    'fee_type' => $feePlan->fee_type ?? 'monthly',
                                    'total_amount' => $feePlan->amount,
                                    'paid_amount' => 0,
                                    'due_date' => now()->addDays(7),
                                    'status' => 'pending',
                                    'remarks' => "Session fee for " . (\App\Models\Batch::find($batchId)->name ?? 'selected sport'),
                                ]);
                            }
                        }
                    }
                }
            }

            ActivityLog::logActivity('created', 'student', "Created student: {$user->name}");

            return $student;
        });
    }

    /**
     * Update student
     */
    public function updateStudent(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            // Upload new photo if provided
            if (isset($data['photo'])) {
                $data['photo'] = $data['photo']->store('students/photos', 'public');
            }

            // Update user account
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'] ?? $student->user->username,
                'phone' => $data['phone'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ];

            if (isset($data['password']) && !empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $student->user->update($userData);

            // Update student record
            $studentData = array_filter([
                'batch_id' => $data['batch_id'] ?? $student->batch_id,
                'roll_number' => $data['roll_number'] ?? $student->roll_number,
                'birth_date' => $data['birth_date'] ?? $student->birth_date,
                'previous_school' => $data['previous_school'] ?? $student->previous_school,
                'address' => $data['address'] ?? $student->address,
                'parent_name' => $data['parent_name'] ?? $student->parent_name,
                'parent_phone' => $data['parent_phone'] ?? $student->parent_phone,
                'is_active' => $data['is_active'] ?? $student->is_active,
            ]);

            if (isset($data['photo'])) {
                $studentData['photo'] = $data['photo'];
            }

            $student->update($studentData);

            // Sync batches
            $batchIds = $data['batch_ids'] ?? (isset($data['batch_id']) ? [$data['batch_id']] : []);
            if (!empty($batchIds)) {
                $student->batches()->sync($batchIds);

                // Handle Batch-Specific Fees (Supports multiple fees per sport)
                if (isset($data['batch_fees']) && is_array($data['batch_fees'])) {
                    foreach ($data['batch_fees'] as $batchId => $plans) {
                        if (empty($plans) || !in_array($batchId, $batchIds))
                            continue;

                        $planIds = is_array($plans) ? $plans : [$plans];
                        foreach ($planIds as $planId) {
                            $feePlan = \App\Models\FeePlan::find($planId);
                            if ($feePlan) {
                                // Prevent duplication on edit
                                $exists = \App\Models\Fee::where('student_id', $student->id)
                                    ->where('batch_id', $batchId)
                                    ->where('fee_plan_id', $planId)
                                    ->exists();

                                if (!$exists) {
                                    \App\Models\Fee::create([
                                        'school_id' => auth()->user()->school_id,
                                        'student_id' => $student->id,
                                        'batch_id' => $batchId,
                                        'fee_plan_id' => $feePlan->id,
                                        'fee_type' => $feePlan->fee_type ?? 'monthly',
                                        'total_amount' => $feePlan->amount,
                                        'paid_amount' => 0,
                                        'due_date' => now()->addDays(7),
                                        'status' => 'pending',
                                        'remarks' => "Added via update for " . (\App\Models\Batch::find($batchId)->name ?? 'selected sport'),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            ActivityLog::logActivity('updated', 'student', "Updated student: {$student->user->name}");

            return $student;
        });
    }

    /**
     * Delete student
     */
    public function deleteStudent(Student $student)
    {
        $studentName = $student->user->name;

        DB::transaction(function () use ($student) {
            $student->user->delete();
            $student->delete();
        });

        ActivityLog::logActivity('deleted', 'student', "Deleted student: {$studentName}");

        return true;
    }

    /**
     * Get student dashboard statistics
     */
    public function getStudentStats(Student $student)
    {
        return [
            'attendance_percentage' => $student->getAttendancePercentage(),
            'total_fees' => $student->fees()->sum('total_amount'),
            'paid_fees' => $student->fees()->sum('paid_amount'),
            'pending_fees' => $student->getPendingFees(),
            'events_participated' => $student->eventParticipations()->count(),
        ];
    }

    /**
     * Get student financial ledger
     */
    public function getStudentLedger(Student $student)
    {
        $fees = $student->fees()->orderBy('created_at')->get();
        $payments = \App\Models\FeePayment::whereIn('fee_id', $fees->pluck('id'))->with('fee')->orderBy('paid_at')->get();

        $ledger = [];

        foreach ($fees as $fee) {
            $ledger[] = [
                'date' => $fee->created_at,
                'description' => $fee->fee_type . ' Fee Assigned',
                'type' => 'dr',
                'amount' => $fee->total_amount,
                'reference' => 'FEE-' . $fee->id,
            ];

            if ($fee->late_fee > 0) {
                $ledger[] = [
                    'date' => $fee->updated_at,
                    'description' => 'Late Fee Charged',
                    'type' => 'dr',
                    'amount' => $fee->late_fee,
                    'reference' => 'LATE-' . $fee->id,
                ];
            }

            if ($fee->discount > 0) {
                $ledger[] = [
                    'date' => $fee->updated_at,
                    'description' => 'Discount Applied',
                    'type' => 'cr',
                    'amount' => $fee->discount,
                    'reference' => 'DISC-' . $fee->id,
                ];
            }
        }

        foreach ($payments as $payment) {
            $ledger[] = [
                'date' => $payment->paid_at,
                'description' => 'Payment Received (' . $payment->payment_method . ')',
                'type' => 'cr',
                'amount' => $payment->amount,
                'reference' => 'PAY-' . $payment->id,
            ];
        }

        // Sort by date
        usort($ledger, function ($a, $b) {
            return $a['date'] <=> $b['date'];
        });

        return $ledger;
    }
}
