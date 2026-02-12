<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ChatController;

// ============================================
// PUBLIC ROUTES (Guest)
// ============================================

Route::middleware('web')->group(function () {
    // Homepage & Base Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home/counselors-partial', [HomeController::class, 'counselorsPartial'])->name('home.counselors');
    
    // Student Report Routes (Public)
    Route::get('/create', [ReportController::class, 'createForm'])->name('student.create');
    Route::post('/report', [ReportController::class, 'create'])
        ->middleware('throttle:20,1')
        ->name('report.store');
    Route::get('/result/{tracking_code}', [ReportController::class, 'result'])->name('result');
    Route::get('/track', [ReportController::class, 'showTrackForm'])->name('student.track');
    Route::get('/track/{tracking_code}', [ReportController::class, 'track'])->name('track.status');
    
    // Testimonial Routes
    Route::post('/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
    
    // Student Chat Routes
    Route::get('/chat-murid/{tracking_code}', [ChatController::class, 'studentIndex'])->name('chat.murid');
    Route::post('/chat-murid/{tracking_code}', [ChatController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('chat.murid.store');
    Route::get('/chat-murid/{tracking_code}/messages', [ChatController::class, 'messages'])->name('chat.murid.messages');
    Route::post('/chat-murid/{tracking_code}/messages/{id}/mark-read', [ChatController::class, 'markAsRead'])->name('chat.murid.mark-read');
    
    // WhatsApp Integration
    Route::get('/chat/{tracking_code}/whatsapp', [ChatController::class, 'whatsapp'])->name('chat.whatsapp');
});

// ============================================
// AUTH ROUTES (Custom Implementation)
// ============================================

Route::middleware('guest')->group(function () {
    // Teacher Registration with Secret Verification
    Route::get('/register/teacher', [AuthController::class, 'showRegisterForm'])->name('register.teacher.form');
    Route::post('/register/teacher', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.teacher');
    
    // OTP Verification Routes
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
        ->middleware('throttle:5,1')
        ->name('resend.otp');
    Route::get('/verify-success', [AuthController::class, 'showVerifySuccess'])->name('verify.success');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ============================================
// TEACHER ROUTES (Protected)
// ============================================

Route::middleware(['auth', 'role:teacher', \App\Http\Middleware\CheckTeacherApproved::class])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'home'])->name('dashboard');
    Route::get('/reports', [TeacherController::class, 'reports'])->name('reports.index');
    Route::get('/reports/{report}', [TeacherController::class, 'showReport'])->name('reports.show');
    
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::get('/settings', [TeacherController::class, 'settings'])->name('settings');
    Route::post('/settings', [TeacherController::class, 'updateSettings'])->name('settings.update');
    Route::post('/upload-photo', [TeacherController::class, 'uploadProfilePhoto'])->name('upload-photo');
    
    // Password Management
    Route::post('/change-password', [TeacherController::class, 'changePasswordRequest'])->name('settings.change-password');
    Route::get('/verify-otp-password', [TeacherController::class, 'verifyOtpPassword'])->name('verify-otp-password');
    Route::post('/verify-otp-password', [TeacherController::class, 'verifyOtpPassword']);
    Route::post('/resend-otp-password', [TeacherController::class, 'resendOtpPassword'])->name('resend-otp-password');
    
    // Email Management
    Route::post('/change-email', [TeacherController::class, 'changeEmailRequest'])->name('settings.change-email');
    Route::get('/verify-otp-email', [TeacherController::class, 'verifyOtpEmail'])->name('verify-otp-email');
    Route::post('/verify-otp-email', [TeacherController::class, 'verifyOtpEmail']);
    Route::post('/resend-otp-email', [TeacherController::class, 'resendOtpEmail'])->name('resend-otp-email');
    
    // Report Status Management
    Route::patch('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    
    // Testimonials Management
    Route::get('/testimonials', [TeacherController::class, 'testimonials'])->name('testimonials');
    Route::post('/testimonials/{id}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{id}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::post('/testimonials/{testimonial}/hide', [AdminController::class, 'hideTestimonial'])->name('testimonials.hide');
    Route::post('/testimonials/{testimonial}/show', [AdminController::class, 'showTestimonial'])->name('testimonials.show');
    
    // Secret Code Management
    Route::get('/secret-management', [TeacherController::class, 'secretManagement'])->name('secret-management');
    Route::post('/secret-management/generate', [TeacherController::class, 'generateSecretCode'])->name('secret.regenerate');
    
    // Chat Routes
    Route::get('/chat/{tracking_code}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{tracking_code}', [ChatController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('chat.store');
    Route::get('/chat/{tracking_code}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{tracking_code}/messages/{id}/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
});

// ============================================
// ADMIN ROUTES (Protected)
// ============================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    
    // Admin Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Teacher Management
    Route::get('/approve-teachers', [AdminController::class, 'approveTeachers'])->name('approve-teachers');
    Route::post('/approve-teacher/{id}', [AdminController::class, 'approveTeacher'])->name('approve-teacher');
    
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
    Route::patch('/teachers/{teacher}/toggle', [AdminController::class, 'toggleTeacherStatus'])->name('teachers.toggle');
    Route::delete('/teachers/{teacher}', [AdminController::class, 'destroyTeacher'])->name('teachers.destroy');
    
    // School Management
    Route::get('/schools', [AdminController::class, 'schools'])->name('schools');
    Route::post('/schools', [AdminController::class, 'storeSchool'])->name('schools.store');
    Route::put('/schools/{school}', [AdminController::class, 'updateSchool'])->name('schools.update');
    Route::delete('/schools/{school}', [AdminController::class, 'destroySchool'])->name('schools.destroy');
    
    // Testimonials Management
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('testimonials');
    Route::post('/testimonials/{testimonial}/hide', [AdminController::class, 'hideTestimonial'])->name('testimonials.hide');
    Route::post('/testimonials/{testimonial}/show', [AdminController::class, 'showTestimonial'])->name('testimonials.show');
});

// ============================================
// API ROUTES (Real-time Features)
// ============================================

Route::middleware('throttle:60,1')->prefix('api')->name('api.')->group(function () {
    Route::post('/chat/active', [ChatController::class, 'trackActivity'])->name('chat.active');
    Route::get('/presence/{tracking_code}', [ChatController::class, 'getPresence'])->name('presence');
    Route::post('/presence/bulk', [ChatController::class, 'getBulkPresence'])->name('presence.bulk');
    Route::get('/user-online/{userId}', [ChatController::class, 'checkUserOnline'])->name('user-online');
});
