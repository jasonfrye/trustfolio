<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorAvatarUrlSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Creator $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'creator@example.com',
        ]);
        $this->creator = Creator::create([
            'user_id' => $this->user->id,
            'display_name' => 'Test Creator',
            'collection_url' => 'test-creator',
            'widget_theme' => 'light',
            'show_branding' => true,
        ]);
    }

    /** Test that author_avatar_url is not accepted through the collection form */
    public function test_author_avatar_url_is_ignored_in_submission(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'title' => 'CEO',
            'author_avatar_url' => 'https://example.com/avatar.jpg',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('reviews', [
            'author_name' => 'Test User',
            'author_avatar_url' => null,
        ]);
    }

    /** Test that reviews are created without avatar URL */
    public function test_review_created_without_avatar(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('reviews', [
            'author_name' => 'Test User',
            'author_avatar_url' => null,
        ]);
    }
}
