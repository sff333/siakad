<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'user_id', 'nip', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat', 'telepon', 'email', 'jabatan', 'pendidikan_terakhir', 'foto', 'status_aktif'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status_aktif' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function mataPelajaran(): HasMany
    {
        return $this->hasMany(MataPelajaran::class);
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}
