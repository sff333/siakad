<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfilSekolah;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Profil Sekolah
        ProfilSekolah::create([
            'nama_sekolah' => 'SD Negeri 1 Contoh',
            'npsn' => '10100001',
            'akreditasi' => 'A',
            'alamat' => 'Jl. Pendidikan No. 1',
            'kelurahan' => 'Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'kabupaten' => 'Kabupaten Contoh',
            'provinsi' => 'Provinsi Contoh',
            'kode_pos' => '12345',
            'telepon' => '021-1234567',
            'kepala_sekolah' => 'Drs. Ahmad Sudirman, M.Pd',
            'nip_kepala_sekolah' => '196501011990031001',
            'visi' => 'Menjadi sekolah unggul yang menghasilkan lulusan berkarakter, cerdas, dan berakhlak mulia.',
            'misi' => "1. Menyelenggarakan pendidikan yang berkualitas\n2. Membentuk karakter siswa yang berakhlak mulia\n3. Mengembangkan potensi siswa secara optimal\n4. Menciptakan lingkungan belajar yang kondusif",
        ]);

        // Tahun Ajaran
        $ta = TahunAjaran::create([
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Genap',
            'is_active' => true,
            'tanggal_mulai' => '2026-01-05',
            'tanggal_selesai' => '2026-06-30',
        ]);

        // Guru
        $guruData = [
            ['nama' => 'Siti Aminah, S.Pd', 'nip' => '198001012005012001', 'jenis_kelamin' => 'P', 'jabatan' => 'Guru Kelas', 'pendidikan_terakhir' => 'S1'],
            ['nama' => 'Budi Santoso, S.Pd', 'nip' => '198502152008011001', 'jenis_kelamin' => 'L', 'jabatan' => 'Guru Kelas', 'pendidikan_terakhir' => 'S1'],
            ['nama' => 'Dewi Lestari, S.Pd', 'nip' => '199003202010012001', 'jenis_kelamin' => 'P', 'jabatan' => 'Guru Mapel', 'pendidikan_terakhir' => 'S1'],
            ['nama' => 'Ahmad Rizki, S.Pd', 'nip' => '199105102012011001', 'jenis_kelamin' => 'L', 'jabatan' => 'Guru PJOK', 'pendidikan_terakhir' => 'S1'],
            ['nama' => 'Nurhasanah, S.Pd', 'nip' => '198712012009012001', 'jenis_kelamin' => 'P', 'jabatan' => 'Guru Agama', 'pendidikan_terakhir' => 'S1'],
            ['nama' => 'Rina Marlina, S.Pd', 'nip' => '199204012015012001', 'jenis_kelamin' => 'P', 'jabatan' => 'Guru Kelas', 'pendidikan_terakhir' => 'S1'],
        ];
        $guruIds = [];
        foreach ($guruData as $g) {
            $guru = Guru::create(array_merge($g, ['status_aktif' => true]));
            $guruIds[] = $guru->id;
        }

        // Kelas
        $kelasData = [
            ['nama_kelas' => 'Kelas 1A', 'tingkat' => 1, 'rombel' => 'A'],
            ['nama_kelas' => 'Kelas 2A', 'tingkat' => 2, 'rombel' => 'A'],
            ['nama_kelas' => 'Kelas 3A', 'tingkat' => 3, 'rombel' => 'A'],
            ['nama_kelas' => 'Kelas 4A', 'tingkat' => 4, 'rombel' => 'A'],
            ['nama_kelas' => 'Kelas 5A', 'tingkat' => 5, 'rombel' => 'A'],
            ['nama_kelas' => 'Kelas 6A', 'tingkat' => 6, 'rombel' => 'A'],
        ];
        $kelasIds = [];
        foreach ($kelasData as $i => $k) {
            $kelas = Kelas::create(array_merge($k, [
                'guru_id' => $guruIds[$i] ?? null,
                'tahun_ajaran_id' => $ta->id,
                'kapasitas' => 30,
            ]));
            $kelasIds[] = $kelas->id;
        }

        // Mata Pelajaran
        $mapelData = [
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam', 'kkm' => 75],
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan', 'kkm' => 75],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia', 'kkm' => 70],
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika', 'kkm' => 65],
            ['kode_mapel' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kkm' => 70],
            ['kode_mapel' => 'IPS', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kkm' => 70],
            ['kode_mapel' => 'SBK', 'nama_mapel' => 'Seni Budaya dan Keterampilan', 'kkm' => 75],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani', 'kkm' => 75],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris', 'kkm' => 65, 'is_muatan_lokal' => true],
        ];
        foreach ($kelasIds as $kelasId) {
            foreach ($mapelData as $m) {
                MataPelajaran::create(array_merge($m, ['kelas_id' => $kelasId, 'tahun_ajaran_id' => $ta->id, 'status_aktif' => true, 'is_muatan_lokal' => $m['is_muatan_lokal'] ?? false]));
            }
        }

        // Sample Siswa for each class
        $namaLaki = ['Andi', 'Budi', 'Cahyo', 'Deni', 'Eko', 'Fajar', 'Galih', 'Hadi'];
        $namaPerempuan = ['Ani', 'Bunga', 'Citra', 'Dina', 'Eka', 'Fitri', 'Gita', 'Hana'];

        $nis = 1001;
        foreach ($kelasIds as $kelasId) {
            for ($j = 0; $j < 5; $j++) {
                $isLaki = $j < 3;
                $nama = $isLaki ? $namaLaki[array_rand($namaLaki)] : $namaPerempuan[array_rand($namaPerempuan)];
                Siswa::create([
                    'nis' => (string)$nis++,
                    'nisn' => '00' . rand(10000000, 99999999),
                    'nama' => $nama . ' ' . fake()->lastName(),
                    'jenis_kelamin' => $isLaki ? 'L' : 'P',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => fake()->dateTimeBetween('-12 years', '-6 years'),
                    'agama' => 'Islam',
                    'alamat' => fake()->address(),
                    'nama_ayah' => fake()->name('male'),
                    'nama_ibu' => fake()->name('female'),
                    'kelas_id' => $kelasId,
                    'status' => 'aktif',
                    'tanggal_masuk' => '2025-07-15',
                ]);
            }
        }
    }
}
