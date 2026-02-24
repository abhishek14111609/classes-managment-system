<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\SportsEvent;
use App\Models\Teacher;
use App\Models\Student;
use App\Http\Requests\StoreSportsEventRequest;
use Illuminate\Http\Request;

class SportsEventController extends Controller
{
    public function index()
    {
        $events = SportsEvent::with(['coach.user'])
            ->latest('event_date')
            ->paginate(15);

        return view('school.events.index', compact('events'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->active()->get();
        $students = Student::with('user')->active()->get();
        $levels = \App\Models\Level::where('is_active', true)->get();

        return view('school.events.create', compact('teachers', 'students', 'levels'));
    }

    public function store(StoreSportsEventRequest $request)
    {
        try {
            $event = SportsEvent::create($request->except('participants'));

            if ($request->has('participants')) {
                foreach ($request->participants as $studentId) {
                    $event->participants()->create([
                        'student_id' => $studentId,
                        'participation_status' => 'registered',
                    ]);
                }
            }

            return redirect()->route('school.events.index')
                ->with('success', 'Sports event created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating event: ' . $e->getMessage());
        }
    }

    public function show(SportsEvent $event)
    {
        $event->load(['coach.user', 'participants.student.user']);

        return view('school.events.show', compact('event'));
    }

    public function edit(SportsEvent $event)
    {
        $teachers = Teacher::with('user')->active()->get();
        $students = Student::with('user')->active()->get();
        $levels = \App\Models\Level::where('is_active', true)->get();
        $event->load('participants');

        return view('school.events.edit', compact('event', 'teachers', 'students', 'levels'));
    }

    public function update(StoreSportsEventRequest $request, SportsEvent $event)
    {
        try {
            $event->update($request->except('participants'));

            // Always delete old participants, then re-insert selected ones
            // This allows admin to intentionally remove ALL participants
            $event->participants()->delete();

            if ($request->filled('participants')) {
                foreach ($request->participants as $studentId) {
                    $event->participants()->create([
                        'student_id' => $studentId,
                        'participation_status' => 'registered',
                    ]);
                }
            }

            return redirect()->route('school.events.index')
                ->with('success', 'Sports event updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating event: ' . $e->getMessage());
        }
    }

    public function destroy(SportsEvent $event)
    {
        $event->delete();

        return redirect()->route('school.events.index')
            ->with('success', 'Sports event deleted successfully.');
    }
}
