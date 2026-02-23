# Unified Institute Management System (UIMS) - Project Flow & Architecture

This document describes the workflow, architecture, and user journeys of the unified platform designed to manage both **Academic Institutes** (schools/colleges) and **Sports Academies** within a single, multi-tenant Laravel backend.

---

## 🏗️ 1. Core Architecture & Multi-Tenancy

### 1.1 Multi-Tenancy Architecture
- **Approach:** Single Database, Row-Level Isolation.
- **Implementation:** The `MultiTenant` trait is applied to almost all models (except Super Admin models like `School`, `Plan`, `SchoolSubscription`). It automatically appends `school_id` to every query and injection using Eloquent Global Scopes.
- **Result:** Data from Institute A is invisible and physically separated from Institute B at the query level.

### 1.2 The "Type-Aware" Unified Engine
Instead of two separate codebases, the system uses a **Context Engine**:
- Each `School` (Institute) has a `type` column (`academic` or `sports`).
- **`InstituteContextService`** safely determines the institute's type.
- **`InjectInstituteContext` Middleware** globally shares the `$instituteType` and `$isSports` boolean to all Blade views.
- **Dynamic UIs:** Based on `$isSports`, a single controller/view (e.g., Student Dashboard or Teacher Sidebar) dynamically morphs its terminology:
  - *Academic mode:* "Student", "Teacher", "Classes", "Exams", "Attendance".
  - *Sports mode:* "Player", "Coach", "Batches/Levels", "Performance", "Training".

### 1.3 Strict Module Separation
To prevent data contamination, two middlewares enforce strict access:
- `EnsureAcademicMode` (protects `/school/academic/*` routes like Exams, Subjects, Syllabuses)
- `EnsureSportsMode` (protects `/school/sports/*` routes like Sport Types, Skill Levels, Performance Logs)
- Intruding into the wrong module throws a custom `403 Forbidden` error.

---

## 👩‍💻 2. User Roles & Workflows

The system uses **Spatie Laravel Permission** to manage Role-Based Access Control (RBAC). There are four primary roles/portals:

### ⚙️ Workflow A: The Super Admin (Platform Owner)
*Accesses the master `/admin` portal. Not bound to any specific `school_id`.*

1. **Platform Configuration:** Manages Subscription *Plans* (Basic, Pro, Enterprise).
2. **Institute Onboarding:** 
   - Clicks "Add New School".
   - Fills out standard info (Name, Admin Details) AND selects the **Institute Type** (`Academic` or `Sports`).
   - Assigns a subscription plan.
3. **Auto-Provisioning:** 
   - The backend creates the Institute (`School`).
   - Creates the local `School Admin` user.
   - Activates the invoice/subscription.
   - Automatically seeds the system: creates a default `General Academic` class (if academic) OR a default `General Training` sport type (if sports).
4. **Monitoring:** Views total institutes, active subscriptions, and aggregated statistics (🎓 Academic count vs 🏆 Sports count).

### 🏢 Workflow B: The Institute Admin
*Accesses the `/school` portal. Isolated to their `school_id`.*

1. **Initial Setup:** Configures basic info, fee structures, and branding.
2. **Contextual Configuration:**
   - **Academic Admin:** Creates *Courses*, *Classes*, *Subjects*.
   - **Sports Admin:** Creates *Sport Types* (e.g., Tennis, Swimming) and *Skill Levels* (e.g., Beginner, Advanced).
3. **HR & Roster Management:** 
   - Adds Teachers/Coaches.
   - Adds Students/Players.
   - Assigns students to *Batches* (Time slots associated with a Class or Sport).
4. **Financial Operations:** 
   - Assigns *Fee Plans* to students.
   - Collects payments (Cash, Bank, Online).
   - Views the Financial Dashboard, tracks Overdue accounts, and views invoices.
5. **General Operations:** Can schedule *Events* (Competitions, Seminars) and track cross-institute reports.

### 👨‍🏫 Workflow C: The Teacher / Coach
*Accesses the `/teacher` portal. Bound to specific Batches/Classes.*

1. **Daily Dashboard:** Logs in to view their dynamically labeled dashboard (e.g., "🏆 Coach Dashboard" vs "🎓 Teacher Dashboard").
2. **Attendance / Training:** 
   - Views their assigned batches for the day.
   - Marks attendance or "Training Sessions".
3. **Performance Tracking:**
   - **Academic:** Enters Exam Results and uploads Study Materials.
   - **Sports:** Logs *Performance Sessions* (drills, matches, fitness metrics) with dynamic JSON scoring for individual players.
4. **Events:** Views assigned upcoming events or matches.

### 🎒 Workflow D: The Student / Player
*Accesses the `/student` portal. The end-user of the platform.*

1. **Dashboard Overview:** Views their personal financial balance, recent attendance rate, and upcoming events.
2. **Type-Specific Engagement:**
   - **Academic:** Views Class Timetable, downloads Study Resources, views Exam Results.
   - **Sports:** Views Player Profile, checks "Training Fees", views past Performance Logs (scores/drills), and sees matched scheduled Events.
3. **Financials:** Checks their pending dues and views their *Financial Ledger* (Debit/Credit history).

---

## 🔄 3. Automated Background Processes (Cron Jobs)

The system relies on Laravel's Task Scheduler to automate routine administrative tasks:

1. **`fees:update-overdue` (Runs Daily at Midnight)**
   - Scans all unpaid/partially paid fee invoices where the `due_date` has passed.
   - Updates their status to `overdue`.
   - Optionally applies late tracking penalties (if configured).
2. **`subscriptions:send-reminders` (Runs Daily at 08:00 AM)**
   - Scans all Institutes (`Schools`) whose SaaS subscription expires within 7 days.
   - Dispatches email alerts to the Super Admin and the Institute Admin to prevent service interruption.

---

## 🗄️ 4. Data Flow Example: Creating a Sports Performance Log

1. **Coach logs in:** Routes to `/teacher/dashboard`.
2. **Validation:** Middleware verifies they are a `teacher/coach` roll.
3. **View Rendering:** `InjectInstituteContext` detects their Institute is `type = sports`. It renders `teacher.sports.sidebar` and sports terminology on the dashboard.
4. **Action:** Coach clicks "Log Performance" -> hits `PerformanceController@create`.
5. **Form:** System loads the Coach's assigned Batches & Players.
6. **Submission:** Data is saved to `performance_logs` table (includes attributes like speed, agility stored dynamically in a `metrics` JSON column). `MultiTenant` trait silently appends the `school_id`.
7. **Player View:** The associated player logs in. The `recentPerformance` dashboard widget queries logs matching their `student_id` and renders their latest training scores immediately.

---

## 🎨 5. Tech Stack & UX Principles
- **Backend:** Laravel 12.x Core, MySQL (Relational mappings + JSON casting).
- **Frontend UI:** Bootstrap 5, heavily customized with modern CSS techniques (glassmorphism, soft shadows, dynamic gradients).
- **Icons:** Bootstrap Icons.
- **Charts:** Chart.js (used in dashboards for financial/attendance trends).
- **Security:** CSRF Protection, Strict Route Binding, `school_id` scoping injection.

---
*Generated by UIMS Context Engine.*
