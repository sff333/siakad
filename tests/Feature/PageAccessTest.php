<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PageAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_revoke_operator_permissions(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'page_permissions' => []]);
        $data = ['name' => 'Operator Terbatas', 'email' => 'terbatas@example.test', 'role' => 'operator',
            'page_permissions' => ['siswa', 'rekap-presensi']];
        $this->actingAs($admin)->post('/pengguna', $data + ['password' => 'password123', 'password_confirmation' => 'password123'])
            ->assertRedirect('/pengguna');
        $operator = User::where('email', $data['email'])->firstOrFail();
        $this->assertSame(['siswa', 'rekap-presensi'], $operator->page_permissions);
        $this->get("/pengguna/{$operator->id}/edit")->assertInertia(fn (Assert $page) => $page
            ->where('account.page_permissions', ['siswa', 'rekap-presensi'])->has('pages.siswa'));

        $this->actingAs($operator)->get('/siswa')->assertOk();
        $this->get('/absensi/rekap')->assertOk();
        foreach (['/guru', '/absensi', '/pengguna', '/log-aktivitas', '/siswa/rekap', '/guru/import/template'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->post('/guru', ['nama' => 'Tidak Boleh', 'jenis_kelamin' => 'L'])->assertForbidden();
        $this->assertDatabaseMissing('guru', ['nama' => 'Tidak Boleh']);
        $this->put("/pengguna/{$operator->id}", $data + ['role' => 'admin'])->assertForbidden();
        $this->assertSame('operator', $operator->fresh()->role);

        $this->actingAs($admin)->put("/pengguna/{$operator->id}", array_replace($data, ['page_permissions' => []]))
            ->assertRedirect('/pengguna');
        $this->actingAs($operator->fresh())->get('/siswa')->assertForbidden();
        $this->get('/dashboard')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')->where('restricted', true)->missing('totalSiswa')->missing('userCounts')
            ->where('auth.allowed_pages', []));
        $this->get('/beranda')->assertNotFound();
    }

    public function test_unknown_permissions_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))->post('/pengguna', [
            'name' => 'Operator', 'email' => 'invalid@example.test', 'role' => 'operator',
            'password' => 'password123', 'password_confirmation' => 'password123',
            'page_permissions' => ['pengguna'],
        ])->assertSessionHasErrors('page_permissions.0');
        $this->assertDatabaseMissing('users', ['email' => 'invalid@example.test']);
    }

    public function test_operator_without_dashboard_access_can_login_and_logout(): void
    {
        $user = User::factory()->create(['page_permissions' => []]);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/dashboard');
        $this->get('/login')->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertOk();
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_only_admin_dashboard_receives_account_counts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create();
        User::factory()->create();

        $this->actingAs($admin)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->where('userCounts.admin', 1)->where('userCounts.operator', 2));
        $this->actingAs($operator)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->where('userCounts', null));
    }

    public function test_legacy_operators_keep_existing_access_and_class_aliases_are_protected(): void
    {
        $legacy = User::factory()->create();
        $this->assertSame(array_keys(User::PAGES), $legacy->allowedPages());
        $this->actingAs($legacy)->get('/siswa')->assertOk();
        $limited = User::factory()->create(['page_permissions' => ['siswa']]);
        $this->actingAs($limited);
        foreach (['/kelas', '/rombel', '/mapel', '/jadwal', '/nilai/predikat', '/rapor'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->post('/nilai/ekstrakurikuler', [])->assertForbidden();
        $this->post('/rapor/kop-surat', [])->assertForbidden();
    }
}
