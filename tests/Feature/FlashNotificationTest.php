<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $version = app(\App\Http\Middleware\HandleInertiaRequests::class)
            ->version(\Illuminate\Http\Request::create('/'));
        $this->withHeader('X-Inertia-Version', $version ?? '');
    }

    public function test_student_update_redirect_delivers_success_and_updated_data(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $siswa = Siswa::create(['nis' => '123', 'nama' => 'Nama Lama', 'jenis_kelamin' => 'L']);

        $editUrl = "/siswa/{$siswa->id}/edit";
        $redirect = $this->from(url($editUrl))->withHeader('X-Inertia', 'true')->post("/siswa/{$siswa->id}", [
            '_method' => 'PUT', 'nis' => '123', 'nisn' => '0001234567', 'nama' => 'Nama Baru',
            'jenis_kelamin' => 'L', 'status' => 'aktif',
        ])->assertStatus(409)->assertHeader('X-Inertia-Location', url("/siswa/{$siswa->id}"))
            ->assertSessionHas('success', 'Buku Induk siswa berhasil diperbarui!');

        $cookie = $redirect->getCookie(config('session.cookie'));
        $this->withCookie($cookie->getName(), $cookie->getValue())->flushHeaders();
        $this->get("/siswa/{$siswa->id}")->assertOk()
            ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
                ->component('Siswa/Show')->where('siswa.nama', 'Nama Baru')
                ->where('flash.success', 'Buku Induk siswa berhasil diperbarui!')->has('flash.id'));
        $this->get("/siswa/{$siswa->id}")->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->where('flash.success', null));
    }

    public function test_invalid_student_update_returns_to_the_form_with_errors(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $siswa = Siswa::create(['nis' => '123', 'nama' => 'Nama Lama', 'jenis_kelamin' => 'L']);
        $editUrl = "/siswa/{$siswa->id}/edit";

        $redirect = $this->from(url($editUrl))->withHeader('X-Inertia', 'true')->post("/siswa/{$siswa->id}", [
            '_method' => 'PUT', 'nis' => '123', 'nisn' => '0001234567', 'nama' => '',
            'jenis_kelamin' => 'L', 'status' => 'aktif',
        ])->assertStatus(303)->assertRedirect($editUrl)->assertSessionHasErrors('nama');

        $cookie = $redirect->getCookie(config('session.cookie'));
        $response = $this->withCookie($cookie->getName(), $cookie->getValue())->get($editUrl);
        $response->assertOk()->assertJsonPath('component', 'Siswa/Edit')
            ->assertJsonStructure(['props' => ['errors' => ['nama']]])
            ->assertJsonPath('props.flash.success', null);
        $this->assertSame('Nama Lama', $siswa->fresh()->nama);
    }

    public function test_partial_reload_includes_new_notification_even_with_identical_message(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->withHeaders(['X-Inertia' => 'true', 'X-Inertia-Partial-Component' => 'Siswa/Index',
                'X-Inertia-Partial-Data' => 'siswa']);

        $first = $this->withSession(['success' => 'Berhasil disimpan'])->get('/siswa')
            ->assertOk()->assertJsonPath('props.flash.success', 'Berhasil disimpan');
        $second = $this->withSession(['success' => 'Berhasil disimpan'])->get('/siswa')
            ->assertOk()->assertJsonPath('props.flash.success', 'Berhasil disimpan');
        $this->assertNotSame($first->json('props.flash.id'), $second->json('props.flash.id'));
    }
}
