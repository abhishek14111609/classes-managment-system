<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property int $fee_id
 * @property string $invoice_number
 * @property \Illuminate\Support\Carbon $invoice_date
 * @property float $amount
 * @property string|null $pdf_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School $school
 * @property-read Student $student
 * @property-read Fee $fee
 */
class Invoice extends Model
{
    use HasFactory, MultiTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'fee_id',
        'invoice_number',
        'invoice_date',
        'amount',
        'pdf_path',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'amount' => 'decimal:2',
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
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the fee
     */
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber($schoolId)
    {
        $lastInvoice = self::where('school_id', $schoolId)
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -6)) + 1 : 1;

        return 'INV-' . $schoolId . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
