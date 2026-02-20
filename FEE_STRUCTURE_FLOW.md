# 💰 Fee Structure, Flow & Invoice System
## Classes Management System — Complete Documentation

> **This document explains how the fee system is designed, how it flows from creation to invoice,
> how discounts work, what each fee type means, and what is currently in the code vs. what
> needs to be built to support your desired structure.**

---

## 📌 TABLE OF CONTENTS

1. [Fee Categories (What You Want)](#1-fee-categories)
2. [Fee Types — Duration Based](#2-fee-types--duration-based)
3. [Fee Types — Category Based (Basic vs Advanced / Sports)](#3-fee-types--category-based)
4. [How a Fee is Created (School Admin Flow)](#4-how-a-fee-is-created--school-admin-side)
5. [Discount System](#5-discount-system)
6. [Fee Status Life Cycle](#6-fee-status-life-cycle)
7. [What Happens When a Payment is Made](#7-what-happens-when-payment-is-made)
8. [Pending Fee Flow](#8-pending-fee-flow)
9. [Completed Fee Flow + Auto Invoice](#9-completed-fee-flow--auto-invoice)
10. [Invoice System — How It Works](#10-invoice-system--how-it-works)
11. [How the Student Sees All This](#11-student-view-of-fees)
12. [Database Tables Overview](#12-database-tables-overview)
13. [Full End-to-End Flow Diagram](#13-full-end-to-end-flow-diagram)
14. [What Is Currently Missing / Needs to Be Built](#14-what-is-currently-missing--needs-to-be-built)

---

## 1. Fee Categories

The system should support **two dimensions** for each fee assigned to a student:

### Dimension A — Duration (How Often)

| Fee Type | Current Code Value | Meaning |
|-----------|-------------------|---------|
| Monthly | `monthly` | Charged every month |
| Quarterly | `quarterly` | Charged every 3 months |
| Half-Yearly (6 Month) | `half_yearly` ⚠️ *missing* | Charged every 6 months |
| Annual / Yearly | `annual` | Charged once per year |
| Registration | `registration` | One-time admission fee |
| Other | `other` | Custom, one-time fees |

> **Current Status:** The `fees` table stores this in the `fee_type` column (a plain string).
> The code currently only handles: `monthly`, `quarterly`, `annual`, `registration`, `other`.
> **`half_yearly` is NOT defined yet** — needs to be added.

### Dimension B — Sports Learning Level (Skill Stage)

When a student joins the **sports program**, they are placed at one of two levels based on their skill:

| Level | Who It Applies To | Fee Range |
|-------|-------------------|-----------|
| **Basic** | Beginner — just starting to learn the sport | Lower fee |
| **Advanced** | Has mastered basics, doing competitive/intensive training | Higher fee |

> This is not about academic vs sports — it is specifically about **how far a student has
> progressed in learning a sport**. Basic = still learning fundamentals. Advanced = competition-ready.
>
> **Current Status:** The `fees` table does NOT have a `sport_level` column yet.
> A new column `sport_level` with values `'basic'` or `'advanced'` needs to be added.

---

## 2. Fee Types — Duration Based

### 📅 Monthly Fee
- **Amount:** Set per student based on their sport level
- **Due date:** Typically set to the 5th–10th of each month
- **How it works:** School admin creates a new monthly fee record for the student each month
- **Example (Sports Basic):** ₹800/month | **Example (Sports Advanced):** ₹1,800/month

### 📅 Quarterly Fee
- **Amount:** 3 × monthly rate (with optional discount for paying in advance)
- **Due date:** Start of each quarter (Jan, Apr, Jul, Oct)
- **Example (Sports Basic):** ₹2,200/quarter | **Example (Sports Advanced):** ₹5,000/quarter

### 📅 Half-Yearly Fee (6 Months)
- **Amount:** 6 × monthly rate (with optional advance-payment discount)
- **Due date:** Start of each half-year (January, July)
- **Example (Sports Basic):** ₹4,200 for 6 months | **Example (Sports Advanced):** ₹9,500 for 6 months

### 📅 Annual / Yearly Fee
- **Amount:** Full year fee, usually at a discounted rate for committing upfront
- **Due date:** Start of academic/sports year (typically April or June)
- **Example (Sports Basic):** ₹8,000/year | **Example (Sports Advanced):** ₹18,000/year

### 🔖 Registration Fee
- **Amount:** Fixed one-time fee when a student joins the sports program
- **Due date:** Joining date
- **Example:** ₹500 one-time registration for sports

---

## 3. Sports Learning Levels — Basic vs Advanced

These two levels describe **what stage the student is at in learning the sport**.
The school admin assigns the student's level when creating a sports fee.

---

### ⚽ Basic Level — "Learning the Sport"

**Who this is for:** A student who is new to the sport or just beginning their journey.

```
What they learn at Basic Level:
  ✔ Rules and regulations of the sport
  ✔ Basic techniques (grip, stance, posture, footwork)
  ✔ Entry-level drills and beginner exercises
  ✔ General fitness and coordination training
  ✔ Group practice sessions (multiple students per coach)
  ✔ Uses shared school equipment — no personal kit needed
```

| Duration | Typical Fee |
|----------|-------------|
| Monthly | ₹800/month |
| Quarterly | ₹2,200 (3 months) |
| Half-Yearly | ₹4,200 (6 months) |
| Annual | ₹8,000 (full year) |
| Registration | ₹500 (one-time joining) |

---

### � Advanced Level — "Mastering the Sport"

**Who this is for:** A student who has completed the Basic level and is now ready for
intensive training and competition. Or a student who joins with prior experience.

```
What they train for at Advanced Level:
  ✔ Advanced techniques, tactics, and match strategy
  ✔ Competition and tournament preparation
  ✔ Small-group or one-on-one coaching (1 coach : 3 students)
  ✔ 5 sessions per week (more intensive than Basic)
  ✔ Personal/dedicated sports kit provided
  ✔ Participation in inter-school, district, or state competitions
  ✔ Performance video analysis (where available)
  ✔ Nutrition and sports fitness guidance
```

| Duration | Typical Fee |
|----------|-------------|
| Monthly | ₹1,800/month |
| Quarterly | ₹5,000 (3 months) |
| Half-Yearly | ₹9,500 (6 months) |
| Annual | ₹18,000 (full year) |
| Registration | ₹500 (one-time joining) |

---

### 📊 Basic vs Advanced — Side-by-Side Comparison

| Aspect | Basic (Beginner) | Advanced (Competitive) |
|--------|-----------------|----------------------------|
| Goal | Learn the sport | Excel and compete |
| Sessions/week | 3 | 5 |
| Coach ratio | 1:10 (group class) | 1:3 (small group/personal) |
| Equipment | Shared school equipment | Personal kit included |
| Competitions | Internal / school-level | Inter-school / district / state |
| Monthly fee | ₹800 (example) | ₹1,800 (example) |
| Who joins | Absolute beginners | Students with prior training |
| Path forward | Promote to Advanced after assessment | National-level training |

---

### 💡 How This Works in the System

Each sports fee record will have two key fields:

```
fee_type   = 'monthly' | 'quarterly' | 'half_yearly' | 'annual' | 'registration'
sport_level = 'basic'  | 'advanced'
```

**Example 1 — Arjun just joined Cricket from scratch:**
- `sport_level = 'basic'`
- `fee_type = 'monthly'`
- `total_amount = ₹800`
- First payment due March 1, 2026

**Example 2 — Sneha is competing at state level in Badminton:**
- `sport_level = 'advanced'`
- `fee_type = 'quarterly'`
- `total_amount = ₹5,000`
- Discount of ₹200 (merit scholarship)
- First payment due April 1, 2026

---

## 4. How a Fee is Created — School Admin Side

### Step-by-Step Flow

```
School Admin Login
       │
       ▼
 Sidebar → Fees → Create Fee
       │
       ▼
 Fill the Create Fee Form:
 ┌─────────────────────────────────────────────────────┐
 │ 1. Select Student (from dropdown)                   │
 │ 2. Select Fee Type (Monthly/Quarterly/6Month/Annual) │
 │ 3. Select Sport Level (Basic / Advanced)            │ ← NEEDS TO BE ADDED
 │ 4. Total Amount (₹) — auto-suggested by level       │
 │ 5. Discount (₹ or %)                                │
 │ 6. Late Fee Penalty (₹)                             │
 │ 7. Due Date (calendar picker)                       │
 │ 8. Remarks / Notes (optional)                       │
 └─────────────────────────────────────────────────────┘
       │
       ▼
  StoreFeeRequest validates the data
       │
       ▼
  FeeService::createFee() saves to `fees` table:
  ─ school_id, student_id, fee_type, sport_level
  ─ total_amount, discount, late_fee, due_date
  ─ paid_amount = 0 (default)
  ─ status = 'pending' (default)
       │
       ▼
  Fee record created ✅
  ActivityLog recorded ✅
  Redirect to Fees List
```

### What the `fees` Table Stores

| Column | Type | Example Value | Notes |
|--------|------|---------------|-------|
| `id` | integer | 42 | Auto-increment |
| `school_id` | integer | 3 | Which school |
| `student_id` | integer | 17 | Which student |
| `fee_type` | string | `quarterly` | Duration type (monthly/quarterly/half_yearly/annual) |
| `sport_level` | string | `advanced` | ⚠️ Not in DB yet — `basic` or `advanced` |
| `total_amount` | decimal | 9000.00 | Base fee amount |
| `discount` | decimal | 500.00 | Fixed discount in ₹ |
| `late_fee` | decimal | 200.00 | Penalty if paid after due date |
| `paid_amount` | decimal | 0.00 | Accumulates with each payment |
| `due_date` | date | 2026-04-01 | When the fee is due |
| `status` | enum | `pending` | pending/partial/paid/overdue |
| `remarks` | text | "Scholarship student" | Admin notes |

---

## 5. Discount System

### Types of Discounts Supported

| Discount Type | How It Works | Current Support |
|---------------|-------------|-----------------|
| **Fixed Amount (₹)** | e.g., ₹500 off | ✅ Fully supported via `discount` column |
| **Percentage (%)** | e.g., 10% off | ⚠️ Needs frontend conversion |
| **Sibling Discount** | e.g., 2nd child gets 15% off | ⚠️ Not built — manual entry only |
| **Scholarship** | Full or partial waiver | ⚠️ Not built — manual entry only |
| **Early Payment** | Pay quarterly instead of monthly | ⚠️ Structure not enforced |
| **Need-based** | Admin manually assigns ₹ discount | ✅ Supported via remarks + discount field |

### How Discount Flows Into the Fee Calculation

```
Total Amount = ₹9,000   (gross fee)
  - Discount = ₹  500   (sibling discount applied by admin)
  + Late Fee  = ₹  200   (if paid after due date)
             ─────────
Net Payable  = ₹8,700
  - Paid So Far = ₹4,000  (partial payment made last month)
             ─────────
Remaining Balance = ₹4,700
```

### The `getRemainingAmount()` Method (in `Fee` model)

```php
public function getRemainingAmount(): float
{
    return max(0, ($this->total_amount + $this->late_fee - $this->discount) - $this->paid_amount);
}
```

This is the formula used everywhere — in the fee details page, in the payment modal,
and in invoice generation.

### Where Discount Appears

1. **School Admin — Fee Create form:** Admin enters discount amount in ₹
2. **Fee Details Page (school side):** Shows discount deducted from total
3. **Student Fee Details Page:** Shows `- ₹500 Discount` clearly
4. **PDF Invoice:** Shows current discount row (hardcoded as ₹0.00 — **this is a bug**)

---

## 6. Fee Status Life Cycle

Every fee in the system goes through these states:

```
                 ┌─────────────────────────────────────────────┐
                 │                                             │
   Created  ──►  PENDING  ──► (due date passes) ──► OVERDUE   │
                 │                                    │        │
                 │ (partial payment made)             │        │
                 │                                    │        │
                 ▼                                    ▼        │
              PARTIAL ◄──────────────────────── OVERDUE        │
                 │                               + PARTIAL     │
                 │ (remaining amount paid)                     │
                 ▼                                             │
               PAID ──────────────────────────────────────────┘
                │
                ▼
          INVOICE GENERATED ✅  (auto, by FeeService)
```

### Status Definitions

| Status | Badge Color | Meaning |
|--------|-------------|---------|
| `pending` | 🔵 Grey/Blue | Fee created, no payment received, due date not passed |
| `partial` | 🟡 Yellow | At least one payment made, but balance still remains |
| `overdue` | 🔴 Red | Due date has passed and fee is still unpaid or partial |
| `paid` | 🟢 Green | Full amount collected, invoice auto-generated |

### Who Updates the Status?

The `Fee::updateStatus()` method is called **after every payment is recorded**:

```php
public function updateStatus()
{
    $remaining = $this->getRemainingAmount();

    if ($remaining <= 0) {
        $this->status = 'paid';          // ← Full payment received
    } elseif ($this->paid_amount > 0) {
        $this->status = 'partial';       // ← Some amount paid but not full
    } elseif ($this->due_date < now()->toDateString()) {
        $this->status = 'overdue';       // ← Due date passed, nothing paid
    } else {
        $this->status = 'pending';       // ← Normal pending
    }

    $this->save();
}
```

---

## 7. What Happens When Payment is Made

### Payment Recording Flow (School Admin)

```
Fee Details Page  (school.fees.show)
       │
       ▼
Admin clicks "Add Payment" button
       │
       ▼
A modal popup appears with:
 ┌──────────────────────────────────────────┐
 │  Remaining Amount: ₹4,700 (shown)       │
 │  Payment Amount: [input field]           │
 │  Payment Method:                         │
 │    • Cash                                │
 │    • Bank Transfer                       │
 │    • Card (Credit/Debit)                 │
 │    • Cheque                              │
 │  Transaction ID: [optional]              │
 │  Notes: [optional]                       │
 └──────────────────────────────────────────┘
       │
       ▼
Form submits to: POST /school/payments (school.payments.store)
       │
       ▼
StoreFeePaymentRequest validates:
 - fee_id  (must exist in fees table)
 - amount  (must be numeric, >= 1, must not exceed remaining)
 - payment_method  (cash / bank_transfer / card / cheque)
       │
       ▼
FeeService::recordPayment() runs inside DB transaction:
 Step 1: Create FeePayment record
         ─ fee_id, amount, payment_method
         ─ transaction_id, notes, paid_at = now()
         ─ received_by = auth()->id()   ← Who collected the payment

 Step 2: Increment fee.paid_amount
         fee.paid_amount += payment amount

 Step 3: Call fee.updateStatus()
         ─ If remaining = 0  → status = 'paid'
         ─ If remaining > 0  → status = 'partial'

 Step 4: If fee.status === 'paid'
         → Auto-generate Invoice! (see section 9)

 Step 5: Commit transaction / rollback on error
       │
       ▼
Redirect back to fees.show with "Payment recorded successfully" ✅
```

---

## 8. Pending Fee Flow

### How Pending Fees Are Tracked

**Route:** `GET /school/reports/pending-fees`  
**View:** `resources/views/school/reports/pending-fees.blade.php`  
**Controller:** `ReportController::pendingFees()`

The pending fees report shows all fees where `status IN ('pending', 'partial', 'overdue')`.

### What the Pending Fees Page Shows

| Column | What It Means |
|--------|--------------|
| Student (photo + name + ID) | Who owes the fee |
| Batch | Which batch they're in |
| Fee Type | Monthly / Quarterly / etc. |
| Total Amount | The original fee amount |
| Paid | How much has been collected so far |
| Pending | `total_amount - paid_amount` = balance due |
| Due Date | When it was/is due (overdue shown in red with ⚠️) |
| Action | "Collect Payment" button → goes to payment form |

### Overdue Detection Logic

In the Blade view:
```blade
@if($fee->due_date < now())
    ← shows in RED with warning icon ⚠️
@endif
```

In the `Fee` model, the `overdue` scope is:
```php
public function scopeOverdue($query)
{
    return $query->where('status', 'overdue')
                 ->orWhere(function($q) {
                     $q->where('status', '!=', 'paid')
                       ->where('due_date', '<', now());
                 });
}
```

### Important: Status Is NOT Auto-Updated Daily
> **⚠️ Known Gap:** Currently, a fee with `due_date` in the past will NOT automatically
> become `overdue` unless a payment attempt triggers `updateStatus()`.
> You need a **scheduled job (Laravel artisan command/cron)** to update stale statuses.
> Example command to add: `php artisan fees:update-overdue-status`

---

## 9. Completed Fee Flow + Auto Invoice

### When a Fee Becomes "Paid"

As soon as the last payment brings `remaining_amount` to **₹0 or less**, the system:

1. Sets `fee.status = 'paid'`
2. Saves the fee
3. Calls `FeeService::generateInvoice($fee)` automatically

### What `generateInvoice()` Does

```
FeeService::generateInvoice($fee):

Step 1: Generate unique invoice number
        format: INV-{school_id}-{000001}
        e.g.:   INV-3-000042

Step 2: Create Invoice record in `invoices` table:
        - school_id    = fee.school_id
        - student_id   = fee.student_id
        - fee_id       = fee.id
        - invoice_number = generated above
        - invoice_date = today's date
        - amount       = fee.paid_amount (total collected)
        - pdf_path     = null (generated on-demand)

Step 3: Invoice is now available in:
        ─ School → Invoices list (school.invoices.index)
        ─ Student → Fee Details → Invoices section
```

### Invoice Numbering Format

```
INV - {school_id} - {6-digit-sequence}
 INV-3-000001  ← First invoice for school 3
 INV-3-000042  ← 42nd invoice for school 3
 INV-7-000001  ← First invoice for school 7 (separate sequence)
```

---

## 10. Invoice System — How It Works

### Invoice Data Flow

```
INVOICE CREATED (auto when fee is paid)
         │
         ▼
Stored in `invoices` table
(school_id, student_id, fee_id, invoice_number, invoice_date, amount)
         │
    ┌────┴─────────────────────────────────────────────┐
    │                                                  │
    ▼                                                  ▼
School Admin Side                            Student Side
─────────────────                            ────────────
Route: school.invoices.index                 Route: student.fees.show
Shows all invoices                           Shows invoice in Fee Details
Two actions available:                       "Download PDF" button visible
  1. View (opens in browser tab)
  2. Download PDF                            (uses school.invoices.download route)
```

### Viewing the Invoice (Browser Stream)

**Route:** `GET /school/invoices/{invoice}/stream`  
**Controller:** `InvoiceController::stream()`

```php
$pdf = Pdf::loadView('school.invoices.pdf', compact('invoice'))
          ->setPaper('a4', 'portrait');
return $pdf->stream("Invoice-{$invoice->invoice_number}.pdf");
// Opens in browser, user can print using browser's print dialog
```

### Downloading the Invoice as PDF

**Route:** `GET /school/invoices/{invoice}/download`  
**Controller:** `InvoiceController::download()`

```php
$pdf = Pdf::loadView('school.invoices.pdf', compact('invoice'))
          ->setPaper('a4', 'portrait');
return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
// Forces browser download of the PDF file
```

### What the PDF Invoice Includes

The invoice PDF (`resources/views/school/invoices/pdf.blade.php`) shows:

```
┌─────────────────────────────────────────────────────┐
│  [Purple top accent bar]                             │
│                                                      │
│       SCHOOL NAME (large, centered)                 │
│       Education Management System                    │
│  ─────────── FEE RECEIPT ─────────────             │
│                                                      │
│  Receipt Info      │ Issued By      │ Student Details│
│  # INV-3-000042    │ School Name    │ Student Name   │
│  Date: 20 Feb 2026 │ Address        │ ID: Roll No    │
│  Session: 2026-27  │ Email          │ Batch: Morning │
│                                                      │
│  DESCRIPTION                        AMOUNT          │
│  ─────────────────────────────────────────────────  │
│  Quarterly Fee Payment              ₹9,000.00        │
│  Due Date: 01 Apr 2026                               │
│                                                      │
│  Late Payment Charges (if any)     ₹200.00           │
│  ─────────────────────────────────────────────────  │
│                                                      │
│  [VERIFIED]    Subtotal            ₹9,200.00         │
│  (stamp)       Discount            ₹0.00  ← BUG      │
│                NET PAID            ₹9,200.00          │
│                                                      │
│  No signature required. Electronically generated.    │
└─────────────────────────────────────────────────────┘
```

> **⚠️ Known PDF Bug:** The Discount row in the PDF always shows `₹0.00` even if a discount
> was applied. This is because the PDF template has a hardcoded `₹0.00` instead of
> reading `$invoice->fee->discount`. This needs to be fixed.

---

## 11. Student View of Fees

### What the Student Can See

After logging in as a student, the student can access:

```
Student Dashboard
    │
    └── Sidebar → My Fees
              │
              ▼
        fees-index.blade.php
        Shows a table of all their fee records:
        ┌───────────────────────────────────────────────────────┐
        │ Due Date │ Fee Type │ Total │ Paid │ Balance │ Status │
        ├──────────┼──────────┼───────┼──────┼─────────┼────────┤
        │ Apr 1    │ Quarterly│ 9,000 │4,000 │  5,000  │PARTIAL │
        │ Jan 1    │ Monthly  │ 2,500 │2,500 │      0  │  PAID  │
        │ Mar 1    │ Sports   │ 1,500 │    0 │  1,500  │OVERDUE │
        └─────────────────────────────────────────────────────────┘
              │
              ▼ (click "Details")
        fee-details.blade.php
        Shows:
        Left Panel:                    Right Panel:
        ─ Fee Summary                  ─ Payment History
          • Fee Type                     • Date | Receipt# | Method | Amount
          • Due Date                   ─ Invoices Section
          • Status badge                 • Invoice # | Issue Date | [Download PDF]
          • Total Amount
          • Late Fee (+)
          • Discount (-)
          • Net Payable (calculated)
          • Total Paid
          • Remaining Balance
        ─ Admin Remarks (if any)
```

### Student Cannot Make Payments

> **Important:** Currently, the **student cannot initiate a payment** from their side.
> They can only **VIEW** their fee status and **DOWNLOAD** invoices for paid fees.
> All payments are recorded by the **School Admin**.

---

## 12. Database Tables Overview

### `fees` Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key |
| `school_id` | FK | School (multi-tenant) |
| `student_id` | FK | Which student |
| `fee_type` | STRING | `monthly`, `quarterly`, `half_yearly`, `annual`, `registration`, `other` |
| `sport_level` | STRING | ⚠️ **MISSING** — needs `basic` or `advanced` (student's level in the sport) |
| `total_amount` | DECIMAL(10,2) | Gross fee before discount |
| `discount` | DECIMAL(10,2) | Amount to be deducted |
| `late_fee` | DECIMAL(10,2) | Penalty charged |
| `paid_amount` | DECIMAL(10,2) | Running total of payments received |
| `due_date` | DATE | When the fee must be paid |
| `status` | ENUM | `pending`, `partial`, `paid`, `overdue` |
| `remarks` | TEXT | Admin can add notes for student |

### `fee_payments` Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key |
| `fee_id` | FK | Which fee it applies to |
| `amount` | DECIMAL(10,2) | Amount paid in this transaction |
| `payment_method` | STRING | `cash`, `bank_transfer`, `card`, `cheque` |
| `transaction_id` | STRING | Bank ref / cheque number (optional) |
| `notes` | TEXT | Admin note about this payment |
| `paid_at` | TIMESTAMP | When payment was recorded |
| `received_by` | FK(users) | Which school admin recorded it |

### `invoices` Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key |
| `school_id` | FK | School |
| `student_id` | FK | Student |
| `fee_id` | FK | Which fee this invoice is for |
| `invoice_number` | STRING UNIQUE | e.g., `INV-3-000042` |
| `invoice_date` | DATE | Date invoice was generated |
| `amount` | DECIMAL(10,2) | Total amount on invoice |
| `pdf_path` | STRING | Path to stored PDF (currently NULL — generated on demand) |

---

## 13. Full End-to-End Flow Diagram

```
SCHOOL ADMIN                       SYSTEM                          STUDENT
──────────────                     ──────                          ───────
1. Creates Fee Record
   (student, type, amount,    →   fees table:
   discount, due date)             status = 'pending'

                                   [Daily cron - not yet built]
                                   If due_date < today AND status != 'paid'
                                   → updates status to 'overdue'

2. Views Pending Fees List    ←    Queries fees WHERE status
   (Reports → Pending Fees)        IN ('pending','partial','overdue')

                                                                   3. Student logs in
                                                                      Views "My Fees" list
                                                                      Sees PENDING / OVERDUE
                                                                      in red

4. Clicks "Collect Payment"
   (from Pending Fees page)

5. Fills Payment Modal:
   - Amount = ₹4,000          →   fee_payments table:
   - Method = Cash                  Creates payment record
   - Transaction ID = CASH01   →   fees table:
                                     paid_amount += 4,000
                                     status = 'partial' (since remaining > 0)

                                                                   6. Student sees:
                                                                      Fee status → PARTIAL
                                                                      Paid: ₹4,000
                                                                      Remaining: ₹5,000

7. Later, collects remaining
   ₹5,000 payment             →   fee_payments table:
                                    Creates 2nd payment record
                               →   fees table:
                                    paid_amount = 9,000
                                    status = 'paid' ✅

                               →   FeeService::generateInvoice()
                                    Creates invoice record:
                                    INV-3-000042
                                    amount = 9,000

                                                                   8. Student sees:
                                                                      Fee status → PAID ✅
                                                                      Invoice appears in
                                                                      "Invoices" section
                                                                      [Download PDF] button

9. School admin can also
   view invoice list           ←   invoices table
   (school.invoices.index)         filtered by school_id

10. Download/View PDF          →   DomPDF renders pdf.blade.php
    (browser tab or download)      and streams/downloads PDF
```

---

## 14. What Is Currently Missing / Needs to Be Built

### 🔴 Critical — Must Build for Full Fee Structure Support

| # | What | Why Needed |
|---|------|-----------|
| M-01 | Add `sport_level` column to `fees` table | To track if student is at Basic or Advanced sports level |
| M-02 | Add `half_yearly` to fee_type options | 6-month fee type not in system |
| M-03 | Update Create Fee form with Sport Level dropdown | Admins can't select Basic / Advanced level for sports fee |
| M-04 | Create `school/payments/create.blade.php` view | Payment recording page crashes right now |
| M-05 | Fix `school.fees.edit` view (missing) | Can't edit a fee after creation |
| M-06 | Fix discount row in PDF invoice | Always shows ₹0.00 even when discount exists |

### 🟠 Important — For Better Workflow

| # | What | Why Needed |
|---|------|-----------|
| M-07 | Scheduled command: auto-update overdue fees | Without cron, status only updates on payment |
| M-08 | Batch fee generation feature | Create monthly fee for all students in a batch at once |
| M-09 | Discount type (fixed vs %) selector on create form | Currently only fixed ₹ amounts |
| M-10 | Student-facing invoice download (from student side) | Student view links to school invoices route — may fail |
| M-11 | `fee_type = half_yearly` added to StoreFeeRequest rules | Validation rejects it without this |
| M-12 | Link "Pending Fees" report from reports/index.blade.php | Report exists but not linked in Reports Center |

### 🟡 Nice to Have — Advanced Features

| # | What | Why Useful |
|---|------|-----------|
| M-13 | Fee template / plan system | Admin sets a batch-level fee plan (e.g., ₹2,500/month for all in Batch A) |
| M-14 | Automatic due-date calculation when `fee_type` selected | Quarterly → auto set due to 90 days from today |
| M-15 | Email/SMS notification to student on fee creation | Student is notified immediately |
| M-16 | Email/SMS on invoice generation | Student gets PDF by email when fully paid |
| M-17 | School-level default fee amounts per category | Reduce admin effort |
| M-18 | Sibling discount logic | If student has a sibling enrolled, auto-apply X% |
| M-19 | Analytics: fee collection rate by batch/month | Dashboard chart enhancements |

---

## 📊 Fee Calculation Example — Full Scenario

**Context:** Rajesh Kumar is in the Sports program at Advanced level.

### Fee Record 1 — Advanced Program

| Field | Value |
|-------|-------|
| Sport Level | **Advanced** |
| Fee Type | Quarterly |
| Total Amount | ₹ 5,000.00 |
| Discount | ₹   200.00 (merit scholarship) |
| Late Fee | ₹   100.00 (paid on 5th, due on 1st) |
| Net Payable | ₹ 4,900.00 |
| Paid (2 payments) | ₹ 4,900.00 |
| Balance | ₹     0.00 |
| Status | **PAID ✅** |
| Invoice | INV-3-000042 |

**Payment History:**  
- 15 Jan 2026 — ₹4,000.00 — Cash — CASH001  
- 05 Feb 2026 — ₹4,700.00 — Bank Transfer — NEFT20260205  

---

### Fee Record 2 — Same Student at Earlier Stage (Basic Level Record, now closed)

| Field | Value |
|-------|-------|
| Sport Level | **Basic** |
| Fee Type | Monthly (Sep 2025) |
| Total Amount | ₹   800.00 |
| Discount | ₹     0.00 |
| Late Fee | ₹     0.00 |
| Net Payable | ₹   800.00 |
| Paid | ₹   800.00 |
| Balance | ₹     0.00 |
| Status | **PAID ✅** |
| Invoice | INV-3-000038 |

> Rajesh was at **Basic level** in Sep 2025, paid his monthly fee.
> After his assessment, the coach promoted him to **Advanced level**.
> From Oct 2025 onwards, all new fees are created at the **Advanced** rate.

---

## ✅ Summary

The fee system in this project is **well-designed at its core** with the basic flow working:

- ✅ Fee creation with amount, discount, late fee, due date
- ✅ Multiple payments (partial payment support)
- ✅ Status auto-update (pending → partial → paid → overdue)
- ✅ Auto invoice generation when fee is fully paid
- ✅ PDF invoice with school logo section, student info, amounts
- ✅ Student can view their own fees and download invoices
- ✅ Pending fees report for school admin

**What needs to be added for your desired structure:**

- ❌ `half_yearly` fee type (6-month option)
- ❌ `sport_level` column (`basic` / `advanced`) in the `fees` table
- ❌ Sport Level dropdown on the Create Fee form
- ❌ The payment create view (currently crashes)
- ❌ Fee edit view (currently missing)
- ❌ Discount showing correctly on PDF
- ❌ Daily cron to auto-update overdue status
- ❌ Batch-level bulk fee generation

---

*Document created: 2026-02-20 | Based on codebase analysis*  
*No code was changed — this is a documentation-only report.*
