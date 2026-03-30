<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submissions';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'konten',
        'file_path',
        'submitted_at',
        'status',
        'nilai',
        'feedback',
        'catatan_guru',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'status' => \App\Enums\SubmissionStatusEnum::class,
        'nilai' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function isLate(): bool
    {
        if (! $this->submitted_at || ! $this->tugas->deadline) {
            return false;
        }

        return $this->submitted_at->gt($this->tugas->deadline);
    }

    public function isGraded(): bool
    {
        return $this->nilai !== null;
    }

    public function isPassed(): bool
    {
        if ($this->nilai === null || $this->tugas->nilai_maks === null) {
            return false;
        }

        return $this->nilai >= ($this->tugas->nilai_maks * 0.75);
    }

    public function getFormattedNilai(): string
    {
        if ($this->nilai === null) {
            return '-';
        }

        return $this->nilai . ' / ' . ($this->tugas->nilai_maks ?? 100);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'terlambat');
    }

    public function scopeGraded($query)
    {
        return $query->whereNotNull('nilai');
    }

    public function scopeUngraded($query)
    {
        return $query->whereNull('nilai')->whereNotNull('submitted_at');
    }

    public function scopeBySiswa($query, int $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByTugas($query, int $tugasId)
    {
        return $query->where('tugas_id', $tugasId);
    }
}
