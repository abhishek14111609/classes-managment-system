<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $school_id
 * @property int $class_id
 * @property string $name
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int|null $capacity
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read School $school
 * @property-read Classes $class
 */
class Batch extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'class_id',
        'subject_id',
        'name',
        'start_time',
        'end_time',
        'capacity',
        'sport_level',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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
     * Get the class
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get all students
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all teachers
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'batch_teacher');
    }

    /**
     * Get all attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get current student count
     */
    public function getCurrentStudentCount()
    {
        return $this->students()->where('is_active', true)->count();
    }

    /**
     * Check if batch has capacity
     */
    public function hasCapacity(): bool
    {
        if (is_null($this->capacity)) {
            return true; // No limit set — always has capacity
        }
        return $this->getCurrentStudentCount() < $this->capacity;
    }

    /**
     * Scope active batches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
