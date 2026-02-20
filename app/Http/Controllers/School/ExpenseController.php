<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Http\Requests\StoreExpenseRequest;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        /** @var string|null $category */
        $category = $request->input('category');
        if ($category) {
            $query->byCategory($category);
        }

        /** @var string|null $startDate */
        $startDate = $request->input('start_date');
        /** @var string|null $endDate */
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }

        $expenses = $query->latest('expense_date')->paginate(15);
        $categories = Expense::distinct()->pluck('category');

        return view('school.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        return view('school.expenses.create');
    }

    public function store(StoreExpenseRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->id();

            if ($request->hasFile('receipt')) {
                $data['receipt'] = $request->file('receipt')->store('expenses/receipts', 'public');
            }

            Expense::create($data);

            return redirect()->route('school.expenses.index')
                ->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating expense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        return view('school.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('school.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('school.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('school.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
