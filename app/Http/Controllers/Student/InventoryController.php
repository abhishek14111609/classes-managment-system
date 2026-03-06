<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InventorySale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $purchases = InventorySale::where('student_id', $student->id)
            ->with(['item', 'school'])
            ->latest()
            ->paginate(10);

        return view('student.inventory.index', compact('purchases'));
    }

    public function downloadInvoice(InventorySale $sale)
    {
        // Ensure student can only download their own invoice
        if ($sale->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $sale->load(['school', 'student.user', 'item']);
        $pdf = Pdf::loadView('school.inventory.sales.invoice_pdf', compact('sale'));

        $fontPath = public_path('fonts/FreeSans');
        $pdf->getDomPDF()
            ->getFontMetrics()
            ->setFontFamily('FreeSans', ['normal' => $fontPath]);

        return $pdf->download("My-Kit-Invoice-{$sale->id}.pdf");
    }
}
