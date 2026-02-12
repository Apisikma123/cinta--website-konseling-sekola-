<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verification_question',
        'verification_answer',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}