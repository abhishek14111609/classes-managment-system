<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InstituteLabelsComposer
{
    /**
     * Bind $isSport and $label[] to every Blade view.
     * Sport academy vs. academic school label pairs.
     */
    public function compose(View $view): void
    {
        $isSport = Auth::check()
            && Auth::user()->school
            && Auth::user()->school->institute_type === 'sport';

        $label = [

            // ── People ──────────────────────────────────────────
            'student' => $isSport ? 'Student' : 'Student',
            'students' => $isSport ? 'Students' : 'Students',
            'teacher' => $isSport ? 'Coach' : 'Teacher',
            'teachers' => $isSport ? 'Coaches' : 'Teachers',

            // ── Portal names ─────────────────────────────────────
            'admin_portal' => $isSport ? 'Academy Admin' : 'School Admin',
            'teacher_portal' => $isSport ? 'Coach Portal' : 'Teacher Portal',
            'student_portal' => $isSport ? 'Athlete Portal' : 'Student Portal',

            // ── Dashboard ────────────────────────────────────────
            'dashboard' => $isSport ? 'Academy Dashboard' : 'School Dashboard',
            'my_dashboard' => $isSport ? 'My Dashboard' : 'My Dashboard',

            // ── Academic / Training structure ────────────────────
            'course' => $isSport ? 'Program' : 'Course',
            'courses' => $isSport ? 'Programs' : 'Courses',
            'class' => $isSport ? 'Team' : 'Class',
            'classes' => $isSport ? 'Teams' : 'Classes',
            'subject' => $isSport ? 'Activity' : 'Subject',
            'subjects' => $isSport ? 'Batch Types' : 'Subjects',
            'batch' => $isSport ? 'Batch' : 'Batch',
            'batches' => $isSport ? 'Batches' : 'Batches',

            // ── Attendance ───────────────────────────────────────
            'attendance' => $isSport ? 'Attendance' : 'Attendance',
            'session' => $isSport ? 'Training Session' : 'Class Session',
            'sessions' => $isSport ? 'Training Sessions' : 'Class Sessions',

            // ── Fees ─────────────────────────────────────────────
            'fees' => $isSport ? 'Academy Fees' : 'Fees & Payments',
            'fee_assign' => $isSport ? 'Assign Fee to Athlete' : 'Assign Fee to Student',

            // ── Events ───────────────────────────────────────────
            'events' => $isSport ? 'Events' : 'Events',

            // ── Materials / Resources ────────────────────────────
            'materials' => $isSport ? 'Training Materials' : 'Study Materials',
            'resources' => $isSport ? 'Training Resources' : 'Learning Resources',

            // ── Timetable ────────────────────────────────────────
            'timetable' => $isSport ? 'Training Schedule' : 'Timetable',

            // ── Expenses ─────────────────────────────────────────
            'expenses' => $isSport ? 'Academy Expenses' : 'School Expenses',

            // ── Sidebar section headings ──────────────────────────
            'section_academic' => $isSport ? 'Training' : 'Academics',
            'section_people' => 'People',
            'section_finance' => 'Finance',

            // ── Registry labels ──────────────────────────────────
            'student_registry' => $isSport ? 'Athletes List' : 'Students List',
            'teacher_registry' => $isSport ? 'Coaches List' : 'Teachers List',

            // ── Form labels ──────────────────────────────────────
            'admission_date' => $isSport ? 'Join Date' : 'Admission Date',
            'student_photo' => $isSport ? 'Athlete Photo' : 'Student Photo',

            // ── Buttons ──────────────────────────────────────────
            'add_student' => $isSport ? 'Add Athlete' : 'Add Student',
            'edit_student' => $isSport ? 'Edit Athlete' : 'Edit Student',
            'update_student' => $isSport ? 'Update Athlete' : 'Update Student',
            'add_teacher' => $isSport ? 'Add Coach' : 'Add Teacher',
            'edit_teacher' => $isSport ? 'Edit Coach' : 'Edit Teacher',
            'update_teacher' => $isSport ? 'Update Coach' : 'Update Teacher',
        ];

        $view->with('isSport', $isSport);
        $view->with('label', $label);
    }
}
