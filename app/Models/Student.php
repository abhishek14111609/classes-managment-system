<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property int|null $batch_id
 * @property string|null $roll_number
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $previous_school
 * @property string|null $address
 * @property string|null $parent_name
 * @property string|null $parent_phone
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $admission_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read School $school
 * @property-read User $user
 * @property-read Batch|null $batch
 */
class Student extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'user_id',
        'course_id',
        'batch_id',
        'roll_number',
        'birth_date',
        'previous_school',
        'address',
        'parent_name',
        'parent_phone',
        'photo',
        'admission_date',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'admission_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the batch
     */
    /**
     * Get the primary batch (Backward compatibility)
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get all batches the student is enrolled in
     */
    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_student')
            ->withPivot('enrollment_date', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all fees
     */
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Get all attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all event participations
     */
    public function eventParticipations()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Get all sports events the student participates in
     */
    public function events()
    {
        return $this->belongsToMany(SportsEvent::class, 'event_participants', 'student_id', 'sports_event_id')
            ->withPivot('participation_status', 'rank', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Calculate attendance percentage
     */
    public function getAttendancePercentage()
    {
        $totalDays = $this->attendances()->count();
        if ($totalDays === 0) {
            return 0;
        }

        $presentDays = $this->attendances()->where('status', 'present')->count();
        return round(($presentDays / $totalDays) * 100, 2);
    }

    /**
     * Get pending fees
     */
    public function getPendingFees()
    {
        return $this->fees()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->sum('total_amount');
    }

    /**
     * Scope active students
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
