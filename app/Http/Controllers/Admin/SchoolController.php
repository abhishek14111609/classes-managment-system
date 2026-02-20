<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Plan;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(private SchoolService $schoolService)
    {
    }

    public function index()
    {
        $schools = School::with('activeSubscription.plan')
            ->withCount(['students', 'teachers', 'users'])
            ->latest()
            ->paginate(15);

        return view('admin.schools.index', compact('schools'));
    }

    public function show(School $school)
    {
        $school->load(['activeSubscription.plan', 'subscriptions.plan', 'schoolAdmin'])
            ->loadCount(['students', 'teachers', 'users']);

        return view('admin.schools.show', compact('school'));
    }

    public function create()
    {
        $plans = Plan::active()->get();

        return view('admin.schools.create', compact('plans'));
    }

    public function store(StoreSchoolRequest $request)
    {
        try {
            $school = $this->schoolService->createSchool($request->validated());

            return redirect()->route('admin.schools.index')
                ->with('success', 'School created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating school: ' . $e->getMessage());
        }
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school)
    {
        try {
            $this->schoolService->updateSchool($school, $request->validated());

            return redirect()->route('admin.schools.index')
                ->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating school: ' . $e->getMessage());
        }
    }

    public function destroy(School $school)
    {
        try {
            $school->delete();

            return redirect()->route('admin.schools.index')
                ->with('success', 'School deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting school: ' . $e->getMessage());
        }
    }

    public function extendSubscription(Request $request, School $school)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:cash,online,bank_transfer,manual',
        ]);

        try {
            /** @var int|null $planId */
            $planId = $request->input('plan_id');
            $plan = $planId ? Plan::find($planId) : null;
            /** @var int $days */
            $days = $request->input('days');

            $this->schoolService->extendSubscription(
                $school,
                $days,
                $plan,
                $request->input('amount_paid', 0),
                $request->input('payment_method', 'manual')
            );

            return back()->with('success', 'Subscription extended successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error extending subscription: ' . $e->getMessage());
        }
    }

    public function toggleStatus(School $school)
    {
        $school->update([
            'status' => $school->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'School status updated successfully.');
    }
}
