<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $school_id
 * @property int|null $coach_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $event_date
 * @property string|null $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read School $school
 * @property-read Teacher|null $coach
 */
class SportsEvent extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'coach_id',
        'title',
        'description',
        'event_date',
        'location',
        'sport_level',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the coach
     */
    public function coach()
    {
        return $this->belongsTo(Teacher::class, 'coach_id');
    }

    /**
     * Get all participants
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Get all participating students
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'event_participants', 'sports_event_id', 'student_id')
            ->withPivot('participation_status', 'rank', 'notes')
            ->withTimestamps();
    }

    /**
     * Scope upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('event_date', '>=', now());
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
