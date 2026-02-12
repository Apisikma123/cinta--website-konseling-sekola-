<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'city',
        'is_active',
        'secret_code',
        'secret_code_generated_at',
    ];

    protected $casts = [
        'secret_code_generated_at' => 'datetime',
    ];
}
