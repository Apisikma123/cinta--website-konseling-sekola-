<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';

    protected $fillable = [
        'email',
        'name',
        'school',
        'class',
        'message',
        'unique_code',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi: Submission memiliki satu Magic Link Token
     */
    public function magicLinkToken(): HasOne
    {
        return $this->hasOne(MagicLinkToken::class, 'submission_id');
    }

    /**
     * Scope: Cari submission yang masih pending berdasarkan email
     */
    public function scopePendingByEmail($query, string $email)
    {
        return $query->where('email', $email)
            ->where('status', self::STATUS_PENDING);
    }

    /**
     * Check apakah submission sudah diverifikasi
     */
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    /**
     * Check apakah submission masih pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
