<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('schoolClass')->latest()->paginate(15);
        return view('school.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = Classes::active()->get();
        return view('school.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:academic,sports',
            'description' => 'nullable|string',
        ]);

        Subject::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $request->class_id,
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('school.subjects.index')
            ->with('success', 'Subject/Syllabus added successfully.');
    }

    public function edit(Subject $subject)
    {
        $classes = Classes::active()->get();
        return view('school.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:academic,sports',
            'description' => 'nullable|string',
        ]);

        $subject->update($request->all());

        return redirect()->route('school.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject deleted successfully.');
    }
}
