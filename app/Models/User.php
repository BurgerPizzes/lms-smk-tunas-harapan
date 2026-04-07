<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'nis',
        'nisn',
        'kelas_id',
        'jurusan_id',
        'tahun_ajaran_id',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ─── Accessors ──────────────────────────────────────────────

    public function getAvatarAttribute(): ?string
    {
        return $this->foto;
    }

    // ─── Relationships ────────────────────────────────────────────

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function enrolledClasses(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function teachingSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'class_guru_mapel', 'guru_id', 'mapel_id')
            ->withPivot(['class_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    public function mengajarKelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'class_guru_mapel', 'guru_id', 'class_id')
            ->withPivot(['mapel_id', 'tahun_ajaran_id', 'is_primary'])
            ->withTimestamps();
    }

    // ─── Helper Methods ───────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
    }

    public function getRoleName(): ?string
    {
        return $this->roles->first()?->display_name ?? $this->roles->first()?->name;
    }

    public function avatarUrl(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4F46E5&color=fff&size=128';
    }

    public function getInitials(): string
    {
        $words = explode(' ', trim($this->name));

        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }

        return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmin($query)
    {
        return $query->role(['admin', 'super-admin']);
    }

    public function scopeGuru($query)
    {
        return $query->role('guru');
    }

    public function scopeSiswa($query)
    {
        return $query->role('siswa');
    }
}
