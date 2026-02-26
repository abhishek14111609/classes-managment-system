<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property int $batch_id
 * @property \Illuminate\Support\Carbon $attendance_date
 * @property string $status
 * @property string|null $remarks
 * @property int|null $marked_by
 * @property string|null $photo_path
 * @property \Illuminate\Support\Carbon|null $photo_submitted_at
 * @property string|null $verification_status
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School $school
 * @property-read Student $student
 * @property-read Batch $batch
 */
class Attendance extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'batch_id',
        'attendance_date',
        'status',
        'remarks',
        'marked_by',
        'photo_path',
        'photo_submitted_at',
        'verification_status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'photo_submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the user who marked attendance
     */
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Scope by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
