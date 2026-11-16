<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class NilaiRaporTest extends TestCase
{
    use RefreshDatabase;

    private TahunAjaran $year;

    private Kelas $kelas;

    private Siswa $siswa;

    private MataPelajaran $mapel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->year = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $this->kelas = Kelas::create(['nama_kelas' => 'Kelas 3A', 'tingkat' => 3, 'tahun_ajaran_id' => $this->year->id]);
        $this->siswa = Siswa::create(['nama' => 'Siswa Uji', 'nis' => 'UJI001', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);
        $this->mapel = MataPelajaran::create(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->year->id, 'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika']);
    }

    private function gradePayload(array $row): array
    {
        return [
            'kelas_id' => $this->kelas->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'tahun_ajaran_id' => $this->year->id,
            'nilai' => [$this->siswa->id => $row],
        ];
    }

    public function test_report_list_averages_final_scores_for_the_selected_period(): void
    {
        $otherPeriod = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        foreach ([['MAT2', 85], ['MAT3', 0], ['MAT4', null]] as [$code, $score]) {
            $subject = MataPelajaran::create(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->year->id,
                'kode_mapel' => $code, 'nama_mapel' => $code]);
            \App\Models\Nilai::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas->id,
                'mata_pelajaran_id' => $subject->id, 'tahun_ajaran_id' => $this->year->id, 'nilai_akhir' => $score]);
        }
        \App\Models\Nilai::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas->id,
            'mata_pelajaran_id' => $this->mapel->id, 'tahun_ajaran_id' => $otherPeriod->id, 'nilai_akhir' => 100]);
        Siswa::create(['nama' => 'Tanpa Nilai', 'nis' => 'KOSONG', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);

        $this->get('/rapor?'.http_build_query(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->year->id]))
            ->assertInertia(fn (Assert $page) => $page->component('Nilai/Rapor')
                ->where('siswaList.0.id', $this->siswa->id)
                ->where('siswaList.0.rata_rata', fn ($average) => (float) $average === 42.5)
                ->where('siswaList.1.rata_rata', null));
        $this->get('/rapor?'.http_build_query(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $otherPeriod->id]))
            ->assertInertia(fn (Assert $page) => $page
                ->where('siswaList.0.rata_rata', fn ($average) => (float) $average === 100.0));
    }

    private function extraPayload(array $rows): array
    {
        return [
            'kelas_id' => $this->kelas->id,
            'siswa_id' => $this->siswa->id,
            'tahun_ajaran_id' => $this->year->id,
            'kegiatan' => $rows,
        ];
    }

    public function test_grades_and_competency_are_saved_reloaded_and_shown_on_the_report(): void
    {
        $note = "Mampu menyelesaikan pecahan.\nPerlu latihan soal cerita.";
        $this->post('/nilai', $this->gradePayload(['tugas' => 80, 'uts' => 90, 'uas' => 100, 'capaian_kompetensi' => $note]))
            ->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('nilai', ['siswa_id' => $this->siswa->id, 'nilai_akhir' => 91, 'predikat' => 'A', 'capaian_kompetensi' => $note]);
        $this->get('/nilai?'.http_build_query(['kelas_id' => $this->kelas->id, 'mapel_id' => $this->mapel->id, 'tahun_ajaran_id' => $this->year->id]))
            ->assertInertia(fn (Assert $page) => $page->component('Nilai/Index')->where("nilaiList.{$this->siswa->id}.capaian_kompetensi", $note));
        $this->get("/rapor/{$this->siswa->id}?tahun_ajaran_id={$this->year->id}")
            ->assertInertia(fn (Assert $page) => $page->where('nilai.0.capaian_kompetensi', $note)->has('ekstrakurikuler', 0));
    }

    public function test_invalid_grades_do_not_partially_save_a_batch(): void
    {
        $other = Siswa::create(['nama' => 'Siswa Kedua', 'nis' => 'UJI002', 'jenis_kelamin' => 'P', 'kelas_id' => $this->kelas->id]);
        $payload = $this->gradePayload(['tugas' => 80]);
        $payload['nilai'][$other->id] = ['uas' => 101];
        $this->post('/nilai', $payload)->assertSessionHasErrors("nilai.{$other->id}.uas");
        $this->assertDatabaseCount('nilai', 0);
    }

    public function test_blank_rows_are_not_converted_into_zero_grades(): void
    {
        $this->post('/nilai', $this->gradePayload(['tugas' => '', 'uts' => '', 'uas' => '', 'capaian_kompetensi' => '']))->assertSessionHasNoErrors();
        $this->assertDatabaseCount('nilai', 0);
        $this->post('/nilai', $this->gradePayload(['tugas' => 0, 'uts' => 0, 'uas' => 0]))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('nilai', ['nilai_akhir' => 0, 'predikat' => 'E']);
    }

    public function test_students_outside_the_selected_class_cannot_be_updated(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'Kelas 4A', 'tingkat' => 4]);
        $payload = $this->gradePayload(['tugas' => 80]);
        $payload['kelas_id'] = $kelas->id;
        $payload['mata_pelajaran_id'] = MataPelajaran::create(['kelas_id' => $kelas->id, 'tahun_ajaran_id' => $this->year->id, 'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika'])->id;
        $this->post('/nilai', $payload)->assertSessionHasErrors('nilai');
        $payload = $this->extraPayload([['kegiatan' => 'Pramuka', 'predikat' => 'Baik']]);
        $payload['kelas_id'] = $kelas->id;
        $this->post('/nilai/ekstrakurikuler', $payload)->assertSessionHasErrors('siswa_id');
        $this->assertDatabaseCount('nilai', 0);
        $this->assertDatabaseCount('nilai_ekstrakurikuler', 0);
    }

    public function test_extracurriculars_are_saved_without_a_subject_and_only_shown_for_the_selected_semester(): void
    {
        $rows = [
            ['kegiatan' => 'Pramuka', 'predikat' => 'Baik', 'keterangan' => 'Aktif bekerja sama.'],
            ['kegiatan' => 'Seni Tari', 'predikat' => 'Sangat Baik', 'keterangan' => 'Menguasai gerakan dasar.'],
        ];
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload($rows))->assertSessionHasNoErrors();
        $this->assertDatabaseCount('nilai_ekstrakurikuler', 2);
        $this->get('/nilai?'.http_build_query(['mode' => 'ekstrakurikuler', 'kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->year->id]))
            ->assertInertia(fn (Assert $page) => $page->where('mode', 'ekstrakurikuler')->has('siswaList', 1)->has("ekstrakurikulerList.{$this->siswa->id}", 2));
        $this->get("/rapor/{$this->siswa->id}?tahun_ajaran_id={$this->year->id}")
            ->assertInertia(fn (Assert $page) => $page->has('ekstrakurikuler', 2)->where('ekstrakurikuler.1.kegiatan', 'Seni Tari'));
        $otherYear = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        $this->get("/rapor/{$this->siswa->id}?tahun_ajaran_id={$otherYear->id}")
            ->assertInertia(fn (Assert $page) => $page->has('ekstrakurikuler', 0)->has('nilai', 0));
    }

    public function test_replacing_or_clearing_activities_does_not_touch_other_periods(): void
    {
        $otherYear = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        NilaiEkstrakurikuler::create(['siswa_id' => $this->siswa->id, 'tahun_ajaran_id' => $otherYear->id, 'kegiatan' => 'Musik', 'predikat' => 'Baik']);
        $rows = [['kegiatan' => 'Pramuka', 'predikat' => 'Baik']];
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload($rows))->assertSessionHasNoErrors();
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload($rows))->assertSessionHasNoErrors();
        $this->assertDatabaseCount('nilai_ekstrakurikuler', 2);
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload([]))->assertSessionHasNoErrors();
        $this->assertDatabaseCount('nilai_ekstrakurikuler', 1);
        $this->assertDatabaseHas('nilai_ekstrakurikuler', ['kegiatan' => 'Musik', 'tahun_ajaran_id' => $otherYear->id]);
    }

    public function test_invalid_activity_does_not_remove_existing_data(): void
    {
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload([['kegiatan' => 'Pramuka', 'predikat' => 'Baik']]))->assertSessionHasNoErrors();
        $this->post('/nilai/ekstrakurikuler', $this->extraPayload([['kegiatan' => '', 'predikat' => 'Tidak valid']]))
            ->assertSessionHasErrors(['kegiatan.0.kegiatan', 'kegiatan.0.predikat']);
        $this->assertDatabaseHas('nilai_ekstrakurikuler', ['kegiatan' => 'Pramuka']);
    }

    public function test_dashboard_statistics_use_active_students_and_seven_days_of_recorded_attendance(): void
    {
        Siswa::create(['nama' => 'Sudah Lulus', 'nis' => 'LULUS', 'jenis_kelamin' => 'P', 'kelas_id' => $this->kelas->id, 'status' => 'lulus']);
        $today = today('Asia/Jakarta');
        foreach ([0, 6, 7] as $offset) {
            Absensi::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->year->id, 'tanggal' => $today->copy()->subDays($offset), 'status' => 'Hadir']);
        }
        $this->get('/dashboard')->assertInertia(fn (Assert $page) => $page->component('Dashboard')
            ->where('totalSiswa', 1)->where('siswaLaki', 1)->where('siswaPerempuan', 0)
            ->where('siswaPerKelas.0.siswa_count', 1)->where('absensiHariIni', 1)->where('hadirHariIni', 1)
            ->has('statistikAbsensi', 7)->where('statistikAbsensi.0.hadir', 1)->where('statistikAbsensi.1.hadir', 0)
            ->where('statistikAbsensi.6.tanggal', $today->toDateString())->where('statistikAbsensi.6.hadir', 1));
    }
}
