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
        'sender_type',       // 'student' | 'teacher'
        'sender_id',         // nullable user id for teachers, null for students
        'message',
        'is_read',
        'edited_at',
        'original_message',
        'deleted_for_everyone',
        'deleted_at',
        'message_encrypted',
        'message_iv',
        'is_encrypted',
    ];

    protected $casts = [
        'is_read'             => 'boolean',
        'deleted_for_everyone'=> 'boolean',
        'is_encrypted'        => 'boolean',
        'edited_at'           => 'datetime',
        'deleted_at'          => 'datetime',
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