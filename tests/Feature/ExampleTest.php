<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_home_page_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_guests_see_the_login_screen(): void
    {
        $this->get('/login')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
        );
    }

    public function test_guests_are_redirected_to_login_from_the_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_signed_in_users_skip_the_login_screen(): void
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/dashboard');
    }
}
