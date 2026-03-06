<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with(['course', 'level'])->latest()->get();
        return view('school.inventory.items.index', compact('items'));
    }

    public function create()
    {
        $courses = Course::active()->get();
        $levels = Level::all();
        return view('school.inventory.items.create', compact('courses', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'course_id' => 'nullable|exists:courses,id',
            'level_id' => 'nullable|exists:levels,id',
            'status' => 'boolean',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('school.inventory.items.index')->with('success', 'Item added to inventory successfully.');
    }

    public function edit(InventoryItem $item)
    {
        $courses = Course::active()->get();
        $levels = Level::all();
        return view('school.inventory.items.edit', compact('item', 'courses', 'levels'));
    }

    public function update(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'course_id' => 'nullable|exists:courses,id',
            'level_id' => 'nullable|exists:levels,id',
            'status' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->route('school.inventory.items.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(InventoryItem $item)
    {
        $item->delete();
        return redirect()->route('school.inventory.items.index')->with('success', 'Item removed from inventory.');
    }
}
