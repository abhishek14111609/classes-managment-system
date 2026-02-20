<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $events = $student->eventParticipations()
            ->with('sportsEvent.coach.user')
            ->latest('id')
            ->paginate(15);

        return view('student.events-index', compact('events'));
    }
}
