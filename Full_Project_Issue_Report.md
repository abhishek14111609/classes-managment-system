# Full Project Issue Report

Date: 2026-03-10

## Scope
- Static, read-only review of controllers, services, models, requests, policies, routes, and jobs.
- No tests, migrations, or runtime behavior were executed.

## Summary
- High: 4
- Medium: 5
- Low: 4

## Findings

### High
1) Multi-tenant scope disabled in console runs (including queued jobs and scheduled commands), allowing cross-tenant reads/writes when jobs or commands mutate data without explicit `school_id` filters. See [app/Traits/MultiTenant.php](app/Traits/MultiTenant.php#L13-L31).

2) Predictable default admin password if `admin_password` is omitted when creating a school. This creates accounts with a known credential, which is a critical security risk. See [app/Services/SchoolService.php](app/Services/SchoolService.php#L60-L72) and [app/Http/Requests/StoreSchoolRequest.php](app/Http/Requests/StoreSchoolRequest.php#L31-L38).

3) `exists:*` validations are unscoped and therefore can accept IDs from other schools because validation does not apply model global scopes. This enables cross-tenant data injection across multiple create/update flows. Examples:
- [app/Http/Requests/StoreStudentRequest.php](app/Http/Requests/StoreStudentRequest.php#L30-L35)
- [app/Http/Requests/StoreFeeRequest.php](app/Http/Requests/StoreFeeRequest.php#L25-L27)
- [app/Http/Requests/StoreAttendanceRequest.php](app/Http/Requests/StoreAttendanceRequest.php#L25-L28)
- [app/Http/Requests/StoreBatchRequest.php](app/Http/Requests/StoreBatchRequest.php#L27-L37)
- [app/Http/Requests/StoreSportsEventRequest.php](app/Http/Requests/StoreSportsEventRequest.php#L39-L42)
- [app/Http/Controllers/Teacher/MaterialController.php](app/Http/Controllers/Teacher/MaterialController.php#L37-L44)

4) Teacher event participant actions lack authorization checks, and event participants are not tenant-scoped. A teacher can add/remove/update participants for events they do not coach, and `EventParticipant` does not include `school_id` in its fillable attributes (and does not use `MultiTenant`), allowing cross-tenant association drift. See [app/Http/Controllers/Teacher/EventController.php](app/Http/Controllers/Teacher/EventController.php#L52-L104), [app/Models/EventParticipant.php](app/Models/EventParticipant.php#L20-L33), and [app/Http/Controllers/School/SportsEventController.php](app/Http/Controllers/School/SportsEventController.php#L35-L83).

### Medium
1) Student activation cannot be reliably set to `false` during update because `array_filter()` drops falsey values, and `is_active` defaults to `true` if omitted. This can unintentionally re-activate users/students or prevent deactivation. See [app/Services/StudentService.php](app/Services/StudentService.php#L117-L143).

2) Fee duplication guard fails when `fee_plan_id` is `null` because `where('fee_plan_id', $data['fee_plan_id'] ?? null)` never matches `NULL`. This allows duplicate plan-less fees for the same period. See [app/Services/FeeService.php](app/Services/FeeService.php#L19-L29).

3) Attendance upsert key omits `batch_id` (and implicitly `school_id`), so a student in multiple batches can have attendance overwritten for the same date. See [app/Services/AttendanceService.php](app/Services/AttendanceService.php#L40-L56).

4) Invoice records are not linked to fee payments because `fee_payment_id` is not in the `Invoice` model’s fillable list, yet it is passed during invoice creation. This breaks audit trails and downstream lookups. See [app/Models/Invoice.php](app/Models/Invoice.php#L25-L34) and [app/Services/FeeService.php](app/Services/FeeService.php#L139-L155).

5) `recordPayment()` checks remaining balance without row-level locking, so concurrent payments can overpay the same fee. See [app/Services/FeeService.php](app/Services/FeeService.php#L73-L110).

### Low
1) Teacher batch assignments cannot be fully cleared when the request omits `batches`, because sync is conditional on `has('batches')`. See [app/Http/Controllers/School/TeacherController.php](app/Http/Controllers/School/TeacherController.php#L70-L78) and [app/Http/Controllers/School/TeacherController.php](app/Http/Controllers/School/TeacherController.php#L120-L128).

2) Teacher creation accepts `username` in the controller but the request rules do not validate it (nor enforce uniqueness), allowing duplicates or invalid values. See [app/Http/Controllers/School/TeacherController.php](app/Http/Controllers/School/TeacherController.php#L36-L42) and [app/Http/Requests/StoreTeacherRequest.php](app/Http/Requests/StoreTeacherRequest.php#L34-L48).

3) `ActivityLog::logActivity()` assumes an authenticated user; in console/queue contexts this can throw due to `auth()->user()` being `null`. See [app/Models/ActivityLog.php](app/Models/ActivityLog.php#L57-L66).

4) `extendSubscription()` assumes an existing active subscription or provided plan; if both are missing, `$plan` can be null and `$plan->id` will error. See [app/Services/SchoolService.php](app/Services/SchoolService.php#L101-L140).

## Notes
- This report focuses on correctness, data integrity, and authorization/tenant safety issues visible in static code inspection.
- I did not run tests or static analysis tools.
