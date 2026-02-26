<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display all materials uploaded by this teacher.
     */
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $school = auth()->user()->school;

        $materials = Material::where('school_id', $school->id)
            ->where('teacher_id', auth()->id())
            ->with('batch')
            ->latest()
            ->paginate(15);

        // Batches this teacher is assigned to, for folder quick-filter
        $batches = $teacher ? $teacher->batches()->with('class')->get() : collect();

        return view('teacher.materials.index', compact('materials', 'batches'));
    }

    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg,jpeg,zip,mp4|max:51200',
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $school = auth()->user()->school;

        $uploaded = $request->file('file');
        $path = $uploaded->store("schools/{$school->id}/materials", 'public');

        Material::create([
            'school_id' => $school->id,
            'teacher_id' => auth()->id(),
            'batch_id' => $request->batch_id,
            'title' => $request->title,
            'file_path' => $path,
            'file_size' => $uploaded->getSize(),
            'file_type' => $uploaded->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Material uploaded successfully.');
    }

    /**
     * Serve a file download.
     */
    public function download(Material $material)
    {
        // Ensure only teacher who owns it or same school can download
        if ($material->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($material->file_path, $material->title . '.' . $material->file_type);
    }

    /**
     * Delete a material file.
     */
    public function destroy(Material $material)
    {
        if ($material->teacher_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back()->with('success', 'Material deleted.');
    }
}
