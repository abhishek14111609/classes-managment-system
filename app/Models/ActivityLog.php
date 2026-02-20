<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $school_id
 * @property int|null $user_id
 * @property string $action
 * @property string $module
 * @property string|null $description
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School|null $school
 * @property-read User|null $user
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'ip_address',
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
     * Log an activity
     */
    public static function logActivity($action, $module, $description = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'school_id' => auth()->user()->school_id ?? null,
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
