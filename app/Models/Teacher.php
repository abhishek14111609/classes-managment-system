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
 * @property string|null $employee_id
 * @property string|null $qualification
 * @property string|null $specialization
 * @property \Illuminate\Support\Carbon|null $joining_date
 * @property float|null $salary
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read School $school
 * @property-read User $user
 */
class Teacher extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'user_id',
        'employee_id',
        'qualification',
        'specialization',
        'joining_date',
        'salary',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
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
     * Get all batches
     */
    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_teacher');
    }

    /**
     * Get all coached sports events
     */
    public function coachedEvents()
    {
        return $this->hasMany(SportsEvent::class, 'coach_id');
    }

    /**
     * Scope active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
