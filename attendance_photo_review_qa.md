# Attendance Photo Review QA Checklist

## Setup
- Run migrations, then seed minimum data (school, class, batch, student, teacher).
- Ensure storage link exists for photo access.

## Student Upload Flow
- Open student attendance page during allowed window.
- Activate camera and submit a live photo.
- Confirm success message appears.
- Verify attendance record shows status pending and verification pending.

## Teacher Review Flow
- Open teacher attendance mark page for the same batch/date.
- Verify pending badge and submitted time show next to student.
- Click View Photo and confirm image opens with timestamp.
- Mark Present to approve and submit.
- Verify status becomes approved and review time is shown.

## Reject Flow
- Submit another pending photo for a different student.
- Mark Absent to reject and submit.
- Verify status becomes rejected and review time is shown.

## Permissions
- Teacher should only see students in assigned batches.
- Student should not submit outside the time window.

## Data Integrity
- Ensure only one attendance record exists per student/date.
- Confirm reviewed_by and reviewed_at are set on approve/reject.
