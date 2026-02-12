<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'student_name',
        'content',
        'is_anonymous',
        'is_approved',
        'is_visible',
        'rating',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean',
        'is_visible' => 'boolean',
        'rating' => 'integer',
    ];

    // RELATIONS
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}