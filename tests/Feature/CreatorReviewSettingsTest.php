<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorReviewSettingsTest extends TestCase
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

    public function test_save_review_threshold(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'review_threshold' => 3,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('creators', [
            'id' => $this->creator->id,
            'review_threshold' => 3,
        ]);
    }

    public function test_save_google_review_url(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'google_review_url' => 'https://g.page/my-business/review',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('creators', [
            'id' => $this->creator->id,
            'google_review_url' => 'https://g.page/my-business/review',
        ]);
    }

    public function test_save_redirect_platforms(): void
    {
        $platforms = [
            ['name' => 'Google', 'url' => 'https://g.page/test/review'],
            ['name' => 'Yelp', 'url' => 'https://yelp.com/biz/test'],
        ];

        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'redirect_platforms' => $platforms,
        ]);

        $response->assertSessionHasNoErrors();

        $this->creator->refresh();
        $this->assertEquals($platforms, $this->creator->redirect_platforms);
    }

    public function test_validate_platform_url_format(): void
    {
        $platforms = [
            ['name' => 'Google', 'url' => 'not-a-valid-url'],
        ];

        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'redirect_platforms' => $platforms,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_toggle_prefill_enabled(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'prefill_enabled' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('creators', [
            'id' => $this->creator->id,
            'prefill_enabled' => true,
        ]);
    }

    public function test_save_review_prompt_text(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'review_prompt_text' => 'We appreciate your feedback!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('creators', [
            'id' => $this->creator->id,
            'review_prompt_text' => 'We appreciate your feedback!',
        ]);
    }

    public function test_save_private_feedback_text(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'private_feedback_text' => 'Sorry to hear that. Please tell us more.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('creators', [
            'id' => $this->creator->id,
            'private_feedback_text' => 'Sorry to hear that. Please tell us more.',
        ]);
    }
}
