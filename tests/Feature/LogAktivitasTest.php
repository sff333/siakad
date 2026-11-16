<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LogAktivitasTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_records_successful_changes_and_is_visible_only_to_administrators(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create();

        $this->get('/log-aktivitas')->assertRedirect('/login');
        $this->actingAs($operator)->get('/log-aktivitas')->assertForbidden();

        $this->actingAs($admin)->post('/pengguna', [
            'name' => 'Operator Baru',
            'email' => 'operator-baru@example.test',
            'role' => 'operator',
            'password' => 'ContohPassword123',
            'password_confirmation' => 'ContohPassword123',
        ])->assertRedirect('/pengguna');

        $this->from('/pengguna/create')->post('/pengguna', [])->assertSessionHasErrors('name');
        $this->assertDatabaseCount('log_aktivitas', 1);
        $this->get('/log-aktivitas')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('LogAktivitas/Index')
            ->has('logs.data', 1)
            ->where('logs.data.0.nama_pengguna', $admin->name)
            ->where('logs.data.0.peran', 'Administrator')
            ->where('logs.data.0.aktivitas', 'Pengguna berhasil ditambahkan.'));
    }

    public function test_log_records_successful_login_and_logout_only(): void
    {
        $user = User::factory()->create();

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertDatabaseCount('log_aktivitas', 0);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/login');

        $this->assertDatabaseCount('log_aktivitas', 2);
        $this->assertDatabaseHas('log_aktivitas', ['user_id' => $user->id, 'aktivitas' => 'Masuk ke aplikasi.']);
        $this->assertDatabaseHas('log_aktivitas', ['user_id' => $user->id, 'aktivitas' => 'Keluar dari aplikasi.']);
    }
}
