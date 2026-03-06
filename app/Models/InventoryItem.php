<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'course_id',
        'level_id',
        'name',
        'category',
        'price',
        'stock_quantity',
        'alert_quantity',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'alert_quantity' => 'integer',
        'status' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function sales()
    {
        return $this->hasMany(InventorySale::class, 'item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->alert_quantity;
    }
}
