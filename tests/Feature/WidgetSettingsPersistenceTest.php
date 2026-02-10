<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WidgetSettingsPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Creator $creator;

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

        // Create default widget settings
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
    }

    /**
     * Test: Theme selection persists
     */
    public function test_theme_selection_persists(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'dark',
            'primary_color' => '#818cf8',
            'background_color' => '#1f2937',
            'text_color' => '#f3f4f6',
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

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('widget.settings'));

        $this->assertDatabaseHas('widget_settings', [
            'creator_id' => $this->creator->id,
            'theme' => 'dark',
        ]);
    }

    /**
     * Test: Color picker values save to database
     */
    public function test_color_picker_values_save_to_database(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'custom',
            'primary_color' => '#ff6b6b',
            'background_color' => '#1a1a2e',
            'text_color' => '#eaeaea',
            'border_radius' => '16',
            'layout' => 'grid',
            'limit' => 20,
            'show_ratings' => true,
            'show_avatars' => false,
            'show_dates' => true,
            'minimum_rating' => 3,
            'sort_order' => 'highest_rated',
            'show_branding' => false,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('widget_settings', [
            'creator_id' => $this->creator->id,
            'primary_color' => '#ff6b6b',
            'background_color' => '#1a1a2e',
            'text_color' => '#eaeaea',
        ]);
    }

    /**
     * Test: Layout option persists
     */
    public function test_layout_option_persists(): void
    {
        foreach (['cards', 'list', 'grid'] as $layout) {
            $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
                'theme' => 'light',
                'primary_color' => '#4f46e5',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
                'border_radius' => '8',
                'layout' => $layout,
                'limit' => 10,
                'show_ratings' => true,
                'show_avatars' => true,
                'show_dates' => true,
                'minimum_rating' => 1,
                'sort_order' => 'recent',
                'show_branding' => true,
            ]);

            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('widget_settings', [
                'creator_id' => $this->creator->id,
                'layout' => $layout,
            ]);
        }
    }

    /**
     * Test: Border radius setting persists
     */
    public function test_border_radius_setting_persists(): void
    {
        foreach (['0', '4', '8', '12', '16'] as $radius) {
            $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
                'theme' => 'custom',
                'primary_color' => '#4f46e5',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
                'border_radius' => $radius,
                'layout' => 'cards',
                'limit' => 10,
                'show_ratings' => true,
                'show_avatars' => true,
                'show_dates' => true,
                'minimum_rating' => 1,
                'sort_order' => 'recent',
                'show_branding' => true,
            ]);

            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('widget_settings', [
                'creator_id' => $this->creator->id,
                'border_radius' => $radius,
            ]);
        }
    }

    /**
     * Test: Limit setting persists
     */
    public function test_limit_setting_persists(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'cards',
            'limit' => 25,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => true,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('widget_settings', [
            'creator_id' => $this->creator->id,
            'limit' => 25,
        ]);
    }

    /**
     * Test: Boolean settings persist correctly
     */
    public function test_boolean_settings_persist_correctly(): void
    {
        // Test with all booleans off
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'custom',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'cards',
            'limit' => 10,
            'show_ratings' => false,
            'show_avatars' => false,
            'show_dates' => false,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => false,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('widget_settings', [
            'creator_id' => $this->creator->id,
            'show_ratings' => false,
            'show_avatars' => false,
            'show_dates' => false,
            'show_branding' => false,
        ]);
    }

    /**
     * Test: Minimum rating persists
     */
    public function test_minimum_rating_persists(): void
    {
        foreach ([1, 2, 3, 4, 5] as $rating) {
            $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
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
                'minimum_rating' => $rating,
                'sort_order' => 'recent',
                'show_branding' => true,
            ]);

            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('widget_settings', [
                'creator_id' => $this->creator->id,
                'minimum_rating' => $rating,
            ]);
        }
    }

    /**
     * Test: Sort order persists
     */
    public function test_sort_order_persists(): void
    {
        foreach (['recent', 'random', 'highest_rated'] as $sortOrder) {
            $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
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
                'sort_order' => $sortOrder,
                'show_branding' => true,
            ]);

            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('widget_settings', [
                'creator_id' => $this->creator->id,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    /**
     * Test: Settings page loads for authenticated creator
     */
    public function test_settings_page_loads_for_authenticated_creator(): void
    {
        $response = $this->actingAs($this->user)->get(route('widget.settings'));

        $response->assertOk();
        $response->assertViewIs('widget-settings');
    }

    /**
     * Test: Settings are correctly applied to widget
     */
    public function test_settings_are_correctly_applied_to_widget(): void
    {
        // Update settings
        $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'custom',
            'primary_color' => '#ff6b6b',
            'background_color' => '#1a1a2e',
            'text_color' => '#eaeaea',
            'border_radius' => '12',
            'layout' => 'grid',
            'limit' => 15,
            'show_ratings' => false,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 2,
            'sort_order' => 'highest_rated',
            'show_branding' => true,
        ]);

        // Check widget script contains updated settings
        $response = $this->get(route('widget.script', $this->creator->collection_url));
        $content = $response->getContent();

        $this->assertStringContainsString('--tf-primary: #ff6b6b;', $content);
        $this->assertStringContainsString('--tf-bg: #1a1a2e;', $content);
        $this->assertStringContainsString('--tf-text: #eaeaea;', $content);
        $this->assertStringContainsString("borderRadius = '12px';", $content);
    }

    /**
     * Test: Guest users are redirected to login
     */
    public function test_guest_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('widget.settings'));
        $response->assertRedirect(route('login'));

        $response = $this->put(route('widget.settings.update'), []);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Theme is required
     */
    public function test_theme_is_required(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => '',
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

        $response->assertSessionHasErrors('theme');
    }

    /**
     * Test: Invalid color format is rejected
     */
    public function test_invalid_color_format_is_rejected(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'custom',
            'primary_color' => 'not-a-color',
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

        $response->assertSessionHasErrors('primary_color');
    }

    /**
     * Test: Success message shown after update
     */
    public function test_success_message_shown_after_update(): void
    {
        $response = $this->actingAs($this->user)->put(route('widget.settings.update'), [
            'theme' => 'dark',
            'primary_color' => '#818cf8',
            'background_color' => '#1f2937',
            'text_color' => '#f3f4f6',
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

        $response->assertSessionHas('success');
    }
}
