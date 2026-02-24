# Simple Explanation: Academic vs. Sports Setup

To put it simply, your system is built very well. It uses the **exact same code and database** for both Schools and Sports Academies. This is a very smart way to build software because it means you don't have to write everything twice.

Here is how the system secretly translates things:

### How they are the same (Behind the scenes):
In the database, the computer just sees "Groups formatting into sub-groups".

*   **In a School:** A "Course" (like Science) has "Classes" (like Grade 10) which have "Subjects" (like Physics).
*   **In a Sports Academy:** A "Course" (like Cricket) has "Teams" (like U-16 Boys) which have "Activities" (like Batting Practice).

It's the exact same structure! A *Class* and a *Team* are technically identical to the computer. 

### Where is the problem?
The problem is only visual.

We already fixed the main dashboard menus so a Sports Admin sees "Athletes" instead of "Students". 

**HOWEVER,** if a Sports Admin clicks "Add New Athlete", the form that pops up is still designed for a school. 
*   It asks for a **Roll Number** (Sports players use Jersey Numbers or IDs).
*   It asks what **Class** they will join (Sports players join Teams).
*   When adding a Coach, it asks for their **Subject Expertise** (It should ask for their Coaching Role).

### What needs to be done?
We don't need to rebuild the application. We just need to change the **labels and text** on the forms where you add or edit information.

We need to tell the code: *"If this is a sports school, change the word 'Roll Number' to 'Jersey/Registration ID' on this input box."*

Once we do that across the forms (Adding Students, Adding Teachers, Creating Classes, etc.), the Sports Academy experience will feel 100% real and perfect without any bugs!
