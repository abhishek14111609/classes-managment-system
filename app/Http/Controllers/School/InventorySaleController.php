<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventorySale;
use App\Models\Student;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InventorySaleController extends Controller
{
    public function index()
    {
        $sales = InventorySale::with(['student.user', 'item'])->latest()->paginate(20);
        return view('school.inventory.sales.index', compact('sales'));
    }

    public function create()
    {
        $students = Student::with('user')->active()->get();
        $items = InventoryItem::active()->where('stock_quantity', '>', 0)->get();
        return view('school.inventory.sales.create', compact('students', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'payment_status' => 'required|in:paid,pending,partial',
        ]);

        $item = InventoryItem::findOrFail($validated['item_id']);

        if ($item->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available. Current stock: ' . $item->stock_quantity);
        }

        DB::beginTransaction();
        try {
            $totalAmount = $item->price * $validated['quantity'];

            // 1. Create Invoice first (optional but good for tracking)
            $invoice = Invoice::create([
                'school_id' => auth()->user()->school_id,
                'student_id' => $validated['student_id'],
                'fee_id' => null,
                'invoice_number' => 'INV-KIT-' . strtoupper(uniqid()),
                'invoice_date' => now(),
                'amount' => $totalAmount,
            ]);

            // 2. Create Sale Record
            $sale = InventorySale::create([
                'school_id' => auth()->user()->school_id,
                'student_id' => $validated['student_id'],
                'item_id' => $validated['item_id'],
                'quantity' => $validated['quantity'],
                'unit_price' => $item->price,
                'total_amount' => $totalAmount,
                'payment_status' => $validated['payment_status'],
                'invoice_id' => $invoice->id,
            ]);

            // 3. Deduct Stock
            $item->decrement('stock_quantity', $validated['quantity']);

            DB::commit();

            return redirect()->route('school.inventory.sales.index')->with('success', 'Sale recorded successfully and invoice generated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function downloadInvoice(InventorySale $sale)
    {
        $sale->load(['school', 'student.user', 'item']);
        $pdf = Pdf::loadView('school.inventory.sales.invoice_pdf', compact('sale'));

        // Reuse font logic if needed
        $fontPath = public_path('fonts/FreeSans');
        $pdf->getDomPDF()
            ->getFontMetrics()
            ->setFontFamily('FreeSans', ['normal' => $fontPath]);

        return $pdf->download("Kit-Invoice-{$sale->id}.pdf");
    }
}
