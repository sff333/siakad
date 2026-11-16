<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaIdentityValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_requires_nisn_and_returns_a_readable_message(): void
    {
        $this->actingAs(User::factory()->create());
        $student = Siswa::create(['nis' => '001', 'nama' => 'Siswa', 'jenis_kelamin' => 'L']);
        $this->put("/siswa/{$student->id}", [
            'nis' => '001', 'nisn' => '', 'nama' => 'Siswa', 'jenis_kelamin' => 'L', 'status' => 'aktif',
        ])->assertSessionHasErrors(['nisn' => 'NISN wajib diisi.']);
    }

    public function test_oversized_student_photo_has_an_indonesian_error(): void
    {
        $this->app->setLocale('id');
        $this->actingAs(User::factory()->create());
        $photo = \Illuminate\Http\UploadedFile::fake()->createWithContent('foto.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aRf8AAAAASUVORK5CYII='
        ))->size(2049);
        $this->post('/siswa', [
            'nis' => '001', 'nisn' => '0001234567', 'nama' => 'Siswa', 'jenis_kelamin' => 'L',
            'status' => 'aktif', 'foto' => $photo,
        ])->assertSessionHasErrors(['foto' => 'Ukuran foto maksimal 2048 KB.']);
        $this->assertDatabaseCount('siswa', 0);
    }

    public function test_duplicate_identifiers_return_specific_messages_and_do_not_create_students(): void
    {
        $this->actingAs(User::factory()->create());
        Siswa::create(['nis' => '001', 'nisn' => '0001234567', 'nama' => 'Siswa Lama', 'jenis_kelamin' => 'L']);
        $base = ['nama' => 'Siswa Baru', 'jenis_kelamin' => 'P', 'status' => 'aktif'];

        $this->from('/siswa/create')->post('/siswa', $base + ['nis' => '001', 'nisn' => '0007654321'])
            ->assertRedirect('/siswa/create')->assertSessionHasErrors([
                'nis' => 'NIS ganda. NIS ini sudah digunakan oleh siswa lain.',
            ])->assertSessionDoesntHaveErrors('nisn');
        $this->post('/siswa', $base + ['nis' => '002', 'nisn' => '0001234567'])
            ->assertSessionHasErrors(['nisn' => 'NISN ganda. NISN ini sudah digunakan oleh siswa lain.'])
            ->assertSessionDoesntHaveErrors('nis');
        $this->post('/siswa', $base + ['nis' => '001', 'nisn' => '0001234567'])
            ->assertSessionHasErrors(['nis', 'nisn']);
        $this->assertDatabaseCount('siswa', 1);

        $this->post('/siswa', $base + ['nis' => '002', 'nisn' => '0007654321'])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('siswa', 2);
    }

    public function test_edit_accepts_own_identifiers_and_rejects_another_students_nisn(): void
    {
        $this->actingAs(User::factory()->create());
        $student = Siswa::create(['nis' => '001', 'nisn' => '0001234567', 'nama' => 'Siswa Satu', 'jenis_kelamin' => 'L']);
        Siswa::create(['nis' => '002', 'nisn' => '0007654321', 'nama' => 'Siswa Dua', 'jenis_kelamin' => 'P']);
        $data = ['nis' => '001', 'nisn' => '0001234567', 'nama' => 'Nama Baru', 'jenis_kelamin' => 'L', 'status' => 'aktif'];
        $this->put("/siswa/{$student->id}", $data)->assertSessionHasNoErrors();
        $this->put("/siswa/{$student->id}", array_replace($data, ['nisn' => '0007654321']))
            ->assertSessionHasErrors(['nisn' => 'NISN ganda. NISN ini sudah digunakan oleh siswa lain.']);
        $this->assertSame('0001234567', $student->fresh()->nisn);
    }
}
