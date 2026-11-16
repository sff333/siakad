<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected static function booted(): void
    {
        static::saved(function (Siswa $siswa) {
            if ($siswa->wasRecentlyCreated || $siswa->wasChanged('kelas_id')) {
                $year = TahunAjaran::getActive()?->tahun_ajaran;
                if ($year) {
                    AnggotaRombel::updateOrCreate(['siswa_id' => $siswa->id, 'tahun_ajaran' => $year], ['kelas_id' => $siswa->kelas_id]);
                }
            }
        });
    }

    public function anggotaRombel(): HasMany
    {
        return $this->hasMany(AnggotaRombel::class);
    }

    public function scopeDiRombel($query, $kelasId, ?string $year)
    {
        return $query->whereHas('anggotaRombel', fn ($q) => $q->where('tahun_ajaran', $year)->where('kelas_id', $kelasId));
    }

    public function kelasPada(?string $year): ?Kelas
    {
        return $this->anggotaRombel()->where('tahun_ajaran', $year)->with('kelas.guru')->first()?->kelas;
    }

    protected $table = 'siswa';

    protected $fillable = [
        'nis', 'nisn', 'nik', 'nama', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'agama', 'kewarganegaraan', 'anak_ke', 'jumlah_saudara', 'golongan_darah', 'tinggi_badan', 'berat_badan',
        'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'tinggal_bersama',
        'telepon_ortu',
        'nama_ayah', 'nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
        'nama_ibu', 'nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
        'nama_wali', 'hubungan_wali', 'pekerjaan_wali', 'telepon_wali',
        'foto', 'kelas_id', 'status', 'tanggal_masuk', 'asal_sekolah', 'nomor_ijazah_sebelumnya',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
