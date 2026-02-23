# 🏛️ Unified Institute Management System
## Laravel Enterprise Architecture Report
### For: Academic Institutes & Sports Academies — One Backend, Two Worlds

**Document Type:** System Architecture & Design Report
**Framework:** Laravel 12.x (PHP 8.3+)
**Multi-Tenancy:** Row-Level (institute_id everywhere)
**RBAC:** Spatie Laravel Permission (with Teams)
**Prepared By:** Senior Laravel Architect

---

## 📌 TABLE OF CONTENTS

1. [System Overview](#1-system-overview)
2. [Institute Type Architecture](#2-institute-type-architecture)
3. [Four Panel Structure](#3-four-panel-structure)
4. [Feature Matrix Per Panel](#4-feature-matrix-per-panel)
5. [Database Architecture](#5-database-architecture)
6. [Role & Permission Structure](#6-role--permission-structure)
7. [Dynamic Module Loading](#7-dynamic-module-loading)
8. [Middleware Strategy](#8-middleware-strategy)
9. [Folder & Module Structure](#9-folder--module-structure)
10. [Preventing Academic ↔ Sports Logic Mixing](#10-preventing-academic--sports-logic-mixing)
11. [Scalability Design](#11-scalability-design)
12. [Data Flow Diagrams](#12-data-flow-diagrams)
13. [Client Presentation Summary](#13-client-presentation-summary)

---

## 1. System Overview

This system is a **single unified Laravel backend** that serves two completely different business domains:

| Domain | Type Value | Target Clients |
|--------|-----------|----------------|
| Academic Institute | `academic` | Schools, Coaching Centers, Tuition Classes, GPSC |
| Sports Academy | `sports` | Cricket Academy, Football Club, Badminton Academy |

The core principle is:
> **One codebase. One database. Two completely separate operating modes.**

A Platform Admin creates an institute, assigns it a type (`academic` or `sports`), and from that moment forward, **every panel, every route, every module, every view** adapts to that institute's type. The two modes never interfere with each other.

---

## 2. Institute Type Architecture

### How Type Determination Works

```
User Logs In
     │
     ▼
Auth Middleware: Identify User's institute_id
     │
     ▼
Load Institute Record → institute.type = 'academic' | 'sports'
     │
     ├──► type = 'academic' ──► Inject AcademicContext
     │                              Load academic routes
     │                              Load academic views
     │                              Block sports middleware
     │
     └──► type = 'sports'   ──► Inject SportsContext
                                    Load sports routes
                                    Load sports views
                                    Block academic middleware
```

### Context Injection

The `institute.type` value is resolved once per session and stored in:
- Laravel Session (`session('institute_type')`)
- Shared middleware property accessible anywhere in the request lifecycle

This prevents repeated database lookups on every request.

### Behavioral Separation Rules

| Rule | Description |
|------|-------------|
| No shared navigation | Academic sidebar ≠ Sports sidebar |
| No shared routes | `/institute/subjects` only for academic; `/institute/sports-events` only for sports |
| No shared models in controllers | Course/Subject models blocked in sports context |
| No shared views | Blade layouts are type-specific |
| Full data isolation | All queries scope by `institute_id` + type context |

---

## 3. Four Panel Structure

### Panel 1: Platform Admin Panel
> **Audience:** The SaaS owner / super admin
> **URL Prefix:** `/admin`
> **No institute type restriction** — sees both

### Panel 2: Institute Admin Panel
> **Audience:** School Principal, Academy Director
> **URL Prefix:** `/institute`
> **Behavior changes based on institute.type**

### Panel 3: Teacher / Coach Panel
> **Audience:** Academic teacher OR sports coach (separate role)
> **URL Prefix:** `/staff`
> **Behavior changes based on institute.type**

### Panel 4: Student Panel
> **Audience:** Academic student OR sports student
> **URL Prefix:** `/student`
> **Behavior changes based on institute.type**

---

## 4. Feature Matrix Per Panel

### 4.1 Platform Admin Panel (Type-Agnostic)

| Feature | Description |
|---------|-------------|
| Institute Management | Create, activate, suspend institutes |
| Plan Management | SaaS subscription plans (student_limit, batch_limit, duration) |
| Subscription Tracking | View all active / expired subscriptions globally |
| Revenue Reporting | Total SaaS revenue across all institutes |
| User Impersonation | Log in as any institute admin for debugging |
| System Health Dashboard | Total institutes, active users, revenue metrics |
| Global Activity Logs | Audit trail for all critical actions across all tenants |

---

### 4.2 Institute Admin Panel — Academic Mode

| Module | Features |
|--------|---------|
| **Dashboard** | Enrollment stats, attendance %, fee collection, upcoming exams |
| **Hierarchy Management** | Course → Class → Subject → Batch |
| **Student Management** | Enroll, profiles, roll numbers, academic history |
| **Teacher Management** | Assign subjects, manage schedule, payroll |
| **Attendance** | Mark by class & subject, batch-level daily attendance |
| **Exam & Results** | Create exams, enter marks, generate grade reports |
| **Fee Management** | Create fee plans, collect fees, generate invoices |
| **Reporting** | Enrollment trends, defaulters list, monthly income |
| **Timetable** | Weekly schedule per class/batch |
| **Notice Board** | Post announcements to students and teachers |

---

### 4.3 Institute Admin Panel — Sports Mode

| Module | Features |
|--------|---------|
| **Dashboard** | Active players, training schedule, upcoming events, fee collection |
| **Hierarchy Management** | Sport Type → Skill Level (Basic/Advanced) → Batch/Session |
| **Player Management** | Register players, skill assessment, performance history |
| **Coach Management** | Assign sports, manage schedule, specializations |
| **Training Attendance** | Mark daily practice attendance per batch |
| **Sports Events** | Create tournaments, track participants, record results & ranks |
| **Performance Tracking** | Log session stats, skill progression, match records |
| **Fee Management** | Sport-level fees (Basic/Advanced), collect, invoice |
| **Reporting** | Player progress, revenue, event summaries |
| **Kit & Equipment** | Assign equipment to players (optional module) |

---

### 4.4 Teacher Panel — Academic Mode

| Feature | Description |
|---------|-------------|
| My Classes & Batches | View assigned classes only |
| Mark Attendance | Daily attendance for assigned batch |
| Exam Management | Create exams, enter marks for assigned subjects |
| Student Performance | View per-student academic progress |
| Notice Board | Read admin announcements |
| Timetable | View personal schedule |

---

### 4.5 Coach Panel — Sports Mode

| Feature | Description |
|---------|-------------|
| My Batches / Sessions | View assigned training batches only |
| Mark Attendance | Daily practice attendance per session |
| Player Performance | Log performance metrics per player per session |
| Event Participation | Register players for upcoming sports events |
| Squad Management | Set team composition for tournaments |
| Notice Board | Read admin announcements |

---

### 4.6 Student Panel — Academic Mode

| Feature | Description |
|---------|-------------|
| My Dashboard | Attendance %, pending fees, upcoming exams |
| Attendance Report | Personal monthly attendance view |
| Exam Results | View own marks and grade report |
| Fee Status | View pending / paid fee records |
| Invoice Download | Download PDF receipt for paid fees |
| Notice Board | Read announcements |

---

### 4.7 Student Panel — Sports Mode

| Feature | Description |
|---------|-------------|
| My Dashboard | Training sessions attended, upcoming events, pending fees |
| Training Attendance | Personal attendance record |
| Performance Log | Personal stats per session / event |
| Events | Registered events, results, rank |
| Fee Status | View training fee records (Basic / Advanced) |
| Invoice Download | Download PDF receipt for paid fees |
| Notice Board | Read announcements |

---

## 5. Database Architecture

### 5.1 Core Multi-Tenant Principle

> `institute_id` MUST appear in every single table that belongs to an institute.
> This is the primary tenant isolation key.

### 5.2 Table Structure Overview

#### `institutes` (Root Tenant Table)
```
id                  → Primary key
name                → Institute name
email               → Unique email
phone
address
logo
type                → ENUM('academic', 'sports')   ← THE CRITICAL COLUMN
status              → ENUM('active', 'inactive')
subscription_expires_at
timestamps
softDeletes
```

#### `users` (Global Auth Table)
```
id
institute_id        → FK to institutes (nullable for platform admin)
name
email               → UNIQUE per institute: composite unique(institute_id, email)
username
phone
avatar
password
is_active           → Boolean
timestamps
```
> ⚠️ CRITICAL: email uniqueness must be per-institute, NOT globally unique.
> Use: `unique(['institute_id', 'email'])` NOT `unique('email')`.

---

### 5.3 Academic Mode Tables

#### `courses` (Level 1 - Academic)
```
id
institute_id        → FK
name                → e.g., "Science Stream", "GPSC Preparation"
code                → e.g., "SCI", "GPSC"
description
is_active
timestamps, softDeletes
unique: [institute_id, name]
```

#### `classes` (Level 2 - Academic)
```
id
institute_id        → FK
course_id           → FK to courses
name                → e.g., "Standard 10", "Class A"
type                → ENUM('academic', 'sports')
description
is_active
timestamps, softDeletes
```

#### `subjects` (Level 3 - Academic)
```
id
institute_id        → FK
class_id            → FK to classes
name                → e.g., "Mathematics", "Physics"
code
type                → 'academic'
description
is_active
timestamps, softDeletes
```

#### `batches` (Level 4 - Shared but Context-Typed)
```
id
institute_id        → FK
class_id            → FK to classes
subject_id          → FK to subjects (nullable for sports)
name                → e.g., "Morning Batch", "Afternoon Group"
start_time
end_time
capacity
is_active
timestamps, softDeletes
index: [institute_id, class_id]
```

---

### 5.4 Sports Mode Tables

#### `sport_types` (Level 1 - Sports)
```
id
institute_id        → FK
name                → e.g., "Cricket", "Badminton", "Football"
description
is_active
timestamps, softDeletes
unique: [institute_id, name]
```

#### `skill_levels` (Level 2 - Sports)
```
id
institute_id        → FK
sport_type_id       → FK to sport_types
name                → e.g., "Basic", "Intermediate", "Advanced"
description
is_active
timestamps
```

#### `training_sessions` / `batches` (Level 3 - Sports)
> Note: Batches table is shared, but for sports, `class_id` is replaced by linking
> through `sport_type_id` on the sports side (via polymorphic or a dedicated column).
```
id
institute_id        → FK
sport_type_id       → FK to sport_types (null for academic)
skill_level_id      → FK to skill_levels (null for academic)
name                → e.g., "Morning Practice", "Evening Training"
start_time
end_time
capacity
days_of_week        → JSON: ["Mon", "Wed", "Fri"]
is_active
timestamps, softDeletes
```

---

### 5.5 Shared Tables (Both Modes)

#### `students`
```
id
institute_id        → FK
user_id             → FK to users
batch_id            → FK to batches
roll_number
birth_date
parent_name
parent_phone
address
photo
admission_date
is_active
timestamps, softDeletes
unique: [institute_id, roll_number, deleted_at]  ← Fix for soft delete collision
```

#### `teachers` / `coaches` → Same Table, Different Role
```
id
institute_id        → FK
user_id             → FK to users
employee_id
qualification
specialization      → e.g., "Mathematics" OR "Cricket Coaching"
joining_date
salary
is_active
timestamps, softDeletes
```

#### `attendances`
```
id
institute_id        → FK
student_id          → FK
batch_id            → FK
attendance_date     → Date
status              → ENUM('present', 'absent', 'late', 'excused')
remarks
marked_by           → FK to users
timestamps
unique: [student_id, batch_id, attendance_date]  ← Prevent double entries
index: [institute_id, batch_id, attendance_date]
```

#### `fees`
```
id
institute_id        → FK
student_id          → FK
fee_plan_id         → FK (nullable)
fee_type            → ENUM('monthly','quarterly','half_yearly','annual','registration','other')
sport_level         → ENUM('basic','advanced') nullable  ← Sports only
total_amount        → DECIMAL(10,2)
paid_amount         → DECIMAL(10,2) default 0
discount            → DECIMAL(10,2) default 0
late_fee            → DECIMAL(10,2) default 0
due_date            → Date
status              → ENUM('pending','partial','paid','overdue')
remarks
timestamps
index: [institute_id, student_id]
index: [status, due_date]
```

#### `fee_payments`
```
id
fee_id              → FK
amount              → DECIMAL(10,2)
payment_method      → ENUM('cash','bank_transfer','card','cheque','upi')
transaction_id      → Nullable
notes               → Nullable
paid_at             → Timestamp
received_by         → FK to users
timestamps
index: fee_id, paid_at
```

#### `invoices`
```
id
institute_id        → FK
student_id          → FK
fee_id              → FK
invoice_number      → Unique  e.g., INV-3-000042
invoice_date        → Date
amount              → DECIMAL(10,2)
pdf_path            → Nullable
timestamps
index: [institute_id, student_id]
```

---

### 5.6 Sports-Exclusive Tables

#### `sports_events`
```
id
institute_id        → FK
sport_type_id       → FK to sport_types
coach_id            → FK to teachers/coaches
title
description
event_date          → DateTime
location
status              → ENUM('upcoming','ongoing','completed','cancelled')
timestamps, softDeletes
```

#### `event_participants`
```
id
sports_event_id     → FK
student_id          → FK
participation_status → ENUM('registered','participated','absent')
rank                → Nullable
score               → Nullable DECIMAL
notes               → Text nullable
metadata            → JSON  ← Flexible sport-specific stats
timestamps
unique: [sports_event_id, student_id]
```

---

### 5.7 Academic-Exclusive Tables

#### `exams`
```
id
institute_id        → FK
class_id            → FK
subject_id          → FK
name                → e.g., "Term 1 Exam", "Unit Test 2"
exam_date           → Date
max_marks           → Integer
pass_marks          → Integer
timestamps
```

#### `exam_results`
```
id
exam_id             → FK
student_id          → FK
marks_obtained      → DECIMAL
grade               → Varchar nullable
remarks             → Text nullable
timestamps
unique: [exam_id, student_id]
```

---

## 6. Role & Permission Structure

### 6.1 Role Definitions

| Role Slug | Context | Scope |
|-----------|---------|-------|
| `platform-admin` | Platform Admin panel | Global (no institute_id) |
| `institute-admin` | Institute Admin panel | Per institute |
| `academic-teacher` | Academic Staff panel | Per institute (academic only) |
| `sports-coach` | Sports Staff panel | Per institute (sports only) |
| `academic-student` | Academic Student panel | Per institute (academic only) |
| `sports-student` | Sports Student panel | Per institute (sports only) |

### 6.2 Spatie Teams Configuration

> You MUST enable teams in Spatie (`config/permission.php` → `'teams' => true`).
> Map `team_id` = `institute_id`.

This ensures that:
- Role "Admin" in Institute A ≠ Role "Admin" in Institute B
- Permissions set by Institute A's admin do not bleed into Institute B

```php
// When assigning a role, always scope to the team:
setPermissionsTeamId($institute_id);
$user->assignRole('academic-teacher');
```

### 6.3 Permission Matrix

#### Platform Admin Permissions
```
institutes.view         institutes.create       institutes.update
institutes.delete       plans.manage            subscriptions.view
users.impersonate       reports.global
```

#### Institute Admin — Academic
```
students.manage         teachers.manage         courses.manage
classes.manage          subjects.manage         batches.manage
attendance.manage       fees.manage             invoices.view
exams.manage            results.manage          reports.view
```

#### Institute Admin — Sports
```
students.manage         coaches.manage          sport-types.manage
skill-levels.manage     batches.manage          attendance.manage
fees.manage             invoices.view           events.manage
performance.manage      reports.view
```

#### Academic Teacher
```
attendance.mark         exams.mark              students.view
results.view            timetable.view
```

#### Sports Coach
```
attendance.mark         performance.log         events.view
players.view            squads.manage
```

#### Academic Student
```
attendance.view         results.view            fees.view
invoices.download       notices.view
```

#### Sports Student
```
attendance.view         performance.view        events.view
fees.view               invoices.download       notices.view
```

---

## 7. Dynamic Module Loading

### 7.1 How Module Loading Works

Every panel checks the `institute.type` at the **layout level** and **middleware level** to:
1. Render the correct sidebar navigation
2. Resolve the correct set of available routes
3. Use type-specific Blade views

### 7.2 InstituteContext Service Class

A centralized `InstituteContextService` is injected via Laravel's Service Container into every controller, middleware, and Blade view.

```
InstituteContextService:
  - getType()           → returns 'academic' or 'sports'
  - isAcademic()        → bool
  - isSports()          → bool
  - getModules()        → array of available module keys
  - getInstitute()      → the full Institute model
  - getDashboardView()  → 'institute.academic.dashboard' | 'institute.sports.dashboard'
```

### 7.3 Module Registry

A configuration file (`config/institute_modules.php`) defines which modules belong to which type:

```
academic:
  - dashboard
  - students
  - teachers
  - courses
  - classes
  - subjects
  - batches
  - attendance
  - exams
  - results
  - fees
  - invoices
  - reports
  - timetable
  - notices

sports:
  - dashboard
  - players
  - coaches
  - sport-types
  - skill-levels
  - training-sessions
  - attendance
  - events
  - performance
  - fees
  - invoices
  - reports
  - notices
```

### 7.4 View Resolution

Blade views are organized by type:

```
resources/views/
  institute/
    academic/
      dashboard.blade.php
      students/
        index.blade.php
        show.blade.php
      teachers/
      exams/
      ...
    sports/
      dashboard.blade.php
      players/
        index.blade.php
        show.blade.php
      coaches/
      events/
      performance/
      ...
    shared/
      fees/
        index.blade.php
        show.blade.php
      invoices/
      notices/
      ...
```

---

## 8. Middleware Strategy

### 8.1 Middleware Stack

```
EnsureInstituteActive        → Block suspended institutes
EnsureSubscriptionValid      → Block expired subscriptions
InjectInstituteContext       → Load and share institute.type into request
EnsureAcademicMode           → Return 403 if institute is sports type
EnsureSportsMode             → Return 403 if institute is academic type
CheckPlanLimits              → Enforce student_limit and batch_limit
```

### 8.2 Route Group Structure

```php
// Platform Admin Routes (no institute type check)
Route::prefix('admin')->middleware(['auth', 'role:platform-admin'])->group(...)

// Institute Admin Routes — Type protected
Route::prefix('institute')->middleware(['auth', 'inject-institute-context'])->group(function() {

    // Common routes for both types
    Route::resource('fees', FeeController::class);
    Route::resource('invoices', InvoiceController::class);

    // Academic-only routes
    Route::middleware('ensure-academic-mode')->group(function() {
        Route::resource('courses', CourseController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('exams', ExamController::class);
        Route::resource('results', ResultController::class);
    });

    // Sports-only routes
    Route::middleware('ensure-sports-mode')->group(function() {
        Route::resource('sport-types', SportTypeController::class);
        Route::resource('events', SportsEventController::class);
        Route::resource('performance', PerformanceController::class);
    });
});
```

### 8.3 Middleware Logic: `InjectInstituteContext`

```
1. Get auth()->user()->institute_id
2. Load Institute from Cache (Redis/DB) → cache('institute_' . id)
3. If institute.status != 'active' → redirect to suspended page
4. If institute.subscription_expires_at < now() → redirect to expired page
5. Share $institute and $institute->type into:
   - Request attributes
   - View::share() for Blade
   - Session (for fast access within request chain)
6. Set Spatie team: setPermissionsTeamId($institute->id)
```

### 8.4 Middleware Logic: `EnsureAcademicMode` & `EnsureSportsMode`

```
EnsureAcademicMode:
  if (request()->instituteType !== 'academic')
     → abort(403, 'This module is not available for Sports institutes.')

EnsureSportsMode:
  if (request()->instituteType !== 'sports')
     → abort(403, 'This module is not available for Academic institutes.')
```

---

## 9. Folder & Module Structure

```
app/
├── Console/
│   └── Commands/
│       ├── UpdateOverdueFeesCommand.php       ← Cron: update fee statuses daily
│       └── ExpireSubscriptionsCommand.php     ← Cron: flag expired subs
│
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                             ← Platform Admin Panel
│   │   │   ├── DashboardController.php
│   │   │   ├── InstituteController.php
│   │   │   ├── PlanController.php
│   │   │   └── SubscriptionController.php
│   │   │
│   │   ├── Institute/                         ← Institute Admin Panel
│   │   │   ├── Shared/                        ← Works for BOTH types
│   │   │   │   ├── DashboardController.php    ← Resolves academic vs sports view
│   │   │   │   ├── StudentController.php
│   │   │   │   ├── FeeController.php
│   │   │   │   ├── FeePaymentController.php
│   │   │   │   ├── InvoiceController.php
│   │   │   │   ├── BatchController.php
│   │   │   │   ├── AttendanceController.php
│   │   │   │   └── NoticeController.php
│   │   │   │
│   │   │   ├── Academic/                      ← Academic-only controllers
│   │   │   │   ├── CourseController.php
│   │   │   │   ├── ClassController.php
│   │   │   │   ├── SubjectController.php
│   │   │   │   ├── TeacherController.php
│   │   │   │   ├── ExamController.php
│   │   │   │   ├── ResultController.php
│   │   │   │   └── TimetableController.php
│   │   │   │
│   │   │   └── Sports/                        ← Sports-only controllers
│   │   │       ├── SportTypeController.php
│   │   │       ├── SkillLevelController.php
│   │   │       ├── CoachController.php
│   │   │       ├── SportsEventController.php
│   │   │       ├── PerformanceController.php
│   │   │       └── SquadController.php
│   │   │
│   │   ├── Staff/                             ← Teacher / Coach Panel
│   │   │   ├── Shared/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── AttendanceController.php
│   │   │   ├── Academic/
│   │   │   │   ├── TeacherDashboardController.php
│   │   │   │   └── ExamController.php
│   │   │   └── Sports/
│   │   │       ├── CoachDashboardController.php
│   │   │       └── PerformanceController.php
│   │   │
│   │   └── Student/                           ← Student Panel
│   │       ├── Shared/
│   │       │   ├── DashboardController.php
│   │       │   ├── FeeController.php
│   │       │   ├── AttendanceController.php
│   │       │   └── InvoiceController.php
│   │       ├── Academic/
│   │       │   └── ResultController.php
│   │       └── Sports/
│   │           ├── PerformanceController.php
│   │           └── EventController.php
│   │
│   ├── Middleware/
│   │   ├── EnsureInstituteActive.php
│   │   ├── EnsureSubscriptionValid.php
│   │   ├── InjectInstituteContext.php       ← THE CORE MIDDLEWARE
│   │   ├── EnsureAcademicMode.php
│   │   ├── EnsureSportsMode.php
│   │   └── CheckPlanLimits.php
│   │
│   └── Requests/
│       ├── Academic/
│       └── Sports/
│
├── Models/
│   ├── Institute.php
│   ├── User.php
│   ├── Student.php
│   ├── Batch.php
│   ├── Attendance.php
│   ├── Fee.php
│   ├── FeePayment.php
│   ├── Invoice.php
│   ├── FeePlan.php
│   ├── Notice.php
│   │
│   ├── Academic/                            ← Academic-only models
│   │   ├── Course.php
│   │   ├── SchoolClass.php
│   │   ├── Subject.php
│   │   ├── Teacher.php
│   │   ├── Exam.php
│   │   └── ExamResult.php
│   │
│   └── Sports/                              ← Sports-only models
│       ├── SportType.php
│       ├── SkillLevel.php
│       ├── Coach.php
│       ├── SportsEvent.php
│       ├── EventParticipant.php
│       └── PerformanceLog.php
│
├── Services/
│   ├── InstituteContextService.php          ← THE CORE SERVICE
│   ├── FeeService.php
│   ├── AttendanceService.php
│   ├── InvoiceService.php
│   ├── Academic/
│   │   ├── CourseService.php
│   │   └── ExamService.php
│   └── Sports/
│       ├── PerformanceService.php
│       └── EventService.php
│
├── Traits/
│   └── MultiTenant.php                      ← Global scope: where institute_id = ?
│
└── Providers/
    ├── InstituteServiceProvider.php         ← Bind InstituteContextService
    └── AuthServiceProvider.php

config/
├── institute_modules.php                    ← Module registry per type
└── permission.php                           ← Spatie config with teams enabled

resources/views/
├── admin/                                   ← Platform admin views
├── institute/
│   ├── academic/                            ← Academic-only views
│   ├── sports/                              ← Sports-only views
│   └── shared/                             ← Fee, invoice, attendance views
├── staff/
│   ├── academic/
│   └── sports/
└── student/
    ├── academic/
    └── sports/

routes/
├── web.php                                  ← Base route file
├── admin.php                                ← Platform admin routes
├── institute.php                            ← Institute admin routes (shared)
├── institute-academic.php                   ← Academic-only institute routes
├── institute-sports.php                     ← Sports-only institute routes
├── staff.php                                ← Staff shared routes
└── student.php                              ← Student routes (shared + typed)
```

---

## 10. Preventing Academic ↔ Sports Logic Mixing

### 10.1 Layer-by-Layer Enforcement

| Layer | Prevention Mechanism |
|-------|---------------------|
| **Routes** | Academic routes wrapped in `ensure-academic-mode` middleware |
| **Controllers** | Academic controllers under `Institute/Academic/` only |
| **Services** | Academic services only import Academic models; Sports services only import Sports models |
| **Models** | Academic models (Course, Exam) have no relationship to Sports models (SportType, Event) |
| **Views** | Blade views are physically separate directories |
| **DB Queries** | `MultiTenant` trait + type check prevents cross-type queries |
| **Middleware** | `EnsureAcademicMode` / `EnsureSportsMode` blocks wrong-type requests at HTTP level |

### 10.2 Sports Data Cannot Appear in Academic Context

```
Academic Controller loads students:
  Student::where('institute_id', $currentInstituteId)
         ->whereHas('batch.class', fn($q) => $q->where('type', 'academic'))
         ->get()

Sports Controller loads players:
  Student::where('institute_id', $currentInstituteId)
         ->whereHas('batch', fn($q) => $q->whereNotNull('sport_type_id'))
         ->get()
```

### 10.3 Fail-Safe: Model Observers

Institute-level Model Observers fire before every save/update.
If a Course (academic) is being saved with an institute that is of type `sports`, the observer throws a `DomainException`.

---

## 11. Scalability Design

### 11.1 Target Scale

| Metric | Target |
|--------|--------|
| Institutes | 500+ |
| Users per Institute | 1 – 5,000 |
| Total Platform Users | 50,000 – 500,000 |
| Concurrent Active Sessions | 2,000 – 10,000 |

### 11.2 Database Indexing Strategy

Every table with `institute_id` must have:
- `INDEX (institute_id)` — Always first column in compound indexes
- `INDEX (institute_id, status)` — For filtered dashboard queries
- `INDEX (institute_id, created_at)` — For time-series reports

### 11.3 Caching Strategy

| What | Cache Target | TTL |
|------|-------------|-----|
| Institute record | Redis | 1 hour |
| Current plan limits | Redis | 30 min |
| Student count per institute | Redis | 5 min |
| Batch list per institute | Redis | 15 min |
| Fee dashboard stats | Redis | 10 min |

All cache keys are institute-namespaced:
```
key format: institute_{id}_students_count
            institute_{id}_active_feeplans
```

### 11.4 Queue Jobs (Async Processing)

| Job | Description |
|-----|------------|
| `GenerateInvoicePdfJob` | Offload PDF generation from request thread |
| `SendFeeReminderJob` | Email/SMS reminders for overdue fees |
| `UpdateOverdueStatusJob` | Batch SQL update once a night |
| `ExportStudentsJob` | Large CSV export runs in background |

### 11.5 Scheduled Commands (Cron)

```
Daily at midnight:
  - php artisan fees:update-overdue        → Mark overdue fees
  - php artisan fees:apply-late-charges    → Calculate and apply late fees
  - php artisan subscriptions:check-expiry → Flag expired institute subs

Monthly on 1st:
  - php artisan reports:generate-monthly   → Pre-generate monthly report snapshots
```

### 11.6 Future Horizontal Scaling Path

| Stage | Setup |
|-------|-------|
| MVP (0–50 institutes) | Single server, MySQL, Redis |
| Growth (50–200 institutes) | Read Replicas for heavy reports |
| Scale (200–1000 institutes) | Horizontal app servers, centralized Redis Cluster |
| Enterprise (1000+ institutes) | Consider database sharding by institute_id ranges |

---

## 12. Data Flow Diagrams

### 12.1 Academic Institute — Student Fee Journey

```
Institute Admin (Academic)
  → Creates Fee Plan (monthly, ₹2,500) for Class 10
  → Assigns fee to Student Rahul

System:
  → Stores in fees table: status = 'pending', due_date = 15 Mar

Student Rahul:
  → Logs in → dashboard shows "Pending: ₹2,500"

Institute Admin:
  → Collects payment (Cash ₹2,500)
  → Records FeePayment → paid_amount += 2500 → status = 'paid'
  → Invoice auto-generated: INV-4-000037

Student:
  → Status changes to PAID ✅
  → Can download Invoice PDF
```

### 12.2 Sports Academy — Player Event Journey

```
Coach (Sports):
  → Creates Event: "District Cricket Tournament" on 5 April
  → Registers 15 players from Advanced Batch

System:
  → Creates event_participants records: status = 'registered'

Event Day:
  → Coach marks actual participants
  → Records: Rank 1 → player A, Rank 2 → player B

Institute Admin:
  → Views Sports Event Report
  → Sees rank table, missing players from squad

Student (Sports):
  → Logs in → sees "My Events" → District Cricket → Result: Rank 1 🏆
```

### 12.3 Module Switching by Institute Type

```
User authenticates → institute.type = 'sports'
  │
  ▼
InjectInstituteContext Middleware runs
  ├── session('institute_type') = 'sports'
  ├── View::share('modules', config('institute_modules.sports'))
  └── View::share('instituteType', 'sports')

Institute Admin visits /institute/dashboard
  │
  └── DashboardController::index()
          → if isSports()  → return view('institute.sports.dashboard')
          → if isAcademic() → return view('institute.academic.dashboard')

Institute Admin visits /institute/courses (academic route)
  │
  └── EnsureAcademicMode middleware fires
          → institute is 'sports'
          → abort(403, "Academic module not available for Sports institutes")
```

---

## 13. Client Presentation Summary

### What You Are Building

A **professional, unified SaaS platform** that manages both Academic institutes and Sports academies — under a single codebase — while presenting two completely separate operational experiences to users.

### What Makes This System Professional

| Feature | Standard System | This System |
|---------|----------------|-------------|
| Multi-tenant | Often missing | ✅ Every table has `institute_id` |
| Type separation | Mixed modules | ✅ Academic and Sports are fully isolated |
| Role Management | Basic roles | ✅ Spatie Permissions with team isolation |
| Scalability | Not designed | ✅ Indexed for 500+ tenants, async queues |
| Security | Generic | ✅ Middleware-enforced type guards |
| Invoice System | Not present | ✅ Auto-generated PDF invoices |
| Cron Automation | Not present | ✅ Automated overdue, late fees, subscriptions |

### The Three Core Promises to Clients

1. **"Your institute type never mixes with another."**
   A Sports Academy admin will never see Class, Subject, or Exam modules. An Academic Institute admin will never see Sports Events or Skill Levels.

2. **"Your data is always isolated."**
   Every query is always scoped to your `institute_id`. Another institute cannot see your students, fees, or teachers — even if they are on the same server.

3. **"The system grows with you."**
   From 10 students to 5,000 students — the system is designed with Redis caching, indexed queries, and async job processing to remain fast at scale.

---

*Document Version: 1.0*
*Prepared: February 2026*
*Based on: Laravel 12.x + Spatie Permission + MySQL + Redis*
*Architecture principle: One backend. Two worlds. Zero mixing.*
