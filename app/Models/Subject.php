<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'class_id',
        'level_id',
        'name',
        'activity_name',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
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
    public function schoolClass()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the sports level
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get all batches for this subject
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Scope active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
