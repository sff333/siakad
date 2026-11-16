<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_inertia_login_navigates_to_dashboard_with_authenticated_session(): void
    {
        $user = User::factory()->create();

        $this->withHeader('X-Inertia', 'true')->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(409)->assertHeader('X-Inertia-Location', route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_inertia_login_preserves_the_intended_destination(): void
    {
        $user = User::factory()->create();

        $this->withSession(['url.intended' => url('/siswa')])
            ->withHeader('X-Inertia', 'true')->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ])->assertStatus(409)->assertHeader('X-Inertia-Location', url('/siswa'))
            ->assertSessionMissing('url.intended');
    }

    public function test_failed_inertia_login_keeps_validation_errors_without_location_visit(): void
    {
        $user = User::factory()->create();

        $this->from('/login')->withHeader('X-Inertia', 'true')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ])->assertRedirect('/login')->assertSessionHasErrors('email')
            ->assertHeaderMissing('X-Inertia-Location');

        $this->assertGuest();
    }

    public function test_inertia_logout_navigates_to_login_and_invalidates_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withSession(['private_marker' => 'old-session'])
            ->withHeader('X-Inertia', 'true')->post('/logout')
            ->assertStatus(409)->assertHeader('X-Inertia-Location', route('login'))
            ->assertSessionMissing('private_marker');

        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
