# Comprehensive System Architecture Plan: Hierarchical Management

This document outlines the proposed **Multi-Level Hierarchy** and detailed requirements for the Management System, including students, academics, sports, and financial operations.

---

## 🏛️ Level 1: Institute Management (Super Admin Control)
The Super Admin creates the top-level entity representing the organization.
*   **Entities**: Schools, Institutes, or Coaching Centers.
*   **Examples**: *Modi School*, *PM Classes*, *CM Institute*.
*   **Subscription Control**: 
    *   **Expiry Date**: Each entity is linked to an expiry date based on their chosen plan.
    *   **Payment**: Supports **Cash Payments** for institute-level subscriptions/renewals.

---

## 🎓 Level 2: Programs & Courses (Institute Admin Control)
The primary categories of education or training offered.
*   **Academic**: GPSC, 10th Standard, 12th Standard (Science/Commerce).
*   **Sports**: Specialized Sports Division.

---

## 📖 Level 3: Subject, Syllabus & Sports Types
Specific topics or sports within a Course.
*   **Subjects (Syllabus)**: Maths, Science, History, Geography, etc.
*   **Sports Types**: Cricket, Football, Tennis, etc.

---

## 🕒 Level 4: Batches & Timing
Specific schedules assigned to students.
*   **Academic Batches**: Morning, Afternoon, Evening.
*   **Sports Batches**: Morning Practice, Evening Training.
*   **Timing**: Each batch has defined start/end times.

---

## 👤 Detailed Student Profiles
Every student record will include:
*   **Personal**: Name, Birth Date, Photo.
*   **Academic**: Assigned School/Institute, Assigned Course/Class.
*   **Scheduling**: Assigned Batch and Batch Timing.
*   **Account**: Email, Username, Password, Mobile Number.

---

## � Financial & Operations Modules
The system manages the complete lifecycle of fees and events:
1.  **Fee Management**:
    *   **Cash Collection**: Option to collect and record student fees in **Cash**.
    *   **Pending List**: Dashboard to track "Fees Pending List" for quick follow-up.
    *   **Collection Tracking**: "Total Paid Fees" vs "Balance Due" per student.
2.  **Invoicing**:
    *   Professional "Invoice" generation for every payment (Cash or Online).
3.  **Sports Management**:
    *   "Sports Event" scheduling and participant tracking.

---

## 📊 Reporting (Monthly & Analytical)
The system will generate comprehensive **Monthly Reports** covering:
1.  **Enrollment**: New students per Course/Subject.
2.  **Financials**: Total fee collection (Monthly Revenue).
3.  **Defaulters**: Detailed list of pending payments for the month.
4.  **Attendance**: Engagement levels in both Academic and Sports batches.

---

## 🛠️ Data Model Mapping
| Feature | Implementation |
| :--- | :--- |
| **Institute Payment** | Plan/Subscription module with Cash override. |
| **Student Login** | Extended User model with Username/Password support. |
| **Batch Time** | Start/End time fields in the Batch model. |
| **Syllabus** | New `Subjects` table linked to Classes. |

---
*Created by Antigravity AI Assistant*
