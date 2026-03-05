<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class FeeService
{
    /**
     * Create fee for student
     */
    public function createFee(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check for potential duplication (same student, same batch, same plan, same month of due date)
            $existing = Fee::where('student_id', $data['student_id'])
                ->when(isset($data['batch_id']), function ($q) use ($data) {
                    return $q->where('batch_id', $data['batch_id']);
                })
                ->where('fee_plan_id', $data['fee_plan_id'] ?? null)
                ->where('fee_type', $data['fee_type'])
                ->whereMonth('due_date', date('m', strtotime($data['due_date'])))
                ->whereYear('due_date', date('Y', strtotime($data['due_date'])))
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->first();

            if ($existing) {
                $batchName = $existing->batch ? $existing->batch->name : 'this period';
                throw new \Exception("A similar active fee is already assigned to this student for {$batchName} (Period: " . date('F Y', strtotime($data['due_date'])) . ").");
            }

            $fee = Fee::create([
                'school_id' => auth()->user()->school_id,
                'fee_plan_id' => $data['fee_plan_id'] ?? null,
                'student_id' => $data['student_id'],
                'batch_id' => $data['batch_id'] ?? null,
                'fee_type' => $data['fee_type'],
                'duration' => $data['duration'] ?? null,
                'sport_level' => $data['sport_level'] ?? null,
                'total_amount' => $data['total_amount'],
                'discount' => $data['discount'] ?? 0,
                'late_fee' => $data['late_fee'] ?? 0,
                'due_date' => $data['due_date'],
                'status' => 'pending',
                'remarks' => $data['remarks'] ?? null,
            ]);

            // Handle optional initial payment
            if (isset($data['initial_paid_amount']) && $data['initial_paid_amount'] > 0) {
                $this->recordPayment([
                    'fee_id' => $fee->id,
                    'amount' => $data['initial_paid_amount'],
                    'payment_method' => $data['payment_method'] ?? 'cash',
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'notes' => 'Initial payment upon fee creation.',
                    'paid_at' => now(),
                ]);
            }

            ActivityLog::logActivity('created', 'fee', "Created fee for student ID: {$data['student_id']}");

            return $fee;
        });
    }

    /**
     * Record fee payment
     */
    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            /** @var Fee $fee */
            $fee = Fee::findOrFail($data['fee_id']);

            // Overpayment Guard
            $remaining = $fee->getRemainingAmount();
            if ($data['amount'] > ($remaining + 0.01)) { // Allow 0.01 for rounding
                throw new \Exception("Payment amount (\u20B9{$data['amount']}) exceeds the remaining balance (\u20B9{$remaining}).");
            }

            // Create payment record
            $payment = FeePayment::create([
                'fee_id' => $fee->id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'paid_at' => $data['paid_at'] ?? now(),
                'received_by' => auth()->id(),
            ]);

            // Update fee paid amount
            $fee->paid_amount += $data['amount'];
            $fee->save();

            // Update fee status
            $fee->updateStatus();

            // Generate invoice LINKED to this payment
            $this->generateInvoice($fee, $data['amount'], $payment->id);

            ActivityLog::logActivity('created', 'payment', "Recorded payment of {$data['amount']} for fee ID: {$fee->id}");

            return $payment;
        });
    }

    /**
     * Delete a payment record and revert fee status
     */
    public function deletePayment($paymentId)
    {
        return DB::transaction(function () use ($paymentId) {
            $payment = FeePayment::findOrFail($paymentId);
            $fee = $payment->fee;

            // Revert paid amount
            $fee->paid_amount -= $payment->amount;
            $fee->save();

            // The linked invoice will be deleted automatically via cascade if we wish,
            // or we delete it manually if no cascade set. Migration has cascade on fee_payment_id.
            $payment->delete();

            // Update fee status
            $fee->updateStatus();

            ActivityLog::logActivity('deleted', 'payment', "Deleted payment ID: {$paymentId} for fee ID: {$fee->id}");

            return true;
        });
    }

    /**
     * Generate invoice for fee
     */
    public function generateInvoice(Fee $fee, $amount = null, $paymentId = null)
    {
        $invoiceNumber = Invoice::generateInvoiceNumber($fee->school_id);

        $invoice = Invoice::create([
            'school_id' => $fee->school_id,
            'student_id' => $fee->student_id,
            'fee_id' => $fee->id,
            'fee_payment_id' => $paymentId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now(),
            'amount' => $amount ?? $fee->total_amount,
        ]);

        return $invoice;
    }

    /**
     * Calculate late fees for overdue payments
     */
    public function calculateLateFees($lateFeeRate = 50)
    {
        $overdueFees = Fee::overdue()->get();

        /** @var Fee $fee */
        foreach ($overdueFees as $fee) {
            $fee->calculateLateFee($lateFeeRate);
            $fee->status = 'overdue';
            $fee->save();
        }

        return $overdueFees->count();
    }

    /**
     * Get fee collection statistics
     */
    public function getCollectionStats($startDate = null, $endDate = null)
    {
        $query = Fee::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Clone for each stat to avoid query stacking
        return [
            'total_fees' => (clone $query)->sum('total_amount'),
            'collected' => (clone $query)->sum('paid_amount'),
            'pending' => (clone $query)->whereIn('status', ['pending', 'partial', 'overdue'])->sum('total_amount'),
            'overdue' => (clone $query)->where('status', 'overdue')->sum('total_amount'),
        ];
    }
}
