<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeePlan;
use App\Models\Student;
use App\Http\Requests\StoreFeeRequest;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function __construct(private FeeService $feeService)
    {
    }

    public function index(Request $request)
    {
        $query = Fee::with(['student.user']);

        /** @var string|null $status */
        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        /** @var int|null $studentId */
        $studentId = $request->input('student_id');
        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $fees = $query->latest()->paginate(15);
        $students = Student::with('user')->active()->get();

        // Calculate Stats for the Dashboard (Based on current filters except pagination)
        $statsQuery = clone $query;
        $statsQuery->offset(0)->limit(PHP_INT_MAX); // Unlimit for stats

        $stats = [
            'total_expected' => $statsQuery->sum('total_amount') + $statsQuery->sum('late_fee') - $statsQuery->sum('discount'),
            'total_paid' => $statsQuery->sum('paid_amount'),
        ];
        $stats['total_outstanding'] = $stats['total_expected'] - $stats['total_paid'];

        return view('school.fees.index', compact('fees', 'students', 'stats'));
    }

    public function create(Request $request)
    {
        $students = Student::with(['user', 'batches'])->active()->get();
        $feePlans = FeePlan::active()->get();
        $selectedPlan = $request->input('plan') ? FeePlan::find($request->input('plan')) : null;

        return view('school.fees.create', compact('students', 'feePlans', 'selectedPlan'));
    }

    public function store(StoreFeeRequest $request)
    {
        try {
            $this->feeService->createFee($request->validated());

            return redirect()->route('school.fees.index')
                ->with('success', 'Fee created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating fee: ' . $e->getMessage());
        }
    }

    public function show(Fee $fee)
    {
        $fee->load(['student.user', 'payments.receivedBy']);

        return view('school.fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $students = Student::with(['user', 'batches'])->active()->get();

        $levels = \App\Models\Level::where('is_active', true)->get();
        return view('school.fees.edit', compact('fee', 'students', 'levels'));
    }

    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lte:total_amount',
            'late_fee' => 'nullable|numeric|min:0',
            'batch_id' => 'nullable|exists:batches,id',
            'sport_level' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $fee->update($validated);

        // Recalculate status because amounts or dates may have changed
        $fee->updateStatus();

        return redirect()->route('school.fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        // Protect fees that have already been (partially) paid
        if ($fee->paid_amount > 0) {
            return back()->with(
                'error',
                'Cannot delete a fee that has recorded payments. Void the payments first.'
            );
        }

        $fee->delete();

        return redirect()->route('school.fees.index')
            ->with('success', 'Fee deleted successfully.');
    }
}
