<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $fillable = ['quiz_attempt_id', 'quiz_question_id', 'jawaban', 'benar', 'poin_diperoleh'];

    protected $casts = [
        'benar' => 'boolean',
        'poin_diperoleh' => 'integer',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
