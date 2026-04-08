<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MagicLinkToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'email',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Relasi: Token milik sebuah Submission
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    /**
     * Check apakah token sudah digunakan
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Check apakah token sudah expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Check validitas token (belum digunakan dan belum expired)
     */
    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    /**
     * Scope: Cari token berdasarkan token string yang valid
     */
    public function scopeValidToken($query, string $tokenString)
    {
        return $query->where('token', $tokenString)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('used_at');
    }
}
