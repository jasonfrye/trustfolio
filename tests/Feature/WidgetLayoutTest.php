<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WidgetLayoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Creator $creator;

    private WidgetSetting $widgetSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->creator = Creator::factory()->create([
            'user_id' => $this->user->id,
            'plan' => 'pro',
            'subscription_status' => 'active',
        ]);

        $this->widgetSettings = WidgetSetting::create([
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
    }

    public function test_pro_users_can_use_carousel_layout(): void
    {
        $this->actingAs($this->user);

        $response = $this->put(route('widget.settings.update'), [
            'theme' => 'light',
            'layout' => 'carousel',
            'limit' => 10,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
        ]);

        $response->assertRedirect(route('widget.settings'));
        $this->assertDatabaseHas('widget_settings', [
            'creator_id' => $this->creator->id,
            'layout' => 'carousel',
        ]);
    }

    public function test_free_users_cannot_use_advanced_layouts(): void
    {
        $this->creator->plan = 'free';
        $this->creator->subscription_status = 'inactive';
        $this->creator->save();

        $this->actingAs($this->user);

        $response = $this->put(route('widget.settings.update'), [
            'theme' => 'light',
            'layout' => 'carousel',
            'limit' => 10,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
        ]);

        $response->assertSessionHasErrors('layout');
    }

    public function test_carousel_layout_renders_with_navigation(): void
    {
        $this->widgetSettings->update(['layout' => 'carousel']);

        Review::factory()->approved()->count(3)->create([
            'creator_id' => $this->creator->id,
            'is_private_feedback' => false,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('trustfolio-carousel', $content);
        $this->assertStringContainsString('trustfolio-carousel-track', $content);
        $this->assertStringContainsString('trustfolio-carousel-nav', $content);
        $this->assertStringContainsString('trustfolio-carousel-dot', $content);
    }

    public function test_masonry_layout_renders_correctly(): void
    {
        $this->widgetSettings->update(['layout' => 'masonry']);

        Review::factory()->approved()->count(3)->create([
            'creator_id' => $this->creator->id,
            'is_private_feedback' => false,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('trustfolio-masonry', $content);
        $this->assertStringContainsString('grid-template-columns', $content);
    }

    public function test_wall_layout_renders_correctly(): void
    {
        $this->widgetSettings->update(['layout' => 'wall']);

        Review::factory()->approved()->count(3)->create([
            'creator_id' => $this->creator->id,
            'is_private_feedback' => false,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('trustfolio-wall', $content);
    }

    public function test_single_rotating_layout_renders_correctly(): void
    {
        $this->widgetSettings->update(['layout' => 'single']);

        Review::factory()->approved()->count(3)->create([
            'creator_id' => $this->creator->id,
            'is_private_feedback' => false,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('trustfolio-single', $content);
        $this->assertStringContainsString('active', $content);
    }

    public function test_all_advanced_layouts_require_pro_plan(): void
    {
        $advancedLayouts = ['carousel', 'masonry', 'wall', 'single'];

        $this->creator->plan = 'free';
        $this->creator->subscription_status = 'inactive';
        $this->creator->save();

        $this->actingAs($this->user);

        foreach ($advancedLayouts as $layout) {
            $response = $this->put(route('widget.settings.update'), [
                'theme' => 'light',
                'layout' => $layout,
                'limit' => 10,
                'minimum_rating' => 1,
                'sort_order' => 'recent',
                'show_ratings' => true,
                'show_avatars' => true,
                'show_dates' => true,
            ]);

            $response->assertSessionHasErrors('layout', "Layout {$layout} should require Pro plan");
        }
    }

    public function test_basic_layouts_available_to_all_users(): void
    {
        $basicLayouts = ['cards', 'list', 'grid'];

        $this->creator->plan = 'free';
        $this->creator->subscription_status = 'inactive';
        $this->creator->save();

        $this->actingAs($this->user);

        foreach ($basicLayouts as $layout) {
            $response = $this->put(route('widget.settings.update'), [
                'theme' => 'light',
                'layout' => $layout,
                'limit' => 10,
                'minimum_rating' => 1,
                'sort_order' => 'recent',
                'show_ratings' => true,
                'show_avatars' => true,
                'show_dates' => true,
            ]);

            $response->assertRedirect(route('widget.settings'));
            $this->assertDatabaseHas('widget_settings', [
                'creator_id' => $this->creator->id,
                'layout' => $layout,
            ]);
        }
    }
}
