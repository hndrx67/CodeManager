<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'fullname' => 'Ada Lovelace', 'username' => 'ada',
            'password' => 'secure-pass', 'password_confirmation' => 'secure-pass',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_regenerates_authenticated_session(): void
    {
        $user = User::factory()->create(['username' => 'grace', 'password' => 'secure-pass']);
        $this->post('/login', ['username' => 'grace', 'password' => 'secure-pass'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_render_profile_page(): void
    {
        $user = User::factory()->create(['profile_pic' => null]);

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertSee('Account Settings');
    }
}
