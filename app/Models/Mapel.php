<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'kategori',
        'jurusan_id',
        'semester',
        'kkm',
        'is_active',
    ];

    protected $casts = [
        'kategori' => \App\Enums\KategoriMapel::class,
        'kkm' => 'integer',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'mapel_id');
    }

    public function tugases(): HasMany
    {
        return $this->hasMany(Tugas::class, 'mapel_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'mapel_id');
    }

    public function kelases(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'class_guru_mapel', 'mapel_id', 'class_id')
            ->withPivot(['guru_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_guru_mapel', 'mapel_id', 'guru_id')
            ->withPivot(['class_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
