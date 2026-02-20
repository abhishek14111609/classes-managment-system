<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['student.user', 'fee'])
            ->latest()
            ->paginate(15);

        return view('school.invoices.index', compact('invoices'));
    }

    /**
     * Download invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        // Ensure the invoice belongs to the school
        if ($invoice->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('school.invoices.pdf', compact('invoice'));

        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * View invoice as PDF in browser.
     */
    public function stream(Invoice $invoice)
    {
        // Ensure the invoice belongs to the school
        if ($invoice->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('school.invoices.pdf', compact('invoice'));

        return $pdf->stream("Invoice-{$invoice->invoice_number}.pdf");
    }
}
