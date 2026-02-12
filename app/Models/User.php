<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school',
        'class',
        'phone',
        'whatsapp',
        'secret_formula',
        'is_approved',
        'is_active',
        'last_activity',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_activity' => 'datetime',
        'is_approved' => 'boolean',
    ];

    // Role helpers
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // RELATIONS
    public function reports()
    {
        return $this->hasMany(Report::class, 'guru_id');
    }

    public function handledReports()
    {
        return $this->hasMany(ReportDetail::class, 'handled_by');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'user_id');
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function teacherApproval()
    {
        return $this->hasOne(TeacherApproval::class, 'user_id');
    }
}