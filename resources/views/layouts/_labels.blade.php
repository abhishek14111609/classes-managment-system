{{--
============================================================
GLOBAL LABEL DEFINITIONS — Institute Type Aware
============================================================
Include this file at the top of any view that needs
institute-type-sensitive labels.

Usage: @include('layouts._labels')
Then: {{ $label['student'] }} → "Student" or "Athlete"
============================================================
--}}

@php
    // Detect institute type once
    $isSport = auth()->check()
        && auth()->user()->school
        && auth()->user()->school->institute_type === 'sport';

    // ─── Core Role Labels ─────────────────────────────────────
    $label = [

        // People
        'student' => $isSport ? 'Athlete' : 'Student',
        'students' => $isSport ? 'Athletes' : 'Students',
        'teacher' => $isSport ? 'Coach' : 'Teacher',
        'teachers' => $isSport ? 'Coaches' : 'Teachers',

        // Portal names
        'admin_portal' => $isSport ? 'Academy Admin' : 'School Admin',
        'teacher_portal' => $isSport ? 'Coach Portal' : 'Teacher Portal',
        'student_portal' => $isSport ? 'Athlete Portal' : 'Student Portal',

        // Dashboard
        'dashboard' => $isSport ? 'Academy Dashboard' : 'School Dashboard',
        'my_dashboard' => $isSport ? 'My Dashboard' : 'My Dashboard',

        // Academic/Training structure
        'course' => $isSport ? 'Program' : 'Course',
        'courses' => $isSport ? 'Programs' : 'Courses',
        'class' => $isSport ? 'Team' : 'Class',
        'classes' => $isSport ? 'Teams' : 'Classes',
        'subject' => $isSport ? 'Activity' : 'Subject',
        'subjects' => $isSport ? 'Activities' : 'Subjects',
        'batch' => $isSport ? 'Batch' : 'Batch',
        'batches' => $isSport ? 'Batches' : 'Batches',

        // Attendance
        'attendance' => $isSport ? 'Attendance' : 'Attendance',
        'session' => $isSport ? 'Training Session' : 'Class Session',
        'sessions' => $isSport ? 'Training Sessions' : 'Class Sessions',

        // Fees
        'fees' => $isSport ? 'Academy Fees' : 'Fees & Payments',
        'fee_assign' => $isSport ? 'Assign Fee to Athlete' : 'Assign Fee to Student',

        // Events
        'events' => $isSport ? 'Events' : 'Events',

        // Materials/Resources
        'materials' => $isSport ? 'Training Materials' : 'Study Materials',
        'resources' => $isSport ? 'Training Resources' : 'Learning Resources',

        // Timetable
        'timetable' => $isSport ? 'Training Schedule' : 'Timetable',

        // Expenses
        'expenses' => $isSport ? 'Academy Expenses' : 'School Expenses',

        // Section group labels (sidebar)
        'section_academic' => $isSport ? 'Training' : 'Academics',
        'section_people' => $isSport ? 'People' : 'People',
        'section_finance' => 'Finance',

        // Registry labels
        'student_registry' => $isSport ? 'Athletes List' : 'Students List',
        'teacher_registry' => $isSport ? 'Coaches List' : 'Teachers List',

        // Admission
        'admission_date' => $isSport ? 'Join Date' : 'Admission Date',

        // Photo
        'student_photo' => $isSport ? 'Athlete Photo' : 'Student Photo',

        // Buttons
        'add_student' => $isSport ? 'Add Athlete' : 'Add Student',
        'edit_student' => $isSport ? 'Edit Athlete' : 'Edit Student',
        'update_student' => $isSport ? 'Update Athlete' : 'Update Student',
        'add_teacher' => $isSport ? 'Add Coach' : 'Add Teacher',
        'edit_teacher' => $isSport ? 'Edit Coach' : 'Edit Teacher',
        'update_teacher' => $isSport ? 'Update Coach' : 'Update Teacher',
    ];
@endphp