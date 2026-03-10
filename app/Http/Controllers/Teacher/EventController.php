<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SportsEvent;
use App\Models\Student;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of coached events.
     */
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $events = $teacher->coachedEvents()->latest('event_date')->paginate(15);
        return view('teacher.events.index', compact('events'));
    }

    /**
     * Display a specific event and its participants.
     */
    public function show(SportsEvent $event)
    {
        $teacher = auth()->user()->teacher;
        $this->ensureCoach($event, $teacher);

        $event->load(['students.user', 'students.batch']);

        // Get all students in this teacher's batches who are NOT yet in the event
        $teacherBatchIds = $teacher->batches()->pluck('batches.id')->toArray();
        $participantIds = $event->students->pluck('id')->toArray();

        $availableStudents = Student::whereIn('batch_id', $teacherBatchIds)
            ->whereNotIn('id', $participantIds)
            ->with(['user', 'batch'])
            ->active()
            ->get();

        return view('teacher.events.show', compact('event', 'availableStudents'));
    }

    /**
     * Add participants to the event.
     */
    public function addParticipants(Request $request, SportsEvent $event)
    {
        $teacher = auth()->user()->teacher;
        $this->ensureCoach($event, $teacher);

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => [
                Rule::exists('students', 'id')->where('school_id', $request->user()->school_id),
            ],
        ]);

        foreach ($request->student_ids as $studentId) {
            EventParticipant::updateOrCreate([
                'sports_event_id' => $event->id,
                'student_id' => $studentId,
            ], [
                'participation_status' => 'registered',
                'school_id' => auth()->user()->school_id,
            ]);
        }

        return back()->with('success', count($request->student_ids) . ' students added to the event.');
    }

    /**
     * Remove a participant from the event.
     */
    public function removeParticipant(SportsEvent $event, Student $student)
    {
        $teacher = auth()->user()->teacher;
        $this->ensureCoach($event, $teacher);

        EventParticipant::where('sports_event_id', $event->id)
            ->where('student_id', $student->id)
            ->delete();

        return back()->with('success', 'Student removed from event.');
    }

    /**
     * Update participant's rank/result.
     */
    public function updateResult(Request $request, SportsEvent $event, Student $student)
    {
        $teacher = auth()->user()->teacher;
        $this->ensureCoach($event, $teacher);

        $request->validate([
            'rank' => 'nullable|integer|min:1',
            'participation_status' => 'required|in:registered,participated,withdrawn',
            'notes' => 'nullable|string|max:500'
        ]);

        EventParticipant::where('sports_event_id', $event->id)
            ->where('student_id', $student->id)
            ->update([
                'rank' => $request->rank,
                'participation_status' => $request->participation_status,
                'notes' => $request->notes
            ]);

        return back()->with('success', 'Result updated successfully.');
    }

    private function ensureCoach(SportsEvent $event, $teacher): void
    {
        if ($event->coach_id !== $teacher->id || $event->school_id !== $teacher->school_id) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
}
