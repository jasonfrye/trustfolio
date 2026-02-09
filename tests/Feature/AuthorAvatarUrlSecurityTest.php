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
            'max_testimonials' => 10,
        ]);
    }

    /** Test that javascript: URLs in author_avatar_url are rejected */
    public function test_javascript_url_in_author_avatar_url_is_rejected(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'javascript:alert("XSS")',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('author_avatar_url');
        $this->assertDatabaseMissing('testimonials', [
            'author_name' => 'Test User',
        ]);
    }

    /** Test that data: URLs in author_avatar_url are rejected */
    public function test_data_url_in_author_avatar_url_is_rejected(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'data:text/html,<script>alert("XSS")</script>',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('author_avatar_url');
        $this->assertDatabaseMissing('testimonials', [
            'author_name' => 'Test User',
        ]);
    }

    /** Test that only https: URLs are allowed in author_avatar_url */
    public function test_only_https_urls_are_allowed_in_author_avatar_url(): void
    {
        // Valid https URL should be accepted
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'https://example.com/avatar.jpg',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Test User',
            'author_avatar_url' => 'https://example.com/avatar.jpg',
        ]);
    }

    /** Test that http: URLs (non-https) are rejected */
    public function test_http_url_is_rejected_in_author_avatar_url(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'http://example.com/avatar.jpg',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('author_avatar_url');
        $this->assertDatabaseMissing('testimonials', [
            'author_name' => 'Test User',
        ]);
    }

    /** Test that null/empty author_avatar_url is accepted */
    public function test_null_author_avatar_url_is_accepted(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => null,
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Test User',
            'author_avatar_url' => null,
        ]);
    }

    /** Test that empty string author_avatar_url is accepted */
    public function test_empty_author_avatar_url_is_accepted(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => '',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasNoErrors();
        // Empty string is converted to null by Laravel's setAttribute method
        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Test User',
            'author_avatar_url' => null,
        ]);
    }

    /** Test that invalid URLs are rejected */
    public function test_invalid_url_is_rejected_in_author_avatar_url(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'not-a-valid-url',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('author_avatar_url');
        $this->assertDatabaseMissing('testimonials', [
            'author_name' => 'Test User',
        ]);
    }

    /** Test that file: URLs are rejected */
    public function test_file_url_is_rejected_in_author_avatar_url(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'author_title' => 'CEO',
            'author_avatar_url' => 'file:///etc/passwd',
            'content' => 'This is a great testimonial about the product. It works well.',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('author_avatar_url');
        $this->assertDatabaseMissing('testimonials', [
            'author_name' => 'Test User',
        ]);
    }
}
