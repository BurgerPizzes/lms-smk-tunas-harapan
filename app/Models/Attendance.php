<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'class_id',
        'mapel_id',
        'guru_id',
        'tanggal',
        'pertemuan_ke',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pertemuan_ke' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(AttendanceDetail::class, 'attendance_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByMapel($query, int $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function getHadirCount(): int
    {
        return $this->details()->where('status', 'hadir')->count();
    }

    public function getIzinCount(): int
    {
        return $this->details()->where('status', 'izin')->count();
    }

    public function getSakitCount(): int
    {
        return $this->details()->where('status', 'sakit')->count();
    }

    public function getAlphaCount(): int
    {
        return $this->details()->where('status', 'alpha')->count();
    }
}
