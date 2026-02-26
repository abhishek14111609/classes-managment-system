<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $participations = $student->eventParticipations()
            ->with(['sportsEvent.coach.user'])
            ->latest('id')
            ->get();

        $upcomingEvents = \App\Models\SportsEvent::upcoming()
            ->whereHas('participants', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['coach.user'])
            ->get();

        $stats = [
            'gold' => $participations->where('rank', 1)->count(),
            'silver' => $participations->where('rank', 2)->count(),
            'bronze' => $participations->where('rank', 3)->count(),
            'total' => $participations->count(),
        ];

        return view('student.events-index', [
            'participations' => $participations,
            'upcomingEvents' => $upcomingEvents,
            'stats' => $stats
        ]);
    }
}
