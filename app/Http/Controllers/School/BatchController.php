<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Classes;
use App\Models\Teacher;
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
        $classes = Classes::active()->get();
        $teachers = Teacher::with('user')->active()->get();
        $subjects = \App\Models\Subject::active()->get();

        return view('school.batches.create', compact('classes', 'teachers', 'subjects'));
    }

    public function store(StoreBatchRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $batch = Batch::create($request->validated());

            if ($request->has('teacher_ids')) {
                $batch->teachers()->sync($request->teacher_ids);
            }

            return redirect()->route('school.batches.index')
                ->with('success', 'Batch created successfully.');
        });
    }

    public function edit(Batch $batch)
    {
        $classes = Classes::active()->get();
        $teachers = Teacher::with('user')->active()->get();
        $subjects = \App\Models\Subject::active()->get();

        return view('school.batches.edit', compact('batch', 'classes', 'teachers', 'subjects'));
    }

    public function update(StoreBatchRequest $request, Batch $batch)
    {
        return DB::transaction(function () use ($request, $batch) {
            $batch->update($request->validated());

            if ($request->has('teacher_ids')) {
                $batch->teachers()->sync($request->teacher_ids);
            } else {
                $batch->teachers()->sync([]);
            }

            return redirect()->route('school.batches.index')
                ->with('success', 'Batch updated successfully.');
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
