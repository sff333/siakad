<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Identitas Pribadi Siswa (Buku Induk)
            $table->string('nik', 16)->nullable()->after('nisn');
            $table->string('nama_panggilan')->nullable()->after('nama');
            $table->string('kewarganegaraan')->default('WNI')->after('agama');
            $table->unsignedTinyInteger('anak_ke')->nullable()->after('kewarganegaraan');
            $table->unsignedTinyInteger('jumlah_saudara')->nullable()->after('anak_ke');
            $table->string('golongan_darah', 5)->nullable()->default('-')->after('jumlah_saudara');
            $table->unsignedSmallInteger('tinggi_badan')->nullable()->after('golongan_darah');
            $table->unsignedSmallInteger('berat_badan')->nullable()->after('tinggi_badan');

            // Domisili Detail
            $table->string('rt', 5)->nullable()->after('alamat');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('kelurahan')->nullable()->after('rw');
            $table->string('kecamatan')->nullable()->after('kelurahan');
            $table->string('kabupaten')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kabupaten');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
            $table->string('tinggal_bersama')->nullable()->after('kode_pos');

            // Data Ayah
            $table->string('nik_ayah', 16)->nullable()->after('nama_ayah');
            $table->string('pendidikan_ayah')->nullable()->after('pekerjaan_ayah');
            $table->string('penghasilan_ayah')->nullable()->after('pendidikan_ayah');

            // Data Ibu
            $table->string('nik_ibu', 16)->nullable()->after('nama_ibu');
            $table->string('pendidikan_ibu')->nullable()->after('pekerjaan_ibu');
            $table->string('penghasilan_ibu')->nullable()->after('pendidikan_ibu');

            // Data Wali
            $table->string('hubungan_wali')->nullable()->after('nama_wali');
            $table->string('pekerjaan_wali')->nullable()->after('hubungan_wali');

            // Riwayat Masuk Sekolah
            $table->string('asal_sekolah')->nullable()->after('tanggal_masuk');
            $table->string('nomor_ijazah_sebelumnya')->nullable()->after('asal_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'nama_panggilan', 'kewarganegaraan', 'anak_ke', 'jumlah_saudara',
                'golongan_darah', 'tinggi_badan', 'berat_badan',
                'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'tinggal_bersama',
                'nik_ayah', 'pendidikan_ayah', 'penghasilan_ayah',
                'nik_ibu', 'pendidikan_ibu', 'penghasilan_ibu',
                'hubungan_wali', 'pekerjaan_wali',
                'asal_sekolah', 'nomor_ijazah_sebelumnya'
            ]);
        });
    }
};
