<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialManagementTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;

    private Review $pendingTestimonial;

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

        // Create a pending testimonial for testing
        $this->pendingTestimonial = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'John Doe',
            'author_email' => 'john@example.com',
            'content' => 'This is a great product! I use it every day and love it.',
            'rating' => 5,
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Approve action changes status to 'approved' and sets approved_at
     */
    public function test_approve_action_changes_status_to_approved(): void
    {
        $this->actingAs($this->creator->user);

        $response = $this->post(route('testimonials.approve', $this->pendingTestimonial));

        $response->assertSessionHas('status');

        $this->pendingTestimonial->refresh();

        $this->assertEquals('approved', $this->pendingTestimonial->status);
        $this->assertNotNull($this->pendingTestimonial->approved_at);
        $this->assertDatabaseHas('reviews', [
            'id' => $this->pendingTestimonial->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test: Reject action changes status to 'rejected'
     */
    public function test_reject_action_changes_status_to_rejected(): void
    {
        $this->actingAs($this->creator->user);

        $response = $this->post(route('testimonials.reject', $this->pendingTestimonial));

        $response->assertSessionHas('status');

        $this->pendingTestimonial->refresh();

        $this->assertEquals('rejected', $this->pendingTestimonial->status);
        $this->assertDatabaseHas('reviews', [
            'id' => $this->pendingTestimonial->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test: Destroy action removes the testimonial
     */
    public function test_destroy_action_removes_the_testimonial(): void
    {
        $this->actingAs($this->creator->user);

        $response = $this->delete(route('testimonials.destroy', $this->pendingTestimonial));

        $response->assertSessionHas('status');

        $this->assertSoftDeleted('reviews', [
            'id' => $this->pendingTestimonial->id,
        ]);
    }

    /**
     * Test: Unauthorized users cannot approve testimonials
     */
    public function test_unauthorized_users_cannot_approve_testimonials(): void
    {
        // Create another user who doesn't own the testimonial
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $this->actingAs($otherUser);

        $response = $this->post(route('testimonials.approve', $this->pendingTestimonial));

        $response->assertStatus(403);

        // Status should still be pending
        $this->pendingTestimonial->refresh();
        $this->assertEquals('pending', $this->pendingTestimonial->status);
    }

    /**
     * Test: Unauthorized users cannot reject testimonials
     */
    public function test_unauthorized_users_cannot_reject_testimonials(): void
    {
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $this->actingAs($otherUser);

        $response = $this->post(route('testimonials.reject', $this->pendingTestimonial));

        $response->assertStatus(403);

        $this->pendingTestimonial->refresh();
        $this->assertEquals('pending', $this->pendingTestimonial->status);
    }

    /**
     * Test: Unauthorized users cannot destroy testimonials
     */
    public function test_unauthorized_users_cannot_destroy_testimonials(): void
    {
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $this->actingAs($otherUser);

        $response = $this->delete(route('testimonials.destroy', $this->pendingTestimonial));

        $response->assertStatus(403);

        $this->assertDatabaseHas('reviews', [
            'id' => $this->pendingTestimonial->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test: Guest users cannot manage testimonials
     */
    public function test_guest_users_cannot_manage_testimonials(): void
    {
        $response = $this->post(route('testimonials.approve', $this->pendingTestimonial));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('testimonials.reject', $this->pendingTestimonial));
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('testimonials.destroy', $this->pendingTestimonial));
        $response->assertRedirect(route('login'));

        $this->pendingTestimonial->refresh();
        $this->assertEquals('pending', $this->pendingTestimonial->status);
    }

    /**
     * Test: Approved testimonial can be rejected
     */
    public function test_approved_testimonial_can_be_rejected(): void
    {
        // First approve the testimonial
        $this->pendingTestimonial->approve();
        $this->pendingTestimonial->refresh();

        $this->actingAs($this->creator->user);

        // Now reject it
        $response = $this->post(route('testimonials.reject', $this->pendingTestimonial));

        $response->assertSessionHas('status');

        $this->pendingTestimonial->refresh();
        $this->assertEquals('rejected', $this->pendingTestimonial->status);
        $this->assertNotNull($this->pendingTestimonial->approved_at);
    }

    /**
     * Test: Rejected testimonial can be approved
     */
    public function test_rejected_testimonial_can_be_approved(): void
    {
        // First reject the testimonial
        $this->pendingTestimonial->reject();
        $this->pendingTestimonial->refresh();

        $this->actingAs($this->creator->user);

        // Now approve it
        $response = $this->post(route('testimonials.approve', $this->pendingTestimonial));

        $response->assertSessionHas('status');

        $this->pendingTestimonial->refresh();
        $this->assertEquals('approved', $this->pendingTestimonial->status);
        $this->assertNotNull($this->pendingTestimonial->approved_at);
    }

    /**
     * Test: Multiple testimonials can be managed by same creator
     */
    public function test_multiple_testimonials_can_be_managed(): void
    {
        $testimonial1 = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 1',
            'content' => 'First testimonial content here.',
            'rating' => 4,
            'status' => 'pending',
        ]);

        $testimonial2 = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 2',
            'content' => 'Second testimonial content here.',
            'rating' => 5,
            'status' => 'pending',
        ]);

        $this->actingAs($this->creator->user);

        // Approve first, reject second
        $this->post(route('testimonials.approve', $testimonial1));
        $this->post(route('testimonials.reject', $testimonial2));

        $testimonial1->refresh();
        $testimonial2->refresh();

        $this->assertEquals('approved', $testimonial1->status);
        $this->assertEquals('rejected', $testimonial2->status);
        $this->assertNotNull($testimonial1->approved_at);
    }
}
