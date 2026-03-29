<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDetail extends Model
{
    use HasFactory;

    protected $table = 'attendance_details';

    protected $fillable = [
        'attendance_id',
        'siswa_id',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => \App\Enums\AttendanceStatusEnum::class,
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }

    public function scopeIzin($query)
    {
        return $query->where('status', 'izin');
    }

    public function scopeSakit($query)
    {
        return $query->where('status', 'sakit');
    }

    public function scopeAlpha($query)
    {
        return $query->where('status', 'alpha');
    }
}
