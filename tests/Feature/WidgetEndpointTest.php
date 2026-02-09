<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WidgetEndpointTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;
    private Testimonial $approvedTestimonial;
    private Testimonial $pendingTestimonial;
    private Testimonial $rejectedTestimonial;

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

        // Create widget settings
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

        // Create testimonials with different statuses
        $this->approvedTestimonial = Testimonial::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Approved User',
            'author_email' => 'approved@example.com',
            'content' => 'This testimonial is approved and should appear in widget.',
            'rating' => 5,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->pendingTestimonial = Testimonial::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Pending User',
            'author_email' => 'pending@example.com',
            'content' => 'This testimonial is pending and should NOT appear in widget.',
            'rating' => 4,
            'status' => 'pending',
        ]);

        $this->rejectedTestimonial = Testimonial::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Rejected User',
            'author_email' => 'rejected@example.com',
            'content' => 'This testimonial is rejected and should NOT appear in widget.',
            'rating' => 3,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test: Widget script contains only approved testimonials
     */
    public function test_widget_script_contains_only_approved_testimonials(): void
    {
        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/javascript');

        $content = $response->getContent();

        // Approved testimonial content should be present
        $this->assertStringContainsString(
            'This testimonial is approved and should appear in widget.',
            $content
        );

        // Pending testimonial should NOT be in widget
        $this->assertStringNotContainsString(
            'This testimonial is pending and should NOT appear in widget.',
            $content
        );

        // Rejected testimonial should NOT be in widget
        $this->assertStringNotContainsString(
            'This testimonial is rejected and should NOT appear in widget.',
            $content
        );
    }

    /**
     * Test: CSS variables are correctly embedded in widget
     */
    public function test_css_variables_are_correctly_embedded(): void
    {
        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Default light theme CSS variables
        $this->assertStringContainsString('--tf-primary: #4f46e5;', $content);
        $this->assertStringContainsString('--tf-bg: #ffffff;', $content);
        $this->assertStringContainsString('--tf-text: #1f2937;', $content);
    }

    /**
     * Test: Custom theme CSS variables are embedded
     */
    public function test_custom_theme_css_variables_are_embedded(): void
    {
        // Update widget settings to custom theme
        WidgetSetting::where('creator_id', $this->creator->id)->update([
            'theme' => 'custom',
            'primary_color' => '#ff6b6b',
            'background_color' => '#1a1a2e',
            'text_color' => '#eaeaea',
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Custom colors should be in the CSS
        $this->assertStringContainsString('--tf-primary: #ff6b6b;', $content);
        $this->assertStringContainsString('--tf-bg: #1a1a2e;', $content);
        $this->assertStringContainsString('--tf-text: #eaeaea;', $content);
    }

    /**
     * Test: Dark theme CSS variables are embedded
     */
    public function test_dark_theme_css_variables_are_embedded(): void
    {
        // Update widget settings to dark theme
        WidgetSetting::where('creator_id', $this->creator->id)->update([
            'theme' => 'dark',
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Dark theme defaults
        $this->assertStringContainsString('--tf-primary: #818cf8;', $content);
        $this->assertStringContainsString('--tf-bg: #1f2937;', $content);
        $this->assertStringContainsString('--tf-text: #f3f4f6;', $content);
    }

    /**
     * Test: Testimonials are limited by widget settings
     */
    public function test_testimonials_are_limited_by_settings(): void
    {
        // Set limit to 1
        WidgetSetting::where('creator_id', $this->creator->id)->update([
            'limit' => 1,
        ]);

        // Add another approved testimonial
        $extraTestimonial = Testimonial::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Extra Approved User',
            'author_email' => 'extra@example.com',
            'content' => 'This is an extra approved testimonial.',
            'rating' => 4,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Only one testimonial should be in the widget
        // The JSON array should have only 1 testimonial
        $this->assertStringContainsString(
            '"author_name":"Approved User"',
            $content
        );

        // Extra approved testimonial should NOT be present
        $this->assertStringNotContainsString(
            '"author_name":"Extra Approved User"',
            $content
        );
    }

    /**
     * Test: Widget script returns valid JavaScript
     */
    public function test_widget_script_returns_valid_javascript(): void
    {
        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertStatus(200);
        $content = $response->getContent();

        // Should be wrapped in IIFE
        $this->assertStringStartsWith('(function() {', $content);
        $this->assertStringEndsWith('})();', $content);

        // Should contain essential functions
        $this->assertStringContainsString('injectStyles', $content);
        $this->assertStringContainsString('renderWidget', $content);
        $this->assertStringContainsString('escapeHtml', $content);

        // Should have collection URL
        $this->assertStringContainsString($this->creator->collection_url, $content);
    }

    /**
     * Test: Widget endpoint requires valid collection URL
     */
    public function test_widget_endpoint_requires_valid_collection_url(): void
    {
        $response = $this->get(route('widget.script', 'nonexistent'));

        $response->assertStatus(404);
    }

    /**
     * Test: Widget includes creator display name in branding
     */
    public function test_widget_includes_creator_display_name(): void
    {
        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Should contain trustfolio branding
        $this->assertStringContainsString('trustfolio', strtolower($content));
    }

    /**
     * Test: Widget with no approved testimonials shows empty message
     */
    public function test_widget_with_no_approved_shows_empty_message(): void
    {
        // Delete the approved testimonial
        $this->approvedTestimonial->delete();

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Should show empty message
        $this->assertStringContainsString('No testimonials yet', $content);
    }

    /**
     * Test: Multiple approved testimonials appear in widget
     */
    public function test_multiple_approved_testimonials_appear(): void
    {
        // Add another approved testimonial
        $extraTestimonial = Testimonial::create([
            'creator_id' => $this->creator->id,
            'author_name' => 'Second Approved',
            'author_email' => 'second@example.com',
            'content' => 'Second approved testimonial content.',
            'rating' => 4,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Both approved testimonials should be present
        $this->assertStringContainsString('Approved User', $content);
        $this->assertStringContainsString('Second Approved', $content);

        // Pending and rejected should not be present
        $this->assertStringNotContainsString('Pending User', $content);
        $this->assertStringNotContainsString('Rejected User', $content);
    }

    /**
     * Test: Border radius is embedded in widget
     */
    public function test_border_radius_is_embedded(): void
    {
        WidgetSetting::where('creator_id', $this->creator->id)->update([
            'border_radius' => '16',
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $content = $response->getContent();

        // Border radius should be in the JavaScript
        $this->assertStringContainsString("borderRadius = '16px';", $content);
    }
}
