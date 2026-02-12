# CINTA — Architecture & Decisions

## Overview
This patch aligns the codebase with the project spec focusing on: clean architecture, minimal queries, event-driven notifications, queued email delivery (Resend), and safe role-based access.

## What I changed ✅
- DB: Added migrations to standardize fields and indexes for `reports`, `chats`, and `testimonials`. Created `otp_verifications` table.
- Models: Updated `Report`, `Chat`, `Testimonial`, `User`, and added `OtpVerification` model with correct relations and casts.
- Service Layer: Added `ReportService` (create + changeStatus) and `OtpService` (generate + verify) to centralize business logic.
- Events & Listeners: `ReportCreated` -> `NotifyTeachersOfNewReport` (queued), `ReportStatusChanged` -> `NotifyStudentOfStatusChange` (queued).
- Notifications: `NewReportNotification`, `ReportStatusNotification`, `OtpNotification` implemented and queued.
- Policies & Authorization: `ReportPolicy` and `AuthServiceProvider` with `approve-teacher` gate.
- Controllers: Refactored `ReportController` and `ChatController` to be thin and use services; added polling endpoint for messages.
- Views: Updated `student.track` to use the normalized fields and show dynamic WhatsApp link.

## Why these decisions (short)
- Service Layer (ReportService, OtpService): keeps controllers thin and testable; centralizes transactional logic and prevents duplication.
- Events → Listeners (queued): decouples domain actions from side-effects (emails, notifications) and improves throughput.
- Notifications via Mail (Resend configured): ensures emails are queued and reliable; using Laravel Notification keeps flexibility for future channels.
- Policies & Gates: secure role-based operations without scattering checks across controllers.
- Indexes on `tracking_code`, `guru_id`, `status`: critical for fast lookups and filtering.

## Next recommended steps
1. Run migrations: `php artisan migrate` and publish/queue tables (`php artisan queue:table` if using DB queues).
2. Set env vars: `MAIL_MAILER=resend`, `MAIL_FROM_ADDRESS=sistemcinta@telkomcare.my.id`, `QUEUE_CONNECTION=redis` (or `database`).
3. Run a queue worker: `php artisan queue:work --queue=default`.
4. Implement small integration tests for the `ReportService` flows and notifications.
5. Migrate historical data scripts (if any) to populate new columns from old ones.

## Teacher registration & approval flow
- Teachers register via `/register/teacher` (submit `verification_answer`).
- The system generates a queued OTP via `OtpService` and sends an email (Resend) for verification.
- On successful OTP verification the teacher sets their password and awaits admin approval.
- Admins review pending accounts at `/admin/approve-teachers` and approve; a queued `TeacherApprovedNotification` is sent to the teacher.

---

If you want, I can:
- Implement the teacher registration + OTP flow (controller + forms + tests)
- Add unit/integration tests for the ReportService and event flows
- Add a lightweight JS polling client for chat

Tell me which you'd like prioritized next.
