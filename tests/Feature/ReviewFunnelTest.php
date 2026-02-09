<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFunnelTest extends TestCase
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
            'review_threshold' => 4,
            'google_review_url' => 'https://g.page/test/review',
        ]);
    }

    public function test_happy_path_rating_at_threshold(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Happy User',
            'email' => 'happy@example.com',
            'content' => 'This is an excellent product that I really enjoy using.',
            'rating' => 4,
        ]);

        $response->assertOk();
        $response->assertJson(['type' => 'review']);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Happy User',
            'is_private_feedback' => false,
        ]);
    }

    public function test_happy_path_rating_above_threshold(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Very Happy User',
            'email' => 'veryhappy@example.com',
            'content' => 'This is an absolutely amazing product I love it.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $response->assertJson(['type' => 'review']);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Very Happy User',
            'is_private_feedback' => false,
        ]);
    }

    public function test_unhappy_path_rating_below_threshold(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Unhappy User',
            'email' => 'unhappy@example.com',
            'content' => 'This product could use some improvement in many areas.',
            'rating' => 3,
        ]);

        $response->assertOk();
        $response->assertJson(['type' => 'private_feedback']);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Unhappy User',
            'is_private_feedback' => true,
        ]);
    }

    public function test_boundary_rating_equals_threshold_is_happy(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Boundary User',
            'email' => 'boundary@example.com',
            'content' => 'This product meets my expectations and works well.',
            'rating' => 4,
        ]);

        $response->assertOk();
        $response->assertJson(['type' => 'review']);
        $this->assertDatabaseHas('reviews', [
            'creator_id' => $this->creator->id,
            'author_name' => 'Boundary User',
            'is_private_feedback' => false,
        ]);
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

    public function test_prefill_works_when_enabled(): void
    {
        $this->creator->update(['prefill_enabled' => true]);

        $response = $this->get(route('collection.show', $this->creator->collection_url));

        $response->assertOk();
    }

    public function test_ajax_returns_correct_json_structure(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'JSON Test User',
            'email' => 'json@example.com',
            'content' => 'Testing the JSON response structure for happy path.',
            'rating' => 5,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['success', 'type', 'google_review_url', 'platforms']);
    }

    public function test_ajax_private_feedback_no_platform_data(): void
    {
        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'Private JSON User',
            'email' => 'private@example.com',
            'content' => 'Testing the JSON response for private feedback path.',
            'rating' => 2,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['success', 'type']);
        $response->assertJsonMissing(['google_review_url']);
    }
}
