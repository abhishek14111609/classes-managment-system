<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin;
use App\Http\Controllers\School;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Student;
use App\Http\Controllers\Admin\SettingsController;
use App\Models\Material;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $route = auth()->user()->dashboardRoute();
        if ($route) {
            return redirect()->route($route);
        }
    }
    return view('welcome');
})->name('home');

// Auth Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Subscription Expired Page
Route::get('/subscription-expired', function () {
    return view('subscription-expired');
})->name('subscription.expired');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [Admin\DashboardController::class, 'export'])->name('dashboard.export');

    // School Management
    Route::resource('schools', Admin\SchoolController::class);
    Route::post('schools/{school}/extend-subscription', [Admin\SchoolController::class, 'extendSubscription'])->name('schools.extend-subscription');
    Route::post('schools/{school}/toggle-status', [Admin\SchoolController::class, 'toggleStatus'])->name('schools.toggle-status');

    // Plan Management
    Route::resource('plans', Admin\PlanController::class);

    // Subscription Management
    Route::get('subscriptions', [Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}/download', [Admin\SubscriptionController::class, 'download'])->name('subscriptions.download');

    // User Management
    Route::resource('users', Admin\UserController::class)->only(['index', 'edit', 'update']);
    Route::post('users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Activity Logs
    Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// School Admin Routes
Route::middleware(['auth', 'role:school_admin', 'check.subscription'])->prefix('school')->name('school.')->group(function () {
    Route::get('/dashboard', [School\DashboardController::class, 'index'])->name('dashboard');

    // Course Management
    Route::resource('courses', School\CourseController::class);

    // Class Management
    Route::resource('classes', School\ClassController::class);
    Route::post('classes/{class}/toggle-status', [School\ClassController::class, 'toggleStatus'])->name('classes.toggle-status');

    // Batch Management
    Route::resource('batches', School\BatchController::class)->middleware('check.plan.limits:batches')->only(['store']);
    Route::resource('batches', School\BatchController::class)->except(['store']);

    // Subject Management
    Route::resource('subjects', School\SubjectController::class);

    // Student Management
    Route::get('students/export', [School\StudentController::class, 'export'])->name('students.export');
    Route::post('students/{id}/restore', [School\StudentController::class, 'restore'])->name('students.restore');
    Route::get('students/{student}/statement', [School\StudentController::class, 'statement'])->name('students.statement');
    Route::resource('students', School\StudentController::class)->middleware('check.plan.limits:students')->only(['store']);
    Route::resource('students', School\StudentController::class)->except(['store']);

    // Teacher Management
    Route::resource('teachers', School\TeacherController::class);

    // Attendance Management
    Route::get('attendance', [School\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [School\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [School\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('batches/{batch}/students', [School\AttendanceController::class, 'getBatchStudents'])->name('batches.students');

    // Fee Management
    Route::resource('fees', School\FeeController::class);

    // Fee Plans (admin-defined templates)
    Route::get('fee-plans/{feePlan}/api', [School\FeePlanController::class, 'apiShow'])->name('fee-plans.api');
    Route::resource('fee-plans', School\FeePlanController::class);

    // Fee Payment
    Route::get('payments/collect/{student?}', [School\FeePaymentController::class, 'collect'])->name('payments.collect');
    Route::post('payments/bulk-store', [School\FeePaymentController::class, 'bulkStore'])->name('payments.bulk-store');
    Route::get('payments/create/{fee}', [School\FeePaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [School\FeePaymentController::class, 'store'])->name('payments.store');
    Route::delete('payments/{payment}', [School\FeePaymentController::class, 'destroy'])->name('payments.destroy');

    // Sports Events
    Route::resource('events', School\SportsEventController::class);

    // Levels / Sport Levels
    Route::resource('levels', School\LevelController::class);

    // Expense Management
    Route::resource('expenses', School\ExpenseController::class);

    // Invoices
    Route::get('invoices', [School\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}/download', [School\InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('invoices/{invoice}/stream', [School\InvoiceController::class, 'stream'])->name('invoices.stream');

    // Reports
    Route::get('reports', [School\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pending-fees', [School\ReportController::class, 'pendingFees'])->name('reports.pending-fees');
    Route::get('reports/income', [School\ReportController::class, 'income'])->name('reports.income');
    Route::get('reports/expenses', [School\ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('reports/attendance', [School\ReportController::class, 'attendance'])->name('reports.attendance');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher', 'check.subscription'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Attendance Management
    Route::get('attendance', [Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/mark', [Teacher\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance/store', [Teacher\AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('attendance/{attendance}/approve-photo', [Teacher\AttendanceController::class, 'approvePhoto'])->name('attendance.approve-photo');
    Route::post('attendance/{attendance}/reject-photo', [Teacher\AttendanceController::class, 'rejectPhoto'])->name('attendance.reject-photo');

    Route::get('batches', [Teacher\StudentController::class, 'index'])->name('batches.index');

    Route::get('batches/{batch}/students', [Teacher\StudentController::class, 'batchStudents'])->name('batches.students');
    Route::get('students/{student}', [Teacher\StudentController::class, 'show'])->name('students.show');

    // Study Materials Management (Now Fully Functional)
    Route::resource('materials', Teacher\MaterialController::class);
    Route::get('materials/{material}/download', [Teacher\MaterialController::class, 'download'])->name('materials.download');

    // Events Management
    Route::get('events', [Teacher\EventController::class, 'index'])->name('events.index');
    Route::get('events/{event}', [Teacher\EventController::class, 'show'])->name('events.show');
    Route::post('events/{event}/participants', [Teacher\EventController::class, 'addParticipants'])->name('events.participants.add');
    Route::delete('events/{event}/participants/{student}', [Teacher\EventController::class, 'removeParticipant'])->name('events.participants.remove');
    Route::patch('events/{event}/participants/{student}', [Teacher\EventController::class, 'updateResult'])->name('events.participants.update');

    // Profile & Settings
    Route::get('profile', function () {
        $teacher = auth()->user()->teacher;
        return view('teacher.profile', compact('teacher'));
    })->name('profile');

    Route::get('settings', function () {
        $teacher = auth()->user()->teacher;
        return view('teacher.settings', compact('teacher'));
    })->name('settings');
});

// Student Routes
Route::middleware(['auth', 'role:student', 'check.subscription'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

    // View Profile
    Route::get('profile', function () {
        $student = auth()->user()->student;
        return view('student.profile', compact('student'));
    })->name('profile');

    // Attendance
    Route::get('attendance', [Student\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [Student\AttendanceController::class, 'store'])->name('attendance.store');

    // Fees
    Route::get('fees', [Student\FeeController::class, 'index'])->name('fees.index');
    Route::get('fees/{fee}', [Student\FeeController::class, 'show'])->name('fees.show');

    // Events
    Route::get('events', [Student\EventController::class, 'index'])->name('events.index');

    // Resources & Timetable
    Route::get('resources', function () {
        $student = auth()->user()->student;
        $materials = Material::where(function ($q) use ($student) {
            $q->where('batch_id', '=', $student->batch_id)
                ->orWhereNull('batch_id');
        })->where('school_id', '=', $student->school_id)
            ->with('teacher')
            ->latest()
            ->get();
        return view('student.resources', compact('materials'));
    })->name('resources');

    Route::get('timetable', function () {
        $student = auth()->user()->student;
        return view('student.timetable', compact('student'));
    })->name('timetable');

    Route::get('settings', function () {
        return view('student.settings');
    })->name('settings');
});

Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

