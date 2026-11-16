<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RombelTest extends TestCase
{
    use RefreshDatabase;

    private TahunAjaran $year;

    private Kelas $target;

    private Kelas $source;

    private Siswa $member;

    private Siswa $available;

    private Siswa $unassigned;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->year = TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $this->target = Kelas::create(['nama_kelas' => 'Kelas 2A', 'tingkat' => 2, 'kapasitas' => 2]);
        $this->source = Kelas::create(['nama_kelas' => 'Kelas 1A', 'tingkat' => 1, 'kapasitas' => 30]);
        $this->member = $this->student('Anggota', $this->target->id);
        $this->available = $this->student('Tersedia', $this->source->id);
        $this->unassigned = $this->student('Belum Kelas', null);
    }

    private function student(string $name, ?int $kelasId, string $status = 'aktif'): Siswa
    {
        return Siswa::create(['nama' => $name, 'nis' => $name, 'jenis_kelamin' => 'L', 'kelas_id' => $kelasId, 'status' => $status]);
    }

    private function change(Siswa $student, ?int $targetId): array
    {
        return ['siswa_id' => $student->id, 'kelas_asal_id' => $student->kelas_id, 'kelas_tujuan_id' => $targetId];
    }

    private function save(array $changes)
    {
        return $this->patch("/kelas/{$this->target->id}/anggota", ['tahun_ajaran_id' => $this->year->id, 'perubahan' => $changes]);
    }

    public function test_roster_page_contains_members_available_students_and_class_choices(): void
    {
        $this->student('Sudah Lulus', $this->source->id, 'lulus');
        $this->get("/rombel?kelas_id={$this->target->id}")->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Kelas/Anggota')->where('kelas.id', $this->target->id)
            ->has('kelasList', 2)->has('siswaList', 2));
        $this->get('/rombel')->assertOk()->assertInertia(fn (Assert $page) => $page->where('kelas', null)->has('siswaList', 0));
    }

    public function test_students_can_move_in_and_out_together_without_affecting_other_students(): void
    {
        $this->save([
            $this->change($this->member, null),
            $this->change($this->unassigned, $this->target->id),
        ])->assertSessionHasNoErrors()->assertRedirect("/rombel?kelas_id={$this->target->id}&tahun_ajaran_id={$this->year->id}");
        $this->assertDatabaseHas('siswa', ['id' => $this->member->id, 'kelas_id' => null, 'status' => 'aktif']);
        $this->assertDatabaseHas('siswa', ['id' => $this->available->id, 'kelas_id' => $this->source->id, 'nis' => 'Tersedia']);
        $this->assertDatabaseHas('siswa', ['id' => $this->unassigned->id, 'kelas_id' => $this->target->id]);
        $this->assertDatabaseCount('siswa', 3);
    }

    public function test_capacity_rejection_keeps_the_whole_batch_unchanged(): void
    {
        $second = $this->student('Belum Kelas Kedua', null);
        $this->save([
            $this->change($second, $this->target->id),
            $this->change($this->unassigned, $this->target->id),
        ])->assertSessionHasErrors('perubahan');
        $this->assertDatabaseHas('siswa', ['id' => $this->available->id, 'kelas_id' => $this->source->id]);
        $this->assertDatabaseHas('siswa', ['id' => $this->unassigned->id, 'kelas_id' => null]);
    }

    public function test_stale_membership_rejects_the_whole_batch(): void
    {
        $change = $this->change($this->unassigned, $this->target->id);
        $this->unassigned->update(['kelas_id' => $this->source->id]);
        $this->save([$this->change($this->member, null), $change])->assertSessionHasErrors('perubahan');
        $this->assertDatabaseHas('siswa', ['id' => $this->member->id, 'kelas_id' => $this->target->id]);
        $this->assertDatabaseHas('siswa', ['id' => $this->unassigned->id, 'kelas_id' => $this->source->id]);
    }

    public function test_inactive_students_cannot_be_moved(): void
    {
        $this->member->update(['status' => 'lulus']);
        $this->save([$this->change($this->member, null)])->assertSessionHasErrors('perubahan');
        $this->assertDatabaseHas('siswa', ['id' => $this->member->id, 'kelas_id' => $this->target->id]);
    }

    public function test_removal_from_an_unrelated_class_is_rejected(): void
    {
        $this->save([$this->change($this->available, null)])->assertSessionHasErrors('perubahan');
        $this->assertDatabaseHas('siswa', ['id' => $this->available->id, 'kelas_id' => $this->source->id]);
    }

    public function test_duplicate_student_ids_and_other_destination_classes_are_rejected(): void
    {
        $row = $this->change($this->unassigned, $this->target->id);
        $this->save([$row, $row])->assertSessionHasErrors('perubahan.0.siswa_id');
        $this->save([$this->change($this->member, $this->source->id)])->assertSessionHasErrors('perubahan.0.kelas_tujuan_id');
        $this->assertDatabaseHas('siswa', ['id' => $this->member->id, 'kelas_id' => $this->target->id]);
    }

    public function test_form_encoded_ids_are_accepted(): void
    {
        $this->save([['siswa_id' => (string) $this->member->id, 'kelas_asal_id' => (string) $this->target->id, 'kelas_tujuan_id' => null], ['siswa_id' => (string) $this->unassigned->id, 'kelas_asal_id' => null, 'kelas_tujuan_id' => (string) $this->target->id]])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('siswa', ['id' => $this->unassigned->id, 'kelas_id' => $this->target->id]);
    }

    public function test_students_in_another_rombel_cannot_be_added_directly(): void
    {
        $this->save([$this->change($this->available, $this->target->id)])->assertSessionHasErrors('perubahan');
        $this->assertDatabaseHas('siswa', ['id' => $this->available->id, 'kelas_id' => $this->source->id]);
    }

    public function test_roster_routes_require_authentication(): void
    {
        auth()->logout();
        $this->get('/rombel')->assertRedirect('/login');
        $this->save([$this->change($this->member, null)])->assertRedirect('/login');
    }
}
