<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const STATUS_BARU = 'baru';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'school_id',
        'tracking_code',
        'nama_murid',
        'email_murid',
        'email_verified_at',
        'nama_sekolah',
        'kelas',
        'title',
        'isi_laporan',
        'jenis_laporan',
        'phone',
        'status',
        'guru_id',
        'secret_code',
        'claimed_by',
        'claimed_at',
    ];

    protected $casts = [
        'guru_id'           => 'integer',
        'claimed_by'        => 'integer',
        'claimed_at'        => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // RELATIONS
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Guru yang mengambil / mengklaim laporan ini.
     */
    public function claimedBy()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'report_id');
    }

    public function detail()
    {
        return $this->hasOne(ReportDetail::class, 'report_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'report_id');
    }

    // ACCESSORS

    /**
     * $report->is_claimed — true bila laporan sudah diambil oleh guru.
     */
    public function getIsClaimedAttribute(): bool
    {
        return ! is_null($this->claimed_by);
    }

    /**
     * True jika laporan sudah diverifikasi via magic link (atau tidak perlu email).
     */
    public function isEmailVerified(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Scope: hanya laporan yang sudah diverifikasi (tampil ke guru).
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // HELPERS
    public function isFinished(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }
}