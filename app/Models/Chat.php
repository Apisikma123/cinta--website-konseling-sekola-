<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_code',
        'report_id',
        'sender_type', // 'student' | 'teacher'
        'sender_id',   // nullable user id for teachers, null for students
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // RELATIONS
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}