<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::where('school_id', auth()->user()->school_id)
            ->latest()
            ->paginate(10);

        return view('school.levels.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('school.levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['school_id'] = auth()->user()->school_id;

        Level::create($validated);

        return redirect()->route('school.levels.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        if ($level->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('school.levels.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        if ($level->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $level->update($validated);

        return redirect()->route('school.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        if ($level->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $level->delete();

        return redirect()->route('school.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
