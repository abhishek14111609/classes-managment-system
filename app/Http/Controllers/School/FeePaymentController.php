<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Http\Requests\StoreFeePaymentRequest;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeePaymentController extends Controller
{
    public function __construct(private FeeService $feeService)
    {
    }

    public function create(Fee $fee)
    {
        return view('school.payments.create', compact('fee'));
    }

    public function store(StoreFeePaymentRequest $request)
    {
        try {
            $payment = $this->feeService->recordPayment($request->validated());

            // Get the newly generated invoice linked to this payment
            $invoice = \App\Models\Invoice::where('fee_payment_id', $payment->id)->first();

            return redirect()->route('school.fees.show', $request->fee_id)
                ->with('success', 'Payment recorded successfully.')
                ->with('open_invoice_id', $invoice ? $invoice->id : null);
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->feeService->deletePayment($id);
            return back()->with('success', 'Payment deleted and fee status reverted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }
}
