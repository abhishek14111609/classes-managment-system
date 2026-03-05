<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\FeePlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeePlanController extends Controller
{
    public function index()
    {
        $plans = FeePlan::with(['course', 'batch'])->withCount('fees')->latest()->paginate(15);
        return view('school.fee-plans.index', compact('plans'));
    }

    public function create()
    {
        $levels = \App\Models\Level::where('is_active', true)->get();
        $courses = \App\Models\Course::all();
        $batches = \App\Models\Batch::with('class')->get();
        return view('school.fee-plans.create', compact('levels', 'courses', 'batches'));
    }

    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fee_plans')->where('school_id', $schoolId),
            ],
            'course_id' => 'nullable|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'fee_type' => 'required|string|in:tuition,sports,transport,exam,library,other',
            'duration' => 'nullable|string|in:monthly,quarterly,half_yearly,annual,one_time',
            'sport_level' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'name.unique' => 'A fee plan with this name already exists.',
        ]);

        $validated['school_id'] = $schoolId;
        $validated['late_fee_per_day'] = $validated['late_fee_per_day'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        FeePlan::create($validated);

        return redirect()->route('school.fee-plans.index')
            ->with('success', 'Fee plan "' . $validated['name'] . '" created successfully.');
    }

    public function show(FeePlan $feePlan)
    {
        $feePlan->loadCount('fees');
        return view('school.fee-plans.show', compact('feePlan'));
    }

    public function edit(FeePlan $feePlan)
    {
        $levels = \App\Models\Level::where('is_active', true)->get();
        $courses = \App\Models\Course::all();
        $batches = \App\Models\Batch::with('class')->get();
        return view('school.fee-plans.edit', compact('feePlan', 'levels', 'courses', 'batches'));
    }

    public function update(Request $request, FeePlan $feePlan)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fee_plans')->where('school_id', $schoolId)->ignore($feePlan->id),
            ],
            'course_id' => 'nullable|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'fee_type' => 'required|string|in:tuition,sports,transport,exam,library,other',
            'duration' => 'nullable|string|in:monthly,quarterly,half_yearly,annual,one_time',
            'sport_level' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $feePlan->update($validated);

        return redirect()->route('school.fee-plans.index')
            ->with('success', 'Fee plan updated successfully.');
    }

    public function destroy(FeePlan $feePlan)
    {
        if ($feePlan->fees()->count() > 0) {
            return back()->with(
                'error',
                'Cannot delete this plan — it has ' . $feePlan->fees()->count() . ' fee(s) assigned. Deactivate it instead.'
            );
        }

        $feePlan->delete();

        return redirect()->route('school.fee-plans.index')
            ->with('success', 'Fee plan deleted.');
    }

    /** API endpoint: returns plan details as JSON for the fee-create form autofill */
    public function apiShow(FeePlan $feePlan)
    {
        return response()->json([
            'id' => $feePlan->id,
            'name' => $feePlan->name,
            'fee_type' => $feePlan->fee_type,
            'sport_level' => $feePlan->sport_level,
            'amount' => $feePlan->amount,
            'description' => $feePlan->description,
        ]);
    }
}
