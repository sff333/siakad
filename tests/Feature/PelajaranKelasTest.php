<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PelajaranKelasTest extends TestCase
{
    use RefreshDatabase;

    private Kelas $kelas;

    private Kelas $other;

    private TahunAjaran $period;

    private TahunAjaran $otherPeriod;

    private MataPelajaran $mapel;

    private MataPelajaran $foreign;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->period = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $this->otherPeriod = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap']);
        $this->kelas = Kelas::create(['nama_kelas' => 'Kelas 1A', 'tingkat' => 1]);
        $this->other = Kelas::create(['nama_kelas' => 'Kelas 2A', 'tingkat' => 2]);
        $this->mapel = MataPelajaran::create($this->subjectData());
        $this->foreign = MataPelajaran::create($this->subjectData(['kelas_id' => $this->other->id]));
    }

    private function subjectData(array $overrides = []): array
    {
        return array_replace(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id,
            'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika', 'kkm' => 70, 'status_aktif' => true], $overrides);
    }

    private function scheduleData(array $overrides = []): array
    {
        return array_replace(['tahun_ajaran_id' => $this->period->id, 'mata_pelajaran_id' => $this->mapel->id,
            'hari' => 'Senin', 'jam_mulai' => '07:30', 'jam_selesai' => '09:00'], $overrides);
    }

    public function test_subject_codes_are_unique_only_within_the_same_class_and_period(): void
    {
        $this->post("/kelas/{$this->kelas->id}/mapel", $this->subjectData())->assertSessionHasErrors('kode_mapel');
        $this->post("/kelas/{$this->kelas->id}/mapel", $this->subjectData(['tahun_ajaran_id' => $this->otherPeriod->id]))->assertSessionHasNoErrors();
        $this->assertDatabaseCount('mata_pelajaran', 3);
    }

    public function test_subject_edit_is_local_to_the_class_and_honors_unchecked_flags(): void
    {
        $this->put("/kelas/{$this->kelas->id}/mapel/{$this->mapel->id}", $this->subjectData(['nama_mapel' => 'Matematika Kelas 1', 'status_aktif' => false, 'is_muatan_lokal' => false]))
            ->assertSessionHasNoErrors();
        $this->assertSame('Matematika', $this->foreign->fresh()->nama_mapel);
        $this->assertFalse($this->mapel->fresh()->status_aktif);
        $this->get("/kelas/{$this->kelas->id}/mapel/{$this->foreign->id}/edit")->assertNotFound();
        $this->put("/kelas/{$this->kelas->id}/mapel/{$this->foreign->id}", $this->subjectData())->assertNotFound();
        $this->delete("/kelas/{$this->kelas->id}/mapel/{$this->foreign->id}")->assertNotFound();
    }

    public function test_class_tabs_show_only_subjects_and_schedules_for_the_selected_period(): void
    {
        $future = MataPelajaran::create($this->subjectData(['tahun_ajaran_id' => $this->otherPeriod->id]));
        Jadwal::create([...$this->scheduleData(), 'kelas_id' => $this->kelas->id]);
        Jadwal::create([...$this->scheduleData(['mata_pelajaran_id' => $this->foreign->id]), 'kelas_id' => $this->other->id]);
        Jadwal::create([...$this->scheduleData(['mata_pelajaran_id' => $future->id, 'tahun_ajaran_id' => $this->otherPeriod->id]), 'kelas_id' => $this->kelas->id]);
        $this->get("/kelas/{$this->kelas->id}?tab=mapel&tahun_ajaran_id={$this->period->id}")
            ->assertInertia(fn (Assert $page) => $page->where('tab', 'mapel')->has('mapelList', 1)
                ->where('mapelList.0.id', $this->mapel->id)->has('kelas.jadwal', 1)
                ->where('kelas.jadwal.0.mata_pelajaran_id', $this->mapel->id));
    }

    public function test_schedule_create_offers_only_subjects_from_the_class_and_period(): void
    {
        MataPelajaran::create($this->subjectData(['tahun_ajaran_id' => $this->otherPeriod->id]));
        $this->get("/kelas/{$this->kelas->id}/jadwal/create?tahun_ajaran_id={$this->period->id}")
            ->assertInertia(fn (Assert $page) => $page->component('Jadwal/Create')->where('kelas.id', $this->kelas->id)
                ->has('mapelList', 1)->where('mapelList.0.id', $this->mapel->id));
        $this->post("/kelas/{$this->kelas->id}/jadwal", $this->scheduleData(['mata_pelajaran_id' => $this->foreign->id]))->assertSessionHasErrors('mata_pelajaran_id');
        $this->post("/kelas/{$this->kelas->id}/jadwal", $this->scheduleData(['tahun_ajaran_id' => $this->otherPeriod->id]))->assertSessionHasErrors('mata_pelajaran_id');
        $this->assertDatabaseCount('jadwal', 0);
        $this->post("/kelas/{$this->kelas->id}/jadwal", $this->scheduleData())->assertSessionHasNoErrors();
        $this->assertDatabaseHas('jadwal', ['kelas_id' => $this->kelas->id, 'mata_pelajaran_id' => $this->mapel->id, 'tahun_ajaran_id' => $this->period->id]);
    }

    public function test_foreign_schedules_cannot_be_edited_or_deleted_through_another_class(): void
    {
        $schedule = Jadwal::create([...$this->scheduleData(['mata_pelajaran_id' => $this->foreign->id]), 'kelas_id' => $this->other->id]);
        $this->get("/kelas/{$this->kelas->id}/jadwal/{$schedule->id}/edit")->assertNotFound();
        $this->put("/kelas/{$this->kelas->id}/jadwal/{$schedule->id}", $this->scheduleData())->assertNotFound();
        $this->delete("/kelas/{$this->kelas->id}/jadwal/{$schedule->id}")->assertNotFound();
        $this->assertDatabaseCount('jadwal', 1);
    }

    public function test_grade_input_filters_and_validates_subjects_by_class(): void
    {
        $student = Siswa::create(['nis' => 'S001', 'nama' => 'Siswa Uji', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);
        $this->get("/nilai?kelas_id={$this->kelas->id}&tahun_ajaran_id={$this->period->id}&mapel_id={$this->foreign->id}")
            ->assertInertia(fn (Assert $page) => $page->has('mapelList', 1)->where('mapelList.0.id', $this->mapel->id)->where('mapel_id', null));
        $this->post('/nilai', ['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id, 'mata_pelajaran_id' => $this->foreign->id,
            'nilai' => [$student->id => ['tugas' => 90]]])->assertSessionHasErrors('mata_pelajaran_id');
        $this->assertDatabaseCount('nilai', 0);
    }

    public function test_subjects_used_by_grades_cannot_be_deleted(): void
    {
        $student = Siswa::create(['nis' => 'S001', 'nama' => 'Siswa Uji', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);
        Nilai::create(['siswa_id' => $student->id, 'kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $this->period->id, 'mata_pelajaran_id' => $this->mapel->id, 'nilai_akhir' => 90]);
        $this->delete("/kelas/{$this->kelas->id}/mapel/{$this->mapel->id}")->assertSessionHasErrors('mapel');
        $this->assertDatabaseCount('nilai', 1);
        $this->assertDatabaseHas('mata_pelajaran', ['id' => $this->mapel->id]);
    }

    public function test_old_global_menu_links_redirect_to_classes(): void
    {
        $this->get('/mapel')->assertRedirect('/kelas');
        $this->get('/jadwal')->assertRedirect('/kelas');
        $this->get("/jadwal?kelas_id={$this->kelas->id}")->assertRedirect("/kelas/{$this->kelas->id}?tahun_ajaran_id={$this->period->id}&tab=jadwal");
    }
}
