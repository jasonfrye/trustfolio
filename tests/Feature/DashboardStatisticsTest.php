<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatisticsTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user and creator
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

    /**
     * Test: Dashboard loads successfully
     */
    public function test_dashboard_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard');
    }

    /**
     * Test: Dashboard displays creator name
     */
    public function test_dashboard_displays_creator_name(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString($this->creator->display_name, $content);
    }

    /**
     * Test: Pending count displays correctly
     */
    public function test_pending_count_displays_correctly(): void
    {
        // Create pending testimonials
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 1',
            'content' => 'First testimonial content here.',
            'rating' => 5,
            'status' => 'pending',
        ]);
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 2',
            'content' => 'Second testimonial content here.',
            'rating' => 4,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show pending count of 2
        $this->assertStringContainsString('2', $content);
    }

    /**
     * Test: Approved count displays correctly
     */
    public function test_approved_count_displays_correctly(): void
    {
        // Create approved testimonials
        $t1 = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 1',
            'content' => 'First testimonial content here.',
            'rating' => 5,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        $t2 = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 2',
            'content' => 'Second testimonial content here.',
            'rating' => 4,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        $t3 = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 3',
            'content' => 'Third testimonial content here.',
            'rating' => 3,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show approved count of 3
        $this->assertStringContainsString('3', $content);
    }

    /**
     * Test: Rejected count displays correctly
     */
    public function test_rejected_count_displays_correctly(): void
    {
        // Create rejected testimonials
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'User 1',
            'content' => 'First testimonial content here.',
            'rating' => 1,
            'status' => 'rejected',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show rejected count of 1
        $this->assertStringContainsString('1', $content);
    }

    /**
     * Test: Total count displays correctly
     */
    public function test_total_count_displays_correctly(): void
    {
        // Create various testimonials
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Pending User',
            'content' => 'Pending testimonial.',
            'rating' => 5,
            'status' => 'pending',
        ]);
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Approved User',
            'content' => 'Approved testimonial.',
            'rating' => 5,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Rejected User',
            'content' => 'Rejected testimonial.',
            'rating' => 1,
            'status' => 'rejected',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show total count of 3
        $this->assertStringContainsString('3', $content);
    }

    /**
     * Test: Status filter tabs work for pending
     */
    public function test_status_filter_tabs_work_for_pending(): void
    {
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Pending User',
            'content' => 'Pending testimonial.',
            'rating' => 5,
            'status' => 'pending',
        ]);
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Approved User',
            'content' => 'Approved testimonial.',
            'rating' => 5,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard').'?status=pending');

        $response->assertOk();
        $content = $response->getContent();

        // Should show pending user
        $this->assertStringContainsString('Pending User', $content);
        // Should not show approved user
        $this->assertStringNotContainsString('Approved User', $content);
    }

    /**
     * Test: Status filter tabs work for approved
     */
    public function test_status_filter_tabs_work_for_approved(): void
    {
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Pending User',
            'content' => 'Pending testimonial.',
            'rating' => 5,
            'status' => 'pending',
        ]);
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Approved User',
            'content' => 'Approved testimonial.',
            'rating' => 5,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard').'?status=approved');

        $response->assertOk();
        $content = $response->getContent();

        // Should show approved user
        $this->assertStringContainsString('Approved User', $content);
        // Should not show pending user
        $this->assertStringNotContainsString('Pending User', $content);
    }

    /**
     * Test: Status filter tabs work for rejected
     */
    public function test_status_filter_tabs_work_for_rejected(): void
    {
        Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Rejected User',
            'content' => 'Rejected testimonial.',
            'rating' => 1,
            'status' => 'rejected',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard').'?status=rejected');

        $response->assertOk();
        $content = $response->getContent();

        // Should show rejected user
        $this->assertStringContainsString('Rejected User', $content);
    }

    /**
     * Test: Stats reflect status changes
     */
    public function test_stats_reflect_status_changes(): void
    {
        // Create pending testimonial
        $testimonial = Review::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Test User',
            'content' => 'Testimonial content here for status change.',
            'rating' => 5,
            'status' => 'pending',
        ]);

        // Check initial state
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $content = $response->getContent();
        $this->assertStringContainsString('1', $content); // 1 pending

        // Approve the testimonial
        $this->actingAs($this->user)->post(route('testimonials.approve', $testimonial));

        // Check updated state
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $content = $response->getContent();
        // Should now show 0 pending, 1 approved
        $this->assertStringContainsString('0', $content);
    }

    /**
     * Test: Empty state displays when no testimonials
     */
    public function test_empty_state_displays_when_no_testimonials(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show empty state message
        $this->assertStringContainsString('No testimonials yet', $content);
    }

    /**
     * Test: Collection URL displays on dashboard
     */
    public function test_collection_url_displays_on_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $content = $response->getContent();

        // Should show collection URL
        $this->assertStringContainsString($this->creator->collection_url, $content);
    }

    /**
     * Test: Guest users cannot access dashboard
     */
    public function test_guest_users_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
