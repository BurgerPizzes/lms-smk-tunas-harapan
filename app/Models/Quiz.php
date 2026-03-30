<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'tugas_id',
        'class_id',
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'durasi_menit',
        'jumlah_soal',
        'random_soal',
        'show_result',
        'is_published',
        'mulai_at',
        'selesai_at',
    ];

    protected $casts = [
        'durasi_menit' => 'integer',
        'jumlah_soal' => 'integer',
        'random_soal' => 'boolean',
        'show_result' => 'boolean',
        'is_published' => 'boolean',
        'mulai_at' => 'datetime',
        'selesai_at' => 'datetime',
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

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->published()
            ->where('mulai_at', '<=', now())
            ->where('selesai_at', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->published()->where('mulai_at', '>', now());
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        if (! $this->is_published) {
            return false;
        }

        $now = now();

        if ($this->mulai_at && $now->lt($this->mulai_at)) {
            return false;
        }

        if ($this->selesai_at && $now->gt($this->selesai_at)) {
            return false;
        }

        return true;
    }

    public function hasStarted(): bool
    {
        return $this->mulai_at && now()->gte($this->mulai_at);
    }

    public function hasEnded(): bool
    {
        return $this->selesai_at && now()->gt($this->selesai_at);
    }

    public function getAverageScore(): ?float
    {
        $avg = $this->attempts()->avg('skor');

        return $avg !== null ? round($avg, 1) : null;
    }

    public function getAttemptCount(): int
    {
        return $this->attempts()->count();
    }
}
