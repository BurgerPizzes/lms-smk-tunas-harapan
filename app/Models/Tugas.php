<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'class_id',
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'instruksi',
        'file_attachment',
        'deadline',
        'tipe',
        'nilai_maks',
        'allow_late',
        'is_published',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'nilai_maks' => 'integer',
        'allow_late' => 'boolean',
        'is_published' => 'boolean',
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

    /**
     * Alias for guru() — used by controllers that reference 'user'.
     */
    public function user(): BelongsTo
    {
        return $this->guru();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'tugas_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class, 'tugas_id');
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function getStatusForSiswa(int $siswaId): string
    {
        $submission = $this->submissions()->where('siswa_id', $siswaId)->first();

        if (! $submission) {
            return 'belum_dikirim';
        }

        if ($submission->isGraded()) {
            return 'dinilai';
        }

        if ($submission->isLate()) {
            return 'terlambat';
        }

        return 'dikirim';
    }

    public function getSubmissionRate(): float
    {
        $totalSiswa = $this->kelas?->siswas()->count() ?? 0;

        if ($totalSiswa === 0) {
            return 0.0;
        }

        $submittedCount = $this->submissions()->count();

        return round(($submittedCount / $totalSiswa) * 100, 1);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function scopeActive($query)
    {
        return $query->published()->where('deadline', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now());
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }
}
