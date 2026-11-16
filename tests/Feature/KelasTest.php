<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class KelasTest extends TestCase
{
    use RefreshDatabase;

    private Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $this->kelas = Kelas::create(['nama_kelas' => 'Kelas 1A', 'tingkat' => 1, 'rombel' => 'A', 'kapasitas' => 30]);
    }

    public function test_class_details_include_the_selected_class_and_its_related_data(): void
    {
        $guru = Guru::create(['nama' => 'Guru Kelas', 'jenis_kelamin' => 'P']);
        $tahun = TahunAjaran::getActive();
        $this->kelas->update(['guru_id' => $guru->id, 'tahun_ajaran_id' => $tahun->id]);
        $siswa = Siswa::create(['nis' => 'KELAS001', 'nama' => 'Siswa Kelas 1A', 'jenis_kelamin' => 'L', 'kelas_id' => $this->kelas->id]);
        $mapel = MataPelajaran::create(['kelas_id' => $this->kelas->id, 'tahun_ajaran_id' => $tahun->id, 'kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika']);
        Jadwal::create(['kelas_id' => $this->kelas->id, 'mata_pelajaran_id' => $mapel->id, 'guru_id' => $guru->id,
            'tahun_ajaran_id' => $tahun->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:00']);

        $this->get("/kelas/{$this->kelas->id}")->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Kelas/Show')->where('kelas.id', $this->kelas->id)->where('kelas.nama_kelas', 'Kelas 1A')
            ->where('kelas.guru.nama', 'Guru Kelas')->where('kelas.tahun_ajaran.semester', 'Ganjil')
            ->has('kelas.siswa', 1)->where('kelas.siswa.0.id', $siswa->id)
            ->has('kelas.jadwal', 1)->where('kelas.jadwal.0.mata_pelajaran.nama_mapel', 'Matematika')
            ->where('kelas.jadwal.0.guru.nama', 'Guru Kelas'));
    }

    public function test_edit_loads_the_selected_class(): void
    {
        $this->get("/kelas/{$this->kelas->id}/edit")->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Kelas/Edit')->where('kelas.id', $this->kelas->id)->where('kelas.nama_kelas', 'Kelas 1A'));
    }

    public function test_update_changes_only_the_selected_class(): void
    {
        $other = Kelas::create(['nama_kelas' => 'Kelas 2A', 'tingkat' => 2, 'kapasitas' => 30]);
        $this->put("/kelas/{$this->kelas->id}", ['nama_kelas' => 'Kelas 1B', 'tingkat' => 1, 'rombel' => 'B', 'kapasitas' => 32])
            ->assertSessionHasNoErrors()->assertRedirect('/kelas');
        $this->assertDatabaseHas('kelas', ['id' => $this->kelas->id, 'nama_kelas' => 'Kelas 1B', 'kapasitas' => 32]);
        $this->assertDatabaseHas('kelas', ['id' => $other->id, 'nama_kelas' => 'Kelas 2A', 'kapasitas' => 30]);
    }

    public function test_missing_class_returns_not_found_instead_of_empty_details(): void
    {
        $this->get('/kelas/999999')->assertNotFound();
        $this->get('/kelas/999999/edit')->assertNotFound();
    }

    public function test_delete_targets_the_selected_class(): void
    {
        $other = Kelas::create(['nama_kelas' => 'Kelas 2A', 'tingkat' => 2]);
        $this->delete("/kelas/{$this->kelas->id}")->assertRedirect('/kelas');
        $this->assertDatabaseMissing('kelas', ['id' => $this->kelas->id]);
        $this->assertDatabaseHas('kelas', ['id' => $other->id]);
    }
}
