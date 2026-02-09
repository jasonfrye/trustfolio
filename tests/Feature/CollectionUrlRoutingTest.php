<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionUrlRoutingTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a creator
        $user = User::factory()->create([
            'email' => 'creator@example.com',
        ]);
        $this->creator = Creator::create([
            'user_id' => $user->id,
            'display_name' => 'Test Creator',
            'collection_url' => 'test-creator',
            'widget_theme' => 'light',
            'show_branding' => true,
            'max_testimonials' => 10,
        ]);
    }

    /**
     * Test: Valid collection URL shows submission form
     */
    public function test_valid_collection_url_shows_submission_form(): void
    {
        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
        $response->assertViewIs('collection.show');
        $response->assertViewHas('creator');
    }

    /**
     * Test: Submission form displays creator's display name
     */
    public function test_submission_form_displays_creator_name(): void
    {
        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString($this->creator->display_name, $content);
    }

    /**
     * Test: Submission form displays creator's website
     */
    public function test_submission_form_displays_creator_website(): void
    {
        $this->creator->website = 'https://example.com';
        $this->creator->save();

        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('https://example.com', $content);
    }

    /**
     * Test: Invalid collection URL returns 404
     */
    public function test_invalid_collection_url_returns_404(): void
    {
        $response = $this->get(route('collection.show', 'nonexistent-creator'));

        $response->assertNotFound();
    }

    /**
     * Test: Invalid collection URL format returns 404
     */
    public function test_invalid_collection_url_format_returns_404(): void
    {
        // Test with various invalid formats
        $response = $this->get('/collection/---invalid-slug---');
        $response->assertNotFound();
    }

    /**
     * Test: Creator relationship is correctly resolved
     */
    public function test_creator_relationship_is_correctly_resolved(): void
    {
        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
        $viewData = $response->viewData('creator');

        $this->assertNotNull($viewData);
        $this->assertSame($this->creator->id, $viewData->id);
        $this->assertSame($this->creator->display_name, $viewData->display_name);
    }

    /**
     * Test: Submission form contains form fields
     */
    public function test_submission_form_contains_form_fields(): void
    {
        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();

        // Should contain form fields
        $this->assertStringContainsString('author_name', $content);
        $this->assertStringContainsString('content', $content);
        $this->assertStringContainsString('rating', $content);
    }

    /**
     * Test: Multiple creators have distinct collection URLs
     */
    public function test_multiple_creators_have_distinct_urls(): void
    {
        $user2 = User::factory()->create(['email' => 'another@example.com']);
        $creator2 = Creator::create([
            'user_id' => $user2->id,
            'display_name' => 'Another Creator',
            'collection_url' => 'another-creator',
        ]);

        // First creator's URL should still work
        $response1 = $this->get(route('collection.show', $this->creator->collection_url));
        $response1->assertOk();

        // Second creator's URL should work
        $response2 = $this->get(route('collection.show', $creator2->collection_url));
        $response2->assertOk();

        // Each should show their respective content
        $content1 = $response1->getContent();
        $content2 = $response2->getContent();

        $this->assertStringContainsString($this->creator->display_name, $content1);
        $this->assertStringContainsString($creator2->display_name, $content2);
    }

    /**
     * Test: Collection URL with special characters is slugified
     */
    public function test_collection_url_with_special_characters_is_slugified(): void
    {
        // Create creator with spaces and special chars in name
        $user = User::factory()->create(['email' => 'special@example.com']);
        $creator = Creator::create([
            'user_id' => $user->id,
            'display_name' => "Creator's Awesome Site!",
            'collection_url' => 'creators-awesome-site',
        ]);

        // Original URL should work
        $response = $this->get(route('collection.show', $creator->collection_url));
        $response->assertOk();
    }

    /**
     * Test: URL encoding works for collection URLs
     */
    public function test_url_encoding_works_for_collection_urls(): void
    {
        $response = $this->get('/collection/' . urlencode($this->creator->collection_url));

        $response->assertOk();
        $response->assertViewIs('collection.show');
    }

    /**
     * Test: Deleted creator URL returns 404
     */
    public function test_deleted_creator_url_returns_404(): void
    {
        $this->creator->delete();

        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertNotFound();
    }

    /**
     * Test: Route name is correct
     */
    public function test_route_name_is_correct(): void
    {
        $url = route('collection.show', $this->creator->collection_url);
        $this->assertStringContainsString('/collection/test-creator', $url);
    }
}
