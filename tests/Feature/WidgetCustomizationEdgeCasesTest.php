<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use App\Models\WidgetSetting;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WidgetCustomizationEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Creator $creator;
    protected WidgetSetting $widgetSettings;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->creator = Creator::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->widgetSettings = WidgetSetting::factory()->create([
            'creator_id' => $this->creator->id,
        ]);
    }

    protected function validData(array $overrides = []): array
    {
        return array_merge([
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => 8,
            'layout' => 'cards',
            'limit' => 10,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => true,
        ], $overrides);
    }

    #[Test]
    public function border_radius_negative_value_is_clamped_to_zero()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => -5,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(0, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function border_radius_extreme_large_value_is_clamped_to_max()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 999,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(16, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function border_radius_decimal_value_is_rounded()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 7.5,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(8, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function border_radius_zero_is_valid()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 0,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(0, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function short_hex_color_is_expanded_to_full_six_digits()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => '#fff',
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals('#ffffff', $this->widgetSettings->primary_color);
    }

    #[Test]
    public function three_digit_hex_color_is_expanded_correctly()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => '#abc',
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals('#aabbcc', $this->widgetSettings->primary_color);
    }

    #[Test]
    public function invalid_hex_color_with_invalid_characters_is_rejected()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => '#gggggg',
        ]));
        
        $response->assertSessionHasErrors('primary_color');
    }

    #[Test]
    public function malformed_hex_color_without_hash_is_rejected()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => 'ffffff',
        ]));
        
        $response->assertSessionHasErrors('primary_color');
    }

    #[Test]
    public function empty_color_string_is_valid_for_optional_field()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => '',
        ]));
        
        $response->assertSessionDoesntHaveErrors('primary_color');
    }

    #[Test]
    public function null_color_is_handled_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'primary_color' => null,
        ]));
        
        $response->assertSessionDoesntHaveErrors('primary_color');
    }

    #[Test]
    public function missing_theme_selection_defaults_to_light()
    {
        $this->assertEquals('light', $this->widgetSettings->theme);
    }

    #[Test]
    public function invalid_theme_value_is_rejected()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'theme' => 'invalid_theme',
        ]));
        
        $response->assertSessionHasErrors('theme');
    }

    #[Test]
    public function valid_theme_values_are_accepted()
    {
        $this->actingAs($this->user);
        
        foreach (['light', 'dark', 'custom'] as $theme) {
            $response = $this->put('/widget/settings', $this->validData([
                'theme' => $theme,
            ]));
            $response->assertSessionDoesntHaveErrors('theme');
        }
    }

    #[Test]
    public function very_small_border_radius_is_clamped_to_zero()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 0.1,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(0, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function border_radius_max_value_is_valid()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 16,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertEquals(16, (int)$this->widgetSettings->border_radius);
    }

    #[Test]
    public function border_radius_just_over_max_is_clamped()
    {
        $this->actingAs($this->user);
        
        $response = $this->put('/widget/settings', $this->validData([
            'border_radius' => 17,
        ]));
        
        $this->widgetSettings->refresh();
        $this->assertLessThanOrEqual(16, (int)$this->widgetSettings->border_radius);
    }
}
