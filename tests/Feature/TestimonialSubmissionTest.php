<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Creator $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->creator = Creator::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_testimonial_submission_creates_review(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Happy Customer',
            'email' => 'happy@example.com',
            'content' => 'This is an excellent product that I really enjoy using.',
            'rating' => 4,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Happy Customer',
            'is_private_feedback' => false,
            'rating' => 4,
        ]);
    }

    public function test_low_rating_testimonial_creates_review(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Unhappy Customer',
            'email' => 'unhappy@example.com',
            'content' => 'This product could use some improvement in many areas.',
            'rating' => 2,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Unhappy Customer',
            'is_private_feedback' => false,
            'rating' => 2,
        ]);
    }

    public function test_five_star_testimonial_creates_review(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Very Happy Customer',
            'email' => 'veryhappy@example.com',
            'content' => 'This is an absolutely amazing product I love it.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Very Happy Customer',
            'is_private_feedback' => false,
            'rating' => 5,
        ]);
    }

    public function test_ajax_returns_simple_success_response(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'JSON Test User',
            'email' => 'json@example.com',
            'content' => 'Testing the JSON response structure.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
    }

    public function test_private_feedback_excluded_from_widget(): void
    {
        Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'content' => 'This private feedback should not appear in the widget output.',
            'is_private_feedback' => true,
            'author_name' => 'Private Feedback Author',
        ]);

        WidgetSetting::create([
            'creator_id' => $this->creator->id,
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'cards',
            'limit' => 10,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => true,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertDontSee('This private feedback should not appear in the widget output.');
    }

    public function test_private_feedback_excluded_from_approved_scope(): void
    {
        $review = Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'is_private_feedback' => true,
        ]);

        $approvedReviews = Review::approved()->where('creator_id', $this->creator->id)->get();

        $this->assertFalse($approvedReviews->contains($review));
    }

    public function test_private_feedback_excluded_from_pending_scope(): void
    {
        $review = Review::factory()->create([
            'creator_id' => $this->creator->id,
            'status' => 'pending',
            'is_private_feedback' => true,
        ]);

        $pendingReviews = Review::pending()->where('creator_id', $this->creator->id)->get();

        $this->assertFalse($pendingReviews->contains($review));
    }
}
