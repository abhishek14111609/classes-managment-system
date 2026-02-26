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

        // Eager load classes and count students to avoid high memory usage
        $batches = $teacher->batches()
            ->select('batches.*')
            ->with(['class'])
            ->withCount('students')
            ->get();

        $totalStudents = $batches->sum('students_count');

        $batchIds = $batches->pluck('id');

        // Calculate health score for each batch
        $batches->each(function ($batch) use ($batchIds) {
            $totalAtt = \App\Models\Attendance::where('batch_id', $batch->id)->count();
            $present = \App\Models\Attendance::where('batch_id', $batch->id)->where('status', 'present')->count();
            $attRate = $totalAtt > 0 ? ($present / $totalAtt) * 100 : 0;

            // Participation health: % of students in batch who have participated in at least one event
            $studentIds = $batch->students()->active()->pluck('students.id');
            $participatedCount = \App\Models\EventParticipant::whereIn('student_id', $studentIds)
                ->where('participation_status', 'participated')
                ->distinct('student_id')
                ->count();
            $partRate = $studentIds->count() > 0 ? ($participatedCount / $studentIds->count()) * 100 : 0;

            $batch->health_score = round(($attRate + $partRate) / 2);
        });

        $totalAttendances = \App\Models\Attendance::whereIn('batch_id', $batchIds)->count();
        $presentAttendances = \App\Models\Attendance::whereIn('batch_id', $batchIds)->where('status', 'present')->count();
        $avgAttendance = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100) : 0;

        $todaySessions = $batches->sortBy('start_time');

        $upcomingEvents = $teacher->coachedEvents()
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();



        return view('teacher.dashboard', compact(
            'teacher',
            'batches',
            'totalStudents',
            'upcomingEvents',
            'avgAttendance',
            'todaySessions'
        ));
    }
}
