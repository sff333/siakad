<?php

namespace App\Support;

class ImportRules
{
    public static function headings(string $jenis): array
    {
        $headings = [];
        foreach (array_keys(self::for($jenis)) as $column) {
            $headings[$column] = implode(' ', array_map(
                fn ($word) => in_array($word, ['nis', 'nisn', 'nik', 'rt', 'rw', 'id', 'nip']) ? strtoupper($word) : ucfirst($word),
                explode('_', $column)
            ));
        }

        return $headings;
    }

    public static function for(string $jenis): array
    {
        return $jenis === 'siswa' ? [
            'nis' => 'nullable|string|unique:siswa,nis|max:20',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'nama' => 'nullable|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date_format:Y-m-d',
            'agama' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|integer|min:1|max:20',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'tinggi_badan' => 'nullable|integer|min:30|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tinggal_bersama' => 'nullable|string|max:100',
            'telepon_ortu' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nik_ayah' => 'nullable|string|max:20',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'penghasilan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:255',
            'nik_ibu' => 'nullable|string|max:20',
            'pendidikan_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'penghasilan_ibu' => 'nullable|string|max:100',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'telepon_wali' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'nullable|in:aktif,lulus,pindah,keluar',
            'tanggal_masuk' => 'nullable|date_format:Y-m-d',
            'asal_sekolah' => 'nullable|string|max:255',
            'nomor_ijazah_sebelumnya' => 'nullable|string|max:100',
        ] : [
            'nip' => 'nullable|string|max:30|unique:guru,nip',
            'nama' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date_format:Y-m-d',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jabatan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_aktif' => 'boolean',
        ];
    }
}
