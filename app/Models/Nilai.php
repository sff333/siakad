<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id', 'mata_pelajaran_id', 'kelas_id', 'tahun_ajaran_id',
        'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'predikat', 'catatan', 'capaian_kompetensi',
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public static function hitungNilaiAkhir($tugas, $uts, $uas): float
    {
        // Bobot: Tugas 30%, UTS 30%, UAS 40%
        return round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
    }

    public static function getPredikat($nilai, ?array $aturan = null): string
    {
        foreach ($aturan ?? PengaturanPredikat::DEFAULTS as $predikat => $minimum) {
            if ($nilai >= $minimum) {
                return $predikat;
            }
        }

        return 'E';
    }
}
