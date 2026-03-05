<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::withCount('classes')->latest()->paginate(10);
        return view('school.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('school.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses')->where('school_id', $schoolId),
            ],
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.unique' => 'A course with this name already exists in your school.',
        ]);

        $validated['school_id'] = $schoolId;

        if (empty($validated['code'])) {
            $schoolName = auth()->user()->school->name ?? 'INS';
            $schoolPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $schoolName), 0, 3));
            $instituteType = auth()->user()->school->institute_type ?? 'academic';
            $prefix = $schoolPrefix . ($instituteType === 'sport' ? '-PRO-' : '-CRS-');
            $validated['code'] = $prefix . strtoupper(\Illuminate\Support\Str::random(5));
        }

        $course = Course::create($validated);

        // For sports academies, auto-create a hidden "Default Team" for this program
        if (auth()->user()->school->institute_type === 'sport') {
            Classes::create([
                'school_id' => $schoolId,
                'course_id' => $course->id,
                'name' => $course->name . ' - Default Team',
                'is_active' => true
            ]);
        }

        return redirect()->route('school.courses.index')
            ->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load('classes.batches');
        return view('school.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('school.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $course->update($validated);

        // Sync shadow-class name if it exists for sports akademies
        if (auth()->user()->school->institute_type === 'sport') {
            $course->classes()->update(['name' => $course->name . ' - Default Team']);
        }

        return redirect()->route('school.courses.index')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('school.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
