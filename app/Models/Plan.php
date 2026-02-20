<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $student_limit
 * @property int $batch_limit
 * @property int $duration_days
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'student_limit',
        'batch_limit',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'student_limit' => 'integer',
        'batch_limit' => 'integer',
        'duration_days' => 'integer',
    ];

    /**
     * Get all subscriptions using this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(SchoolSubscription::class);
    }

    /**
     * Scope active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
