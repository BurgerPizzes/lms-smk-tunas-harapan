<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassGuruMapel extends Model
{
    use HasFactory;

    protected $table = 'class_guru_mapel';

    public $incrementing = true;

    protected $fillable = [
        'class_id',
        'guru_id',
        'mapel_id',
        'tahun_ajaran_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByGuru($query, int $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByMapel($query, int $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    public function scopeByTahunAjaran($query, int $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
