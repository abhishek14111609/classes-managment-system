<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Classes;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Subject;
use App\Http\Requests\StoreBatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with('class')->withCount('students');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $batches = $query->latest()->paginate(15);
        $classes = Classes::active()->get();

        return view('school.batches.index', compact('batches', 'classes'));
    }

    public function create()
    {
        $isSport = auth()->user()->school->institute_type === 'sport';
        $classes = Classes::active()->get();
        $courses = Course::active()->get();
        $teachers = Teacher::with('user')->active()->get();
        $subjects = Subject::with(['level', 'schoolClass'])->active()->get();
        $levels = \App\Models\Level::where('is_active', true)->get();

        return view('school.batches.create', compact('classes', 'courses', 'teachers', 'subjects', 'levels', 'isSport'));
    }

    public function store(StoreBatchRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            if (auth()->user()->school->institute_type === 'sport' && !empty($data['subject_id'])) {
                $subject = Subject::with(['level', 'schoolClass.course'])->find($data['subject_id']);
                if ($subject) {
                    $data['class_id'] = $subject->class_id;
                    $data['sport_level'] = $subject->level->name ?? null;
                    
                    if (empty($data['name'])) {
                        $sportName = $subject->schoolClass->course->name ?? 'Sport';
                        $levelName = $subject->level->name ?? 'Any Level';
                        $data['name'] = "{$subject->name} ({$levelName})";
                    }
                }
            }

            $batch = Batch::create($data);

            if ($request->has('teacher_ids')) {
                $batch->teachers()->sync($request->teacher_ids);
            }

            return redirect()->route('school.batches.index')
                ->with('success', auth()->user()->school->institute_type === 'sport' ? 'Training session created successfully.' : 'Batch created successfully.');
        });
    }

    public function edit(Batch $batch)
    {
        $isSport = auth()->user()->school->institute_type === 'sport';
        $classes = Classes::active()->get();
        $courses = Course::active()->get();
        $teachers = Teacher::with('user')->active()->get();
        $subjects = Subject::with(['level', 'schoolClass'])->active()->get();
        $levels = \App\Models\Level::where('is_active', true)->get();

        return view('school.batches.edit', compact('batch', 'classes', 'courses', 'teachers', 'subjects', 'levels', 'isSport'));
    }

    public function update(StoreBatchRequest $request, Batch $batch)
    {
        return DB::transaction(function () use ($request, $batch) {
            $data = $request->validated();

            if (auth()->user()->school->institute_type === 'sport' && !empty($data['subject_id'])) {
                $subject = Subject::with(['level', 'schoolClass.course'])->find($data['subject_id']);
                if ($subject) {
                    $data['class_id'] = $subject->class_id;
                    $data['sport_level'] = $subject->level->name ?? null;
                    
                    if (empty($data['name'])) {
                        $levelName = $subject->level->name ?? 'Any Level';
                        $data['name'] = "{$subject->name} ({$levelName})";
                    }
                }
            }

            $batch->update($data);

            if ($request->has('teacher_ids')) {
                $batch->teachers()->sync($request->teacher_ids);
            } else {
                $batch->teachers()->sync([]);
            }

            return redirect()->route('school.batches.index')
                ->with('success', auth()->user()->school->institute_type === 'sport' ? 'Training session updated successfully.' : 'Batch updated successfully.');
        });
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('school.batches.index')
            ->with('success', 'Batch deleted successfully.');
    }

    public function show(Batch $batch)
    {
        $batch->load(['class', 'students.user', 'teachers.user']);

        return view('school.batches.show', compact('batch'));
    }
}
