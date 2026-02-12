<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'handled_by',
        'handled_at',
        'notes',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    // RELATIONS
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}