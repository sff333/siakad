<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CetakDataSekolahTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_recap_is_authorized_filtered_and_not_paginated(): void
    {
        $url = route('siswa.rekap');
        $this->get($url)->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
        $kelas = \App\Models\Kelas::create(['nama_kelas' => 'Kelas 1A', 'tingkat' => 1, 'rombel' => 'A']);
        for ($i = 1; $i <= 12; $i++) {
            Siswa::create(['nama' => "Peserta Rekap {$i}", 'jenis_kelamin' => 'L', 'status' => 'aktif', 'kelas_id' => $kelas->id]);
        }
        Siswa::create(['nama' => 'Peserta Rekap Lulus', 'jenis_kelamin' => 'P', 'status' => 'lulus', 'kelas_id' => $kelas->id]);
        Siswa::create(['nama' => 'Peserta Rekap Luar Kelas', 'jenis_kelamin' => 'P', 'status' => 'aktif']);
        Siswa::create(['nama' => 'Nama Tidak Cocok', 'jenis_kelamin' => 'P', 'status' => 'aktif', 'kelas_id' => $kelas->id]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('siswa.rekap', ['search' => 'Peserta Rekap', 'kelas_id' => $kelas->id, 'status' => 'aktif']))
            ->assertOk()->assertSee('data-rekap-siswa', false)->assertSee('A4 landscape')
            ->assertSee('Peserta Rekap 12')->assertViewHas('siswa', fn ($rows) => $rows->count() === 12)
            ->assertDontSee('Peserta Rekap Lulus')->assertDontSee('Peserta Rekap Luar Kelas')->assertDontSee('Nama Tidak Cocok');
        $this->get(route('siswa.rekap', ['search' => 'tidak-ada']))->assertOk()->assertSee('Tidak ada data siswa.');
    }

    public function test_only_administrators_can_print_individual_records_and_filtered_teacher_recap(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create();
        $siswa = Siswa::create([
            'nama' => 'Siswa Contoh',
            'jenis_kelamin' => 'P',
            'nama_panggilan' => 'Ayu',
            'tanggal_lahir' => '2016-08-12',
            'asal_sekolah' => 'TK Contoh',
            'hubungan_wali' => 'Paman',
            'foto' => 'siswa/contoh.jpg',
        ]);
        $guru = Guru::create([
            'nama' => 'Guru Aktif Contoh',
            'status_aktif' => true,
            'pendidikan_terakhir' => 'S2',
            'foto' => 'guru/contoh.jpg',
        ]);
        Guru::create(['nama' => 'Guru Nonaktif Contoh', 'status_aktif' => false]);

        $siswaUrl = route('siswa.cetak', $siswa);
        $guruUrl = route('guru.cetak', $guru);
        $rekapUrl = route('guru.rekap', ['status' => 'aktif']);

        $this->actingAs($operator);
        foreach ([$siswaUrl, $guruUrl, $rekapUrl] as $url) {
            $this->get($url)->assertForbidden();
        }

        $this->actingAs($admin);
        $this->get($siswaUrl)->assertOk()->assertSee('Biodata Siswa')->assertSee($siswa->nama)
            ->assertSee('data-student-biodata', false)->assertSee('A. Identitas Siswa')
            ->assertSee('Ayu')->assertSee('12 Agustus 2016')->assertSee('TK Contoh')->assertSee('Paman')
            ->assertSee('/storage/siswa/contoh.jpg')->assertDontSee('<table', false);
        $this->get($guruUrl)->assertOk()->assertSee('Biodata Guru')->assertSee($guru->nama)
            ->assertSee('data-biodata', false)->assertSee('Identitas dan data tenaga pendidik')
            ->assertSee('class="person-photo"', false)->assertSee('A. Identitas Guru')
            ->assertDontSee('class="bio-columns--student"', false)
            ->assertSee('Kepegawaian')->assertSee('S2')->assertSee('/storage/guru/contoh.jpg')
            ->assertDontSee('<table', false);
        $this->get($rekapUrl)->assertOk()->assertSee('Rekap Data Guru')
            ->assertSee('A4 landscape')->assertSee($guru->nama)->assertDontSee('Guru Nonaktif Contoh');
    }
}
