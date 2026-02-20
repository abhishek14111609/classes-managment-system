<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes, MultiTenant;

    protected $fillable = [
        'school_id',
        'name',
        'code',
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
     * Get the classes
     */
    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    /**
     * Scope active courses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
