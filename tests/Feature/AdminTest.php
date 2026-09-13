<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard_and_promote_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Account Management');
        $this->actingAs($admin)->patch("/admin/users/{$user->id}/role", ['is_admin' => true])
            ->assertSessionHasNoErrors();

        $this->assertTrue($user->fresh()->is_admin);
    }

    public function test_admin_cannot_demote_or_delete_self(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->patch("/admin/users/{$admin->id}/role", ['is_admin' => false])
            ->assertSessionHasErrors('admin');
        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")
            ->assertSessionHasErrors('admin');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_admin' => true]);
    }
}
