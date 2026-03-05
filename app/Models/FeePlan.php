<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $school_id
 * @property string $name
 * @property string $fee_type
 * @property string|null $duration
 * @property string|null $sport_level
 * @property float  $amount
 * @property float  $late_fee_per_day
 * @property string|null $description
 * @property bool   $is_active
 */
class FeePlan extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'course_id',
        'batch_id',
        'name',
        'fee_type',
        'duration',
        'sport_level',
        'amount',
        'late_fee_per_day',
        'description',
        'is_active',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee_per_day' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    /** Only active plans */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
