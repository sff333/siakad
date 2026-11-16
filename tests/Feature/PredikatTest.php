<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\PengaturanPredikat;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PredikatTest extends TestCase
{
    use RefreshDatabase;

    private Kelas $kelas;

    private TahunAjaran $period;

    private Siswa $siswa;

    private MataPelajaran $mapel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->period = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $this->kelas = Kelas::create(['nama_kelas' => 'Kelas 3A', 'tingkat' => 3]);
        $this->siswa = Siswa::create(['nis' => 'P001', 'nama' => 'Siswa Predikat', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);
        $this->mapel = MataPelajaran::create(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id, 'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika']);
    }

    private function settings(array $override = []): array
    {
        return array_replace(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id,
            'mapel_id' => $this->mapel->id, 'min_a' => 95, 'min_b' => 85, 'min_c' => 70, 'min_d' => 50], $override);
    }

    private function grade(array $override = []): Nilai
    {
        return Nilai::create(array_replace(['siswa_id' => $this->siswa->id, 'kelas_id' => $this->kelas->id,
            'tahun_ajaran_id' => $this->period->id, 'mata_pelajaran_id' => $this->mapel->id,
            'nilai_akhir' => 90, 'predikat' => 'A', 'capaian_kompetensi' => 'Kompetensi tetap.'], $override));
    }

    public function test_settings_page_works_after_only_selecting_a_class_and_uses_defaults(): void
    {
        $this->get("/nilai/predikat?kelas_id={$this->kelas->id}")->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Nilai/Predikat')->where('kelas.id', $this->kelas->id)->where('tahunAjaran.id', $this->period->id)
            ->where('aturanPredikat.A', 90)->where('aturanPredikat.E', 0)->where('defaultPredikat.A', 90));
        $this->assertDatabaseCount('pengaturan_predikat', 0);
    }

    public function test_saving_settings_updates_existing_grades_without_changing_scores_or_notes(): void
    {
        $grade = $this->grade();
        $this->put('/nilai/predikat', $this->settings())->assertSessionHasNoErrors()
            ->assertRedirect('/nilai?kelas_id='.$this->kelas->id.'&tahun_ajaran_id='.$this->period->id.'&mapel_id='.$this->mapel->id);
        $this->assertDatabaseHas('pengaturan_predikat', ['kelas_id' => $this->kelas->id, 'min_a' => 95]);
        $this->assertDatabaseHas('nilai', ['id' => $grade->id, 'nilai_akhir' => 90, 'predikat' => 'B', 'capaian_kompetensi' => 'Kompetensi tetap.']);
        $this->get("/nilai?kelas_id={$this->kelas->id}&tahun_ajaran_id={$this->period->id}&mapel_id={$this->mapel->id}")
            ->assertInertia(fn (Assert $page) => $page->where('aturanPredikat.A', 95)->where("nilaiList.{$this->siswa->id}.predikat", 'B'));
        $this->get("/rapor/{$this->siswa->id}?tahun_ajaran_id={$this->period->id}")
            ->assertInertia(fn (Assert $page) => $page->where('nilai.0.predikat', 'B'));
    }

    public function test_new_grades_use_the_saved_thresholds(): void
    {
        $this->put('/nilai/predikat', $this->settings())->assertSessionHasNoErrors();
        $this->post('/nilai', ['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id,
            'mata_pelajaran_id' => $this->mapel->id, 'nilai' => [$this->siswa->id => ['tugas' => 90, 'uts' => 90, 'uas' => 90]]])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('nilai', ['siswa_id' => $this->siswa->id, 'nilai_akhir' => 90, 'predikat' => 'B']);
    }

    public function test_settings_are_isolated_by_class_and_semester(): void
    {
        $otherClass = Kelas::create(['nama_kelas' => 'Kelas 4A', 'tingkat' => 4]);
        $otherSemester = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        $otherMapel = MataPelajaran::create(['kode_mapel' => 'IPA', 'nama_mapel' => 'IPA']);
        $classGrade = $this->grade(['kelas_id' => $otherClass->id, 'mata_pelajaran_id' => $otherMapel->id]);
        $semesterGrade = $this->grade(['tahun_ajaran_id' => $otherSemester->id]);
        $this->put('/nilai/predikat', $this->settings())->assertSessionHasNoErrors();
        $this->assertSame('A', $classGrade->fresh()->predikat);
        $this->assertSame('A', $semesterGrade->fresh()->predikat);
        $this->assertSame(PengaturanPredikat::DEFAULTS, PengaturanPredikat::untuk($otherClass->id, $this->period->id));
        $this->assertSame(PengaturanPredikat::DEFAULTS, PengaturanPredikat::untuk($this->kelas->id, $otherSemester->id));
    }

    public function test_invalid_ranges_are_rejected_without_changing_existing_settings(): void
    {
        $this->put('/nilai/predikat', $this->settings())->assertSessionHasNoErrors();
        foreach ([['min_a' => 85], ['min_a' => 101], ['min_d' => 0], ['min_d' => -1], ['min_a' => 94.555], ['min_b' => 60]] as $invalid) {
            $this->put('/nilai/predikat', $this->settings($invalid))->assertSessionHasErrors();
        }
        $this->assertDatabaseHas('pengaturan_predikat', ['kelas_id' => $this->kelas->id, 'min_a' => 95, 'min_b' => 85, 'min_c' => 70, 'min_d' => 50]);
        $this->assertDatabaseCount('pengaturan_predikat', 1);
    }

    public function test_decimal_boundaries_and_null_scores_are_handled_correctly(): void
    {
        $grade = $this->grade(['nilai_akhir' => null, 'predikat' => null]);
        $this->put('/nilai/predikat', $this->settings(['min_a' => 89.5, 'min_b' => 79.25, 'min_c' => 65.5, 'min_d' => 50.25]))->assertSessionHasNoErrors();
        $rules = PengaturanPredikat::untuk($this->kelas->id, $this->period->id);
        foreach ([[100, 'A'], [89.5, 'A'], [89.49, 'B'], [79.25, 'B'], [79.24, 'C'], [65.5, 'C'], [65.49, 'D'], [50.25, 'D'], [50.24, 'E'], [0, 'E']] as [$score, $expected]) {
            $this->assertSame($expected, Nilai::getPredikat($score, $rules));
        }
        $this->assertNull($grade->fresh()->predikat);
    }

    public function test_settings_routes_require_login(): void
    {
        auth()->logout();
        $this->get("/nilai/predikat?kelas_id={$this->kelas->id}")->assertRedirect('/login');
        $this->put('/nilai/predikat', $this->settings())->assertRedirect('/login');
    }
}
