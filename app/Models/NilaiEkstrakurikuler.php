<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEkstrakurikuler extends Model
{
    protected $table = 'nilai_ekstrakurikuler';

    protected $fillable = [
        'siswa_id', 'tahun_ajaran_id', 'kegiatan', 'predikat', 'keterangan',
    ];
}
