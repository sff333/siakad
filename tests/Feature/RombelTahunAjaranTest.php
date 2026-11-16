<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\AnggotaRombel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RombelTahunAjaranTest extends TestCase
{
    use RefreshDatabase;

    private TahunAjaran $old;

    private TahunAjaran $new;

    private Kelas $kelas3;

    private Kelas $kelas4;

    private Siswa $siswa;

    private MataPelajaran $mapel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->old = TahunAjaran::create(['tahun_ajaran' => '2025/2026', 'semester' => 'Genap', 'is_active' => true]);
        $this->new = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil']);
        $this->kelas3 = Kelas::create(['nama_kelas' => 'Kelas 3A', 'tingkat' => 3, 'kapasitas' => 1]);
        $this->kelas4 = Kelas::create(['nama_kelas' => 'Kelas 4A', 'tingkat' => 4, 'kapasitas' => 1]);
        $this->siswa = Siswa::create(['nama' => 'Siswa Naik Kelas', 'nis' => 'NAIK001', 'jenis_kelamin' => 'P', 'kelas_id' => $this->kelas3->id]);
        $this->mapel = MataPelajaran::create(['kelas_id' => $this->kelas3->id, 'tahun_ajaran_id' => $this->old->id, 'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika']);
        Nilai::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id, 'mata_pelajaran_id' => $this->mapel->id,
            'tahun_ajaran_id' => $this->old->id, 'nilai_akhir' => 85, 'capaian_kompetensi' => 'Menguasai materi kelas 3.']);
    }

    private function activate(TahunAjaran $period): void
    {
        $this->post("/tahun-ajaran/{$period->id}/activate")->assertSessionHasNoErrors();
    }

    private function assign(Kelas $kelas, TahunAjaran $period, ?int $source = null)
    {
        return $this->patch("/kelas/{$kelas->id}/anggota", ['tahun_ajaran_id' => $period->id, 'perubahan' => [
            ['siswa_id' => $this->siswa->id, 'kelas_asal_id' => $source, 'kelas_tujuan_id' => $kelas->id],
        ]]);
    }

    public function test_new_year_starts_empty_and_shows_the_previous_class_for_manual_placement(): void
    {
        $this->activate($this->new);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => null]);
        $this->get("/rombel?kelas_id={$this->kelas4->id}")->assertInertia(fn (Assert $page) => $page
            ->where('tahunAjaran.id', $this->new->id)->has('siswaList', 1)
            ->where('siswaList.0.kelas_id', null)->where('siswaList.0.kelas_sebelumnya', 'Kelas 3A')
            ->where('siswaList.0.tahun_sebelumnya', '2025/2026'));
        $this->assign($this->kelas4, $this->new)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => $this->siswa->id, 'tahun_ajaran' => '2025/2026', 'kelas_id' => $this->kelas3->id]);
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => $this->siswa->id, 'tahun_ajaran' => '2026/2027', 'kelas_id' => $this->kelas4->id]);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas4->id]);
        $this->get("/rombel?kelas_id={$this->kelas3->id}")->assertInertia(fn (Assert $page) => $page->has('siswaList', 0));
        $this->activate($this->old);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id]);
        $this->activate($this->new);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas4->id]);
    }

    public function test_semester_change_keeps_the_same_annual_roster(): void
    {
        $this->activate($this->new);
        $this->assign($this->kelas4, $this->new)->assertSessionHasNoErrors();
        $genap = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        $this->activate($genap);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas4->id]);
        $this->assertSame(1, AnggotaRombel::where('siswa_id', $this->siswa->id)->where('tahun_ajaran', '2026/2027')->count());
        $this->get("/rombel?kelas_id={$this->kelas4->id}")->assertInertia(fn (Assert $page) => $page
            ->where('siswaList.0.kelas_id', $this->kelas4->id)->where('tahunAjaranList.0.id', $genap->id));
    }

    public function test_old_grades_reports_and_class_details_keep_the_old_class_after_promotion(): void
    {
        $this->activate($this->new);
        $this->assign($this->kelas4, $this->new)->assertSessionHasNoErrors();
        $this->get("/nilai?kelas_id={$this->kelas3->id}&mapel_id={$this->mapel->id}&tahun_ajaran_id={$this->old->id}")
            ->assertInertia(fn (Assert $page) => $page->has('siswaList', 1)->where("nilaiList.{$this->siswa->id}.capaian_kompetensi", 'Menguasai materi kelas 3.'));
        $this->get("/rapor/{$this->siswa->id}?tahun_ajaran_id={$this->old->id}")
            ->assertInertia(fn (Assert $page) => $page->where('siswa.kelas.nama_kelas', 'Kelas 3A')->has('nilai', 1));
        $this->get("/kelas/{$this->kelas3->id}?tahun_ajaran_id={$this->old->id}")
            ->assertInertia(fn (Assert $page) => $page->where('kelas.siswa.0.id', $this->siswa->id));
        $this->get("/rapor?kelas_id={$this->kelas3->id}&tahun_ajaran_id={$this->old->id}")
            ->assertInertia(fn (Assert $page) => $page->has('siswaList', 1));
        $this->get("/nilai?kelas_id={$this->kelas4->id}&mapel_id={$this->mapel->id}&tahun_ajaran_id={$this->new->id}")
            ->assertInertia(fn (Assert $page) => $page->has('siswaList', 1)->has('nilaiList', 0));
    }

    public function test_editing_a_historical_roster_does_not_change_the_active_class(): void
    {
        $this->activate($this->new);
        $this->assign($this->kelas4, $this->new)->assertSessionHasNoErrors();
        $this->patch("/kelas/{$this->kelas3->id}/anggota", ['tahun_ajaran_id' => $this->old->id, 'perubahan' => [
            ['siswa_id' => $this->siswa->id, 'kelas_asal_id' => $this->kelas3->id, 'kelas_tujuan_id' => null],
        ]])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas4->id]);
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => $this->siswa->id, 'tahun_ajaran' => '2025/2026', 'kelas_id' => null]);
    }

    public function test_old_year_capacity_does_not_fill_the_new_year(): void
    {
        $this->activate($this->new);
        $this->assign($this->kelas3, $this->new)->assertSessionHasNoErrors();
        $this->assertSame(2, AnggotaRombel::where('kelas_id', $this->kelas3->id)->count());
        $this->activate($this->old);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id]);
    }

    public function test_attendance_reads_and_writes_the_selected_year_roster(): void
    {
        Absensi::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id, 'tahun_ajaran_id' => $this->old->id, 'tanggal' => '2026-05-04', 'status' => 'Hadir']);
        $this->activate($this->new);
        $this->assign($this->kelas4, $this->new)->assertSessionHasNoErrors();
        $this->get("/absensi?kelas_id={$this->kelas3->id}&tahun_ajaran_id={$this->old->id}&tanggal=2026-05-04")
            ->assertInertia(fn (Assert $page) => $page->has('siswaList', 1)->where("absensi.{$this->siswa->id}.status", 'Hadir'));
        $this->post('/absensi', ['kelas_id' => $this->kelas3->id, 'tahun_ajaran_id' => $this->old->id, 'tanggal' => '2026-05-05', 'absensi' => [$this->siswa->id => 'Izin']])
            ->assertSessionHasNoErrors();
        $this->post('/absensi', ['kelas_id' => $this->kelas3->id, 'tahun_ajaran_id' => $this->new->id, 'tanggal' => '2026-10-30', 'absensi' => [$this->siswa->id => 'Hadir']])
            ->assertSessionHasErrors('absensi');
        $this->get("/absensi/rekap?kelas_id={$this->kelas3->id}&tahun_ajaran_id={$this->old->id}&bulan=5&tahun=2026")
            ->assertInertia(fn (Assert $page) => $page->has('rekap', 1)->where('rekap.0.hadir', 1)->where('rekap.0.izin', 1));
    }

    public function test_monthly_print_data_contains_daily_statuses_and_correct_totals(): void
    {
        foreach (['01' => 'Hadir', '02' => 'Sakit', '03' => 'Izin', '31' => 'Alpa'] as $day => $status) {
            Absensi::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id,
                'tahun_ajaran_id' => $this->old->id, 'tanggal' => "2026-05-$day", 'status' => $status]);
        }
        foreach (['2026-04-30', '2026-06-01'] as $date) {
            Absensi::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id,
                'tahun_ajaran_id' => $this->old->id, 'tanggal' => $date, 'status' => 'Hadir']);
        }
        $otherSemester = TahunAjaran::create(['tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil']);
        Absensi::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id,
            'tahun_ajaran_id' => $otherSemester->id, 'tanggal' => '2026-05-05', 'status' => 'Hadir']);

        $this->get("/absensi/rekap?kelas_id={$this->kelas3->id}&tahun_ajaran_id={$this->old->id}&bulan=5&tahun=2026")
            ->assertInertia(fn (Assert $page) => $page->where('jumlahHari', 31)
                ->where('rekap.0.harian.1', 'Hadir')->where('rekap.0.harian.2', 'Sakit')
                ->where('rekap.0.harian.3', 'Izin')->where('rekap.0.harian.31', 'Alpa')
                ->missing('rekap.0.harian.4')->missing('rekap.0.harian.5')
                ->where('rekap.0.hadir', 1)->where('rekap.0.sakit', 1)->where('rekap.0.izin', 1)
                ->where('rekap.0.alpa', 1)->where('rekap.0.total', 4));
    }

    public function test_print_calendar_adjusts_to_each_month_and_leap_year(): void
    {
        foreach ([[2026, 1, 31], [2026, 2, 28], [2024, 2, 29], [2026, 4, 30]] as [$year, $month, $days]) {
            $this->get("/absensi/rekap?kelas_id={$this->kelas3->id}&tahun_ajaran_id={$this->old->id}&bulan=$month&tahun=$year")
                ->assertInertia(fn (Assert $page) => $page->where('jumlahHari', $days)->where('rekap.0.total', 0));
        }
        $this->get('/absensi/rekap?bulan=13')->assertSessionHasErrors('bulan');
    }

    public function test_used_academic_year_cannot_be_renamed_or_deleted(): void
    {
        $this->put("/tahun-ajaran/{$this->old->id}", ['tahun_ajaran' => '2024/2025', 'semester' => 'Genap'])->assertSessionHasErrors('tahun_ajaran');
        $this->delete("/tahun-ajaran/{$this->old->id}")->assertSessionHasErrors('tahun_ajaran');
        $this->assertDatabaseHas('tahun_ajaran', ['id' => $this->old->id, 'tahun_ajaran' => '2025/2026']);
    }

    public function test_migration_preserves_legacy_current_placements_and_known_history(): void
    {
        $past = TahunAjaran::create(['tahun_ajaran' => '2024/2025', 'semester' => 'Genap']);
        Nilai::create(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas4->id, 'tahun_ajaran_id' => $past->id, 'mata_pelajaran_id' => $this->mapel->id, 'nilai_akhir' => 80]);
        Schema::drop('anggota_rombel');
        $migration = require database_path('migrations/2026_10_31_000002_create_anggota_rombel_table.php');
        $migration->up();
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => $this->siswa->id, 'tahun_ajaran' => '2024/2025', 'kelas_id' => $this->kelas4->id]);
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => $this->siswa->id, 'tahun_ajaran' => '2025/2026', 'kelas_id' => $this->kelas3->id]);
        $this->assertDatabaseCount('nilai', 2);
        $this->assertDatabaseHas('siswa', ['id' => $this->siswa->id, 'kelas_id' => $this->kelas3->id]);
    }
}
