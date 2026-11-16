<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel', 'nama_mapel', 'kkm', 'guru_id', 'is_pilar', 'is_muatan_lokal', 'status_aktif', 'kelas_id', 'tahun_ajaran_id',
    ];

    protected $casts = [
        'is_pilar' => 'boolean',
        'is_muatan_lokal' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function scopeUntukKelas($query, $kelasId, $periodeId)
    {
        return $query->where('kelas_id', $kelasId)->where('tahun_ajaran_id', $periodeId);
    }
}
