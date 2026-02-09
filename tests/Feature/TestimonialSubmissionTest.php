<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a creator with a known collection URL
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
     * Test: Form rejects empty submissions
     */
    public function test_form_rejects_empty_submissions(): void
    {
        $response = $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => '',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['author_name', 'content']);
    }

    /**
     * Test: Submissions save with 'pending' status
     */
    public function test_submissions_save_with_pending_status(): void
    {
        $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'John Doe',
            'author_email' => 'john@example.com',
            'author_title' => 'CEO',
            'content' => 'This is a great product! I use it every day and love it.',
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('testimonials', [
            'creator_id' => $this->creator->id,
            'author_name' => 'John Doe',
            'author_email' => 'john@example.com',
            'author_title' => 'CEO',
            'content' => 'This is a great product! I use it every day and love it.',
            'rating' => 5,
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Success message displays after submission
     */
    public function test_success_message_displays_after_submission(): void
    {
        $response = $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Jane Smith',
            'content' => 'This is a wonderful service. Highly recommended!',
            'rating' => 4,
        ]);

        $response->assertSessionHas('success');
        $this->assertStringContainsString(
            'Thank you for your testimonial',
            session('success')
        );
    }

    /**
     * Test: Rating defaults to 5 when not provided
     */
    public function test_rating_defaults_to_five_when_not_provided(): void
    {
        $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Bob Wilson',
            'content' => 'Amazing experience overall. Will come back again.',
            // rating not provided
        ]);

        $this->assertDatabaseHas('testimonials', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Bob Wilson',
            'rating' => 5,
        ]);
    }

    /**
     * Test: Creator relationship is correctly set
     */
    public function test_creator_relationship_is_correctly_set(): void
    {
        $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Alice Johnson',
            'content' => 'Fantastic product! Exactly what I needed.',
            'rating' => 5,
        ]);

        $testimonial = $this->creator->testimonials()->first();

        $this->assertNotNull($testimonial);
        $this->assertEquals($this->creator->id, $testimonial->creator_id);
        $this->assertTrue($testimonial->creator->is($this->creator));
    }

    /**
     * Test: Content validation requires minimum length
     */
    public function test_content_requires_minimum_length(): void
    {
        $response = $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Short Content',
            'content' => 'Too short',
        ]);

        $response->assertSessionHasErrors('content');
    }

    /**
     * Test: Rating must be between 1 and 5
     */
    public function test_rating_must_be_between_one_and_five(): void
    {
        $response = $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'Invalid Rating',
            'content' => 'This is a valid testimonial content for testing purposes.',
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test: Author email is optional
     */
    public function test_author_email_is_optional(): void
    {
        $this->post(route('collection.submit', $this->creator->collection_url), [
            'author_name' => 'No Email',
            'content' => 'This testimonial has no email provided by the author.',
        ]);

        $this->assertDatabaseHas('testimonials', [
            'creator_id' => $this->creator->id,
            'author_name' => 'No Email',
            'author_email' => null,
        ]);
    }
}
