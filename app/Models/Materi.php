<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'class_id',
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'konten',
        'tipe',
        'file_path',
        'video_url',
        'pertemuan_ke',
        'is_published',
        'urutan',
    ];

    protected $casts = [
        'pertemuan_ke' => 'integer',
        'is_published' => 'boolean',
        'urutan' => 'integer',
        'tanggal_dibuat' => 'datetime',
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

    public function user(): BelongsTo
    {
        return $this->guru();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'materi_id');
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

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByMapel($query, int $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }
}
