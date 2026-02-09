<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use App\Models\WidgetSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WidgetSortFilterTest extends TestCase
{
    use RefreshDatabase;

    private Creator $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->creator = Creator::factory()->create(['user_id' => $user->id]);
    }

    public function test_widget_filters_by_minimum_rating(): void
    {
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
            'minimum_rating' => 4,
            'sort_order' => 'recent',
            'show_branding' => true,
        ]);

        Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'rating' => 5,
            'author_name' => 'High Rater',
        ]);
        Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'rating' => 2,
            'author_name' => 'Low Rater',
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertStatus(200);
        $response->assertSee('High Rater');
        $response->assertDontSee('Low Rater');
    }

    public function test_widget_sorts_by_highest_rated(): void
    {
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
            'sort_order' => 'highest_rated',
            'show_branding' => true,
        ]);

        Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'rating' => 3,
            'author_name' => 'Three Star',
        ]);
        Review::factory()->approved()->create([
            'creator_id' => $this->creator->id,
            'rating' => 5,
            'author_name' => 'Five Star',
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertStatus(200);
        // Five Star should appear before Three Star in the response
        $content = $response->getContent();
        $this->assertLessThan(
            strpos($content, 'Three Star'),
            strpos($content, 'Five Star')
        );
    }

    public function test_widget_respects_limit_setting(): void
    {
        WidgetSetting::create([
            'creator_id' => $this->creator->id,
            'theme' => 'light',
            'primary_color' => '#4f46e5',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'border_radius' => '8',
            'layout' => 'cards',
            'limit' => 2,
            'show_ratings' => true,
            'show_avatars' => true,
            'show_dates' => true,
            'minimum_rating' => 1,
            'sort_order' => 'recent',
            'show_branding' => true,
        ]);

        Review::factory()->approved()->count(5)->create([
            'creator_id' => $this->creator->id,
        ]);

        $response = $this->get(route('widget.script', $this->creator->collection_url));

        $response->assertStatus(200);
        // The JSON should only contain 2 testimonials
        $content = $response->getContent();
        preg_match('/renderWidget\(container, (\[.*?\]), \{/', $content, $matches);
        if (! empty($matches[1])) {
            $testimonials = json_decode($matches[1], true);
            $this->assertCount(2, $testimonials);
        }
    }
}
