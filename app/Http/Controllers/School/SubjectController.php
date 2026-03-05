<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['schoolClass', 'level'])->latest()->paginate(15);
        return view('school.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $isSport = auth()->user()->school->institute_type === 'sport';
        $classes = Classes::active()->get();
        $courses = Course::active()->get();
        $levels = Level::where('is_active', true)->get();

        return view('school.subjects.create', compact('classes', 'courses', 'levels', 'isSport'));
    }

    public function store(Request $request)
    {
        $isSport = auth()->user()->school->institute_type === 'sport';

        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:academic,sports',
            'description' => 'nullable|string',
        ];

        if ($isSport) {
            $rules['course_id'] = 'required|exists:courses,id';
            $rules['level_id'] = 'required|exists:levels,id';
        } else {
            $rules['class_id'] = 'required|exists:classes,id';
        }

        $request->validate($rules);

        $classId = $request->class_id;
        if ($isSport) {
            $classId = $this->getOrCreateDefaultClass($request->course_id);
        }

        Subject::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $classId,
            'level_id' => $isSport ? $request->level_id : null,
            'name' => $request->name,
            'activity_name' => $isSport ? $request->name : null,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('school.subjects.index')
            ->with('success', $isSport ? 'Batch Type (Activity) added successfully.' : 'Subject added successfully.');
    }

    public function edit(Subject $subject)
    {
        $isSport = auth()->user()->school->institute_type === 'sport';
        $classes = Classes::active()->get();
        $courses = Course::active()->get();
        $levels = Level::where('is_active', true)->get();

        return view('school.subjects.edit', compact('subject', 'classes', 'courses', 'levels', 'isSport'));
    }

    public function update(Request $request, Subject $subject)
    {
        $isSport = auth()->user()->school->institute_type === 'sport';

        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:academic,sports',
            'description' => 'nullable|string',
        ];

        if ($isSport) {
            $rules['course_id'] = 'required|exists:courses,id';
            $rules['level_id'] = 'required|exists:levels,id';
        } else {
            $rules['class_id'] = 'required|exists:classes,id';
        }

        $request->validate($rules);

        $classId = $request->class_id;
        if ($isSport) {
            $classId = $this->getOrCreateDefaultClass($request->course_id);
        }

        $subject->update([
            'class_id' => $classId,
            'level_id' => $isSport ? $request->level_id : null,
            'name' => $request->name,
            'activity_name' => $isSport ? $request->name : null,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('school.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject deleted successfully.');
    }

    /**
     * Helper to find or create a default team for a sport course.
     */
    private function getOrCreateDefaultClass($courseId)
    {
        $course = Course::find($courseId);
        $defaultClass = Classes::where('course_id', $courseId)->first();

        if (!$defaultClass && $course) {
            $defaultClass = Classes::create([
                'school_id' => auth()->user()->school_id,
                'course_id' => $courseId,
                'name' => $course->name . ' - Default Team',
                'is_active' => true
            ]);
        }

        return $defaultClass ? $defaultClass->id : null;
    }
}
