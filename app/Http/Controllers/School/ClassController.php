<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Http\Requests\StoreClassRequest;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::withCount('batches')
            ->latest()
            ->paginate(15);

        return view('school.classes.index', compact('classes'));
    }

    public function create()
    {
        $courses = \App\Models\Course::active()->get();
        return view('school.classes.create', compact('courses'));
    }

    public function store(StoreClassRequest $request)
    {
        $data = $request->validated();
        if (empty($data['school_id'])) {
            $data['school_id'] = auth()->user()->school_id;
        }

        Classes::create($data);

        return redirect()->route('school.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(Classes $class)
    {
        $class->load([
            'course',
            'batches.students.user',
            'batches.teachers.user'
        ]);

        return view('school.classes.show', compact('class'));
    }

    public function edit(Classes $class)
    {
        $courses = \App\Models\Course::active()->get();
        return view('school.classes.edit', compact('class', 'courses'));
    }

    public function update(StoreClassRequest $request, Classes $class)
    {
        $class->update($request->validated());

        return redirect()->route('school.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()->route('school.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(Classes $class)
    {
        $class->update([
            'is_active' => !$class->is_active
        ]);

        return back()->with('success', 'Class status updated successfully.');
    }
}
