<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sports_event_id
 * @property int $student_id
 * @property string $participation_status
 * @property int|null $rank
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SportsEvent $sportsEvent
 * @property-read Student $student
 */
class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sports_event_id',
        'student_id',
        'participation_status',
        'rank',
        'notes',
    ];

    /**
     * Get the sports event
     */
    public function sportsEvent()
    {
        return $this->belongsTo(SportsEvent::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
