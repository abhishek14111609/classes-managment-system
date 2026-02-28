<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Build a configured Dompdf instance with FreeSans font registered.
     */
    private function buildPdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView('school.invoices.pdf', compact('invoice'));

        // Directly register FreeSans in DomPDF's font lookup table.
        // setFontFamily() maps the CSS family name -> local TTF path,
        // no .ufm generation or download needed.
        $fontPath = public_path('fonts/FreeSans');   // DomPDF appends .ttf/.ufm itself

        $pdf->getDomPDF()
            ->getFontMetrics()
            ->setFontFamily('FreeSans', ['normal' => $fontPath]);

        return $pdf;
    }

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
        if ($invoice->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $pdf = $this->buildPdf($invoice);

        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * View invoice as PDF in browser.
     */
    public function stream(Invoice $invoice)
    {
        if ($invoice->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $pdf = $this->buildPdf($invoice);

        return $pdf->stream("Invoice-{$invoice->invoice_number}.pdf");
    }
}
