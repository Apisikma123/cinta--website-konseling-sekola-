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
        'nama_sekolah',
        'kelas',
        'title',
        'isi_laporan',
        'jenis_laporan',
        'phone',
        'status',
        'guru_id',
        'secret_code',
    ];

    protected $casts = [
        'guru_id' => 'integer',
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

    // Helpers
    public function isFinished()
    {
        return $this->status === self::STATUS_SELESAI;
    }
}