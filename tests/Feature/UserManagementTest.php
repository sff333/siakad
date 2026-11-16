<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_operator_can_use_dashboard_but_cannot_manage_users(): void
    {
        $operator = User::factory()->create()->refresh();
        $this->assertSame('operator', $operator->role);

        $this->actingAs($operator)->get('/dashboard')->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('auth.can_manage_users', false));
        $this->get('/pengguna')->assertForbidden();
        $this->get('/pengguna/create')->assertForbidden();
        $this->post('/pengguna', ['role' => 'admin'])->assertForbidden();
        $this->get("/pengguna/{$operator->id}/edit")->assertForbidden();
        $this->put("/pengguna/{$operator->id}", ['role' => 'admin'])->assertForbidden();
        $this->patch("/pengguna/{$operator->id}/status", ['is_active' => false])->assertForbidden();
    }

    public function test_administrator_can_create_and_edit_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/pengguna')->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Pengguna/Index')
                ->where('auth.can_manage_users', true)->missing('users.data.0.password'));

        $data = ['name' => 'Operator', 'email' => 'operator@example.test', 'role' => 'operator'];
        $this->post('/pengguna', $data + [
            'password' => 'Operator123',
            'password_confirmation' => 'Operator123',
        ])->assertRedirect('/pengguna');
        $operator = User::where('email', $data['email'])->firstOrFail();
        $this->assertTrue(Hash::check('Operator123', $operator->password));

        $this->put("/pengguna/{$operator->id}", array_replace($data, ['role' => 'admin', 'password' => '']))
            ->assertRedirect('/pengguna');
        $this->assertSame('admin', $operator->refresh()->role);
        $this->assertTrue(Hash::check('Operator123', $operator->password));

        $this->actingAs($operator)->get('/pengguna')->assertOk();
    }

    public function test_administrator_cannot_demote_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->put("/pengguna/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'operator',
        ])->assertSessionHasErrors('role');
        $this->assertSame('admin', $admin->refresh()->role);
    }

    public function test_administrator_can_disable_and_reenable_an_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create();
        $url = "/pengguna/{$operator->id}/status";
        $credentials = ['email' => $operator->email, 'password' => 'password'];
        $rememberToken = $operator->getRememberToken();

        $this->actingAs($admin)->from('/pengguna')->patch($url, ['is_active' => false])
            ->assertRedirect('/pengguna');
        $this->assertFalse($operator->refresh()->is_active);
        $this->assertNotSame($rememberToken, $operator->getRememberToken());

        $this->actingAs($operator)->get('/dashboard')->assertRedirect('/login');
        $this->assertGuest();
        $this->from('/login')->post('/login', $credentials)->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($admin)->from('/pengguna')->patch($url, ['is_active' => true])
            ->assertRedirect('/pengguna');
        $this->assertTrue($operator->refresh()->is_active);

        $this->post('/logout');
        $this->post('/login', $credentials)->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($operator);
    }

    public function test_administrator_cannot_disable_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->patch("/pengguna/{$admin->id}/status", ['is_active' => false])
            ->assertSessionHasErrors('is_active');
        $this->assertTrue($admin->refresh()->is_active);
    }
}
