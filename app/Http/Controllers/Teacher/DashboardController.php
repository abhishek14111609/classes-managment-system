<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\SportsEvent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;

        // Eager load everything for the dashboard
        $batches = $teacher->batches()
            ->with(['class', 'students.user'])
            ->get();

        $totalStudents = $batches->sum(function ($batch) {
            return $batch->students->count();
        });

        $upcomingEvents = $teacher->coachedEvents()
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        $recentActivities = \App\Models\ActivityLog::where('school_id', auth()->user()->school_id)
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'batches',
            'totalStudents',
            'upcomingEvents',
            'recentActivities'
        ));
    }
}
