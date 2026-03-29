<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'class_id',
        'user_id',
        'judul',
        'konten',
        'priority',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'priority' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 3);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function getPriorityLabel(): string
    {
        return match ($this->priority) {
            1 => 'Rendah',
            2 => 'Normal',
            3 => 'Tinggi',
            4 => 'Sangat Tinggi',
            default => 'Normal',
        };
    }

    public function getPriorityColor(): string
    {
        return match ($this->priority) {
            1 => 'gray',
            2 => 'blue',
            3 => 'orange',
            4 => 'red',
            default => 'blue',
        };
    }
}
