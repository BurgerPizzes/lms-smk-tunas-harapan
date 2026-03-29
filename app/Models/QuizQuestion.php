<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'pertanyaan',
        'tipe',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'jawaban_benar',
        'pembahasan',
        'poin',
        'urutan',
    ];

    protected $casts = [
        'tipe' => \App\Enums\QuizQuestionType::class,
        'poin' => 'integer',
        'urutan' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function getOptions(): array
    {
        $options = [];

        if ($this->pilihan_a) {
            $options['a'] = $this->pilihan_a;
        }
        if ($this->pilihan_b) {
            $options['b'] = $this->pilihan_b;
        }
        if ($this->pilihan_c) {
            $options['c'] = $this->pilihan_c;
        }
        if ($this->pilihan_d) {
            $options['d'] = $this->pilihan_d;
        }
        if ($this->pilihan_e) {
            $options['e'] = $this->pilihan_e;
        }

        return $options;
    }

    public function getShuffledOptions(): array
    {
        $options = $this->getOptions();

        if ($this->quiz->random_soal) {
            shuffle($options);
        }

        return $options;
    }

    public function getCorrectOptionText(): ?string
    {
        $options = $this->getOptions();

        return $options[$this->jawaban_benar] ?? null;
    }

    public function isMultipleChoice(): bool
    {
        return $this->tipe === 'pilgan' || $this->tipe === 'multiple_choice';
    }

    public function isEssay(): bool
    {
        return $this->tipe === 'essay';
    }

    public function isTrueFalse(): bool
    {
        return $this->tipe === 'benar_salah';
    }
}
