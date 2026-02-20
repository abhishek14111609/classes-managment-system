# System Enhancement Plan: Schools, Classes, and Courses

This document explains the proposed enhancements for the **Classes Management System** as requested. It outlines how we can add password management for schools, automate class creation, and implement a dedicated course reporting structure.

---

## 🔐 1. Admin Password for New Schools
Currently, the system uses a hidden default password for all new school admins. We will make this transparent and secure.

### How it works:
*   **The Form**: We add a "Password" and "Confirm Password" field to the **Create School** page in the Super Admin panel.
*   **Validation**: The system will check that the password is at least 8 characters long and matches the confirmation.
*   **Creation**: When you click "Create", the system will encrypt this specific password for that school's admin account instead of using a generic one.

---

## 🏗️ 2. Automatic Registration in Classes Table
To save time for the school owner, the system will automatically set up their first "Class" as soon as the school is created.

### How it works:
*   **The Logic**: As soon as the `schools` table gets a new entry, the system will instantly send a command to the `classes` table.
*   **The Result**: A default record (e.g., "Main Academic Class") will be created and linked to that school ID. 
*   **Benefit**: The school admin doesn't have to start from a completely empty dashboard; they can immediately start adding students to this pre-created class.

---

## 📚 3. New "Course" Table and Reporting
You requested that any course added via a school should be saved in a specific `courses` table, and a report should be available.

### How it works:
1.  **New Table**: We will create a `courses` table in the database with columns for:
    *   `School ID` (to know who owns it)
    *   `Course Name`
    *   `Description`
    *   `Status` (Active/Inactive)
2.  **Saving Data**: We will add a "Courses" menu in the School Admin panel. When they add a course, it saves directly to this new table.
3.  **The Report**: A new "Course Summary Report" will be generated that shows:
    *   Total courses assigned to a school.
    *   Active vs. Inactive courses.
    *   A list of all schools and the courses they have registered.

---

## 📝 Implementation Summary
No code has been changed yet. This plan ensures that:
1.  **Security** is improved (User-defined passwords).
2.  **Automation** is introduced (Auto-creating classes).
3.  **Data Organization** is better (Moving from simple "classes" to a formal "courses" structure).

---
*Created by Antigravity AI Assistant*
