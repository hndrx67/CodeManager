<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnippetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_save_a_snippet(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/snippets', [
            'title' => 'Hello', 'language' => 'php', 'description' => 'Example', 'code' => '<?php echo "hi";',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('snippets', ['user_id' => $user->id, 'title' => 'Hello']);
    }

    public function test_user_cannot_update_another_users_snippet(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $snippet = $owner->snippets()->create(['title' => 'Private', 'language' => 'text', 'code' => 'secret']);
        $this->actingAs($intruder)->put("/snippets/{$snippet->id}", [
            'title' => 'Stolen', 'language' => 'text', 'description' => '', 'code' => 'changed',
        ])->assertForbidden();
    }

    public function test_category_filter_only_displays_matching_snippets(): void
    {
        $user = User::factory()->create();
        $user->snippets()->create(['title' => 'PHP Example', 'language' => 'php', 'code' => 'echo 1;']);
        $user->snippets()->create(['title' => 'Python Example', 'language' => 'python', 'code' => 'print(1)']);

        $this->actingAs($user)->get('/snippets?category=php')
            ->assertOk()
            ->assertSee('PHP Example')
            ->assertDontSee('Python Example');
    }

    public function test_only_public_snippets_appear_on_landing_and_home_feeds(): void
    {
        $author = User::factory()->create();
        $viewer = User::factory()->create();
        $author->snippets()->create(['title' => 'Shared Example', 'language' => 'php', 'code' => 'public', 'is_public' => true]);
        $author->snippets()->create(['title' => 'Private Example', 'language' => 'php', 'code' => 'private', 'is_public' => false]);

        $this->get('/')->assertOk()->assertSee('Shared Example')->assertDontSee('Private Example');
        $this->actingAs($viewer)->get('/dashboard')->assertOk()->assertSee('Shared Example')->assertDontSee('Private Example');
    }

    public function test_user_can_change_snippet_sharing_status(): void
    {
        $user = User::factory()->create();
        $snippet = $user->snippets()->create(['title' => 'Example', 'language' => 'text', 'code' => 'code']);

        $this->actingAs($user)->put("/snippets/{$snippet->id}", [
            'title' => 'Example', 'language' => 'text', 'description' => '', 'code' => 'code', 'is_public' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertTrue($snippet->fresh()->is_public);
    }

    public function test_edit_form_uses_current_origin_path_instead_of_an_absolute_url(): void
    {
        $user = User::factory()->create();
        $user->snippets()->create(['title' => 'Example', 'language' => 'text', 'code' => 'code']);

        $this->actingAs($user)->get('/snippets')
            ->assertOk()
            ->assertSee("window.location.pathname.replace(/\\/$/,'')", false)
            ->assertDontSee("editForm').action='http", false);
    }
}
