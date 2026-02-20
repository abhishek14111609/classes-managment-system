<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $fee_id
 * @property float $amount
 * @property string $payment_method
 * @property string|null $transaction_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $paid_at
 * @property int $received_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Fee $fee
 * @property-read User $receivedBy
 */
class FeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_id',
        'amount',
        'payment_method',
        'transaction_id',
        'notes',
        'paid_at',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the fee
     */
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    /**
     * Get the user who received payment
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
