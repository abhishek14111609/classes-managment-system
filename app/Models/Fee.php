<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_id
 * @property int|null $fee_plan_id
 * @property int $student_id
 * @property string $fee_type
 * @property string|null $sport_level
 * @property float $total_amount
 * @property float $paid_amount
 * @property float $discount
 * @property float $late_fee
 * @property \Illuminate\Support\Carbon $due_date
 * @property string $status
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School $school
 * @property-read Student $student
 */
class Fee extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'fee_plan_id',
        'student_id',
        'batch_id',
        'fee_type',
        'duration',
        'sport_level',
        'total_amount',
        'paid_amount',
        'discount',
        'late_fee',
        'due_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student
     */
    public function feePlan()
    {
        return $this->belongsTo(FeePlan::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the specific batch this fee is for
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get all payments
     */
    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * Get invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get remaining amount
     */
    public function getRemainingAmount()
    {
        return $this->total_amount + $this->late_fee - $this->discount - $this->paid_amount;
    }

    /**
     * Get remaining amount attribute
     */
    public function getRemainingAmountAttribute()
    {
        return $this->total_amount + $this->late_fee - $this->discount - $this->paid_amount;
    }

    /**
     * Update fee status based on payment
     */
    public function updateStatus()
    {
        $remaining = $this->getRemainingAmount();

        if ($remaining <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date < now()->toDateString()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }

        $this->save();
    }

    /**
     * Calculate late fee
     */
    public function calculateLateFee($rate = 50)
    {
        if ($this->due_date < now()->toDateString() && $this->status !== 'paid') {
            $daysLate = now()->diffInDays($this->due_date);
            $this->late_fee = $daysLate * $rate;
            $this->save();
        }
    }

    /**
     * Scope overdue fees
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
            ->whereIn('status', ['pending', 'partial']);
    }

    /**
     * Scope pending fees
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'partial', 'overdue']);
    }
}
