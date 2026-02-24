<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $logo
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $subscription_expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'status',
        'institute_type',
        'subscription_expires_at',
    ];

    protected $casts = [
        'subscription_expires_at' => 'datetime',
    ];

    /**
     * Check if school subscription is active
     */
    public function isSubscriptionActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->subscription_expires_at) {
            return false;
        }

        return $this->subscription_expires_at->isFuture();
    }

    /**
     * Get active subscription
     */
    public function activeSubscription()
    {
        return $this->hasOne(SchoolSubscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now());
    }

    /**
     * Get all courses
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(SchoolSubscription::class);
    }

    /**
     * Get all users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all classes
     */
    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    /**
     * Get all batches
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Get all students
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all teachers
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get all fees
     */
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Get all expenses
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the primary school admin user
     */
    public function schoolAdmin()
    {
        return $this->hasOne(User::class)->role('school_admin');
    }

    /**
     * Get all activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
