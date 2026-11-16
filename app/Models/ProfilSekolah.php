<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'instansi_induk', 'sub_instansi', 'nama_sekolah', 'npsn', 'nss', 'akreditasi', 'alamat',
        'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos',
        'telepon', 'email', 'website', 'kepala_sekolah', 'nip_kepala_sekolah',
        'logo', 'logo_kiri', 'tipe_garis_kop', 'visi', 'misi'
    ];
}
