<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'siswa_id',
        'skor',
        'total_benar',
        'total_salah',
        'total_soal',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_detik',
        'status',
        'answers',
    ];

    protected $casts = [
        'skor' => 'integer',
        'total_benar' => 'integer',
        'total_salah' => 'integer',
        'total_soal' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'durasi_detik' => 'integer',
        'status' => \App\Enums\QuizAttemptStatus::class,
        'answers' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function getFormattedDuration(): string
    {
        $minutes = floor($this->durasi_detik / 60);
        $seconds = $this->durasi_detik % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getPercentageScore(): ?float
    {
        if ($this->total_soal === 0) {
            return null;
        }

        return round(($this->total_benar / $this->total_soal) * 100, 1);
    }

    public function isPassed(): bool
    {
        return $this->skor !== null && $this->skor >= 75;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'selesai' || $this->waktu_selesai !== null;
    }

    public function getRemainingTime(): ?int
    {
        if (! $this->waktu_mulai) {
            return null;
        }

        $quizDuration = $this->quiz->durasi_menit * 60;
        $elapsed = now()->diffInSeconds($this->waktu_mulai);

        return max(0, $quizDuration - $elapsed);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'berjalan');
    }

    public function scopeBySiswa($query, int $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByQuiz($query, int $quizId)
    {
        return $query->where('quiz_id', $quizId);
    }

    public function scopePassed($query)
    {
        return $query->where('skor', '>=', 75);
    }

    public function scopeFailed($query)
    {
        return $query->where('skor', '<', 75);
    }
}
