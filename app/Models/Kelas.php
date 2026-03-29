<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'jurusan_id',
        'tahun_ajaran_id',
        'tingkat',
        'ruangan',
        'kapasitas',
        'kode_unik',
        'deskripsi',
        'is_active',
        'cover_image',
        'guru_id',
    ];

    protected $casts = [
        'tingkat' => 'integer',
        'kapasitas' => 'integer',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'class_id');
    }

    public function tugases(): HasMany
    {
        return $this->hasMany(Tugas::class, 'class_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'class_id');
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_guru_mapel', 'class_id', 'guru_id')
            ->withPivot(['mapel_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    public function mapels(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'class_guru_mapel', 'class_id', 'mapel_id')
            ->withPivot(['guru_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getSiswaCountAttribute(): int
    {
        return $this->siswas()->count();
    }

    public function getIsActiveAttribute(bool $value): bool
    {
        return (bool) $value;
    }

    // ─── Methods ──────────────────────────────────────────────────

    public function generateKodeUnik(): string
    {
        $kode = strtoupper(Str::random(8));

        while (static::where('kode_unik', $kode)->exists()) {
            $kode = strtoupper(Str::random(8));
        }

        $this->kode_unik = $kode;

        return $kode;
    }

    public function getGuruPengampu(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->gurus()->withPivot('mapel_id')->get();
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
