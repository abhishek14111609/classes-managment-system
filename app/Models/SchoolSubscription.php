<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_id
 * @property int $plan_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property float $amount_paid
 * @property string|null $payment_method
 * @property string|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School $school
 * @property-read Plan $plan
 */
class SchoolSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'plan_id',
        'invoice_number',
        'invoice_date',
        'start_date',
        'end_date',
        'status',
        'amount_paid',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now()->toDateString();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date < now()->toDateString();
    }

    /**
     * Scope active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Scope expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    /**
     * Generate unique invoice number for school subscription
     */
    public static function generateInvoiceNumber()
    {
        $lastSubscription = self::whereNotNull('invoice_number')
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSubscription ? intval(substr($lastSubscription->invoice_number, -6)) + 1 : 1;

        return 'SCH-INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
