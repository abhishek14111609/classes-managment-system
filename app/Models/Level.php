<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenant;

class Level extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
