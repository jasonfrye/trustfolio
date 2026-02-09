<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorSettingsTest extends TestCase
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
    }

    /**
     * Test: Settings page loads for authenticated creator
     */
    public function test_settings_page_loads_for_authenticated_creator(): void
    {
        $response = $this->actingAs($this->user)->get(route('creator.settings'));

        $response->assertOk();
        $response->assertViewIs('creator-settings');
        $response->assertViewHas('creator');
    }

    /**
     * Test: Guest users are redirected to login
     */
    public function test_guest_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('creator.settings'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Display name update persists
     */
    public function test_display_name_update_persists(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Updated Creator Name',
            'website' => 'https://example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('creator.settings'));

        $this->creator->refresh();
        $this->assertSame('Updated Creator Name', $this->creator->display_name);
    }

    /**
     * Test: Website URL update persists
     */
    public function test_website_url_update_persists(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Test Creator',
            'website' => 'https://newwebsite.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('creator.settings'));

        $this->creator->refresh();
        $this->assertSame('https://newwebsite.com', $this->creator->website);
    }

    /**
     * Test: Display name change regenerates collection URL
     */
    public function test_display_name_change_regenerates_collection_url(): void
    {
        $oldCollectionUrl = $this->creator->collection_url;

        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Jane Smith',
            'website' => null,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('creator.settings'));

        $this->creator->refresh();
        $this->assertSame('jane-smith', $this->creator->collection_url);
        $this->assertNotSame($oldCollectionUrl, $this->creator->collection_url);
    }

    /**
     * Test: Duplicate display name appends increment
     */
    public function test_duplicate_display_name_appends_increment(): void
    {
        // Create another creator with the same name pattern
        $user2 = User::factory()->create(['email' => 'another@example.com']);
        Creator::create([
            'user_id' => $user2->id,
            'display_name' => 'Jane Smith',
            'collection_url' => 'jane-smith',
        ]);

        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Jane Smith',
            'website' => null,
        ]);

        $response->assertSessionHasNoErrors();

        $this->creator->refresh();
        $this->assertSame('jane-smith-1', $this->creator->collection_url);
    }

    /**
     * Test: Unchanged display name preserves collection URL
     */
    public function test_unchanged_display_name_preserves_collection_url(): void
    {
        $oldCollectionUrl = $this->creator->collection_url;

        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => $this->creator->display_name,
            'website' => null,
        ]);

        $response->assertSessionHasNoErrors();

        $this->creator->refresh();
        $this->assertSame($oldCollectionUrl, $this->creator->collection_url);
    }

    /**
     * Test: Display name is required
     */
    public function test_display_name_is_required(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => '',
            'website' => null,
        ]);

        $response->assertSessionHasErrors('display_name');
    }

    /**
     * Test: Website URL must be valid
     */
    public function test_website_url_must_be_valid(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Test Creator',
            'website' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors('website');
    }

    /**
     * Test: Embed code displays correctly in settings page
     */
    public function test_embed_code_displays_correctly(): void
    {
        $response = $this->actingAs($this->user)->get(route('creator.settings'));

        $response->assertOk();
        $content = $response->getContent();

        // Should contain embed container
        $this->assertStringContainsString('trustfolio-widget', $content);
        $this->assertStringContainsString($this->creator->collection_url, $content);
    }

    /**
     * Test: Collection URL is displayed in settings page
     */
    public function test_collection_url_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get(route('creator.settings'));

        $response->assertOk();
        $content = $response->getContent();

        // Should contain the collection URL
        $this->assertStringContainsString($this->creator->collection_url, $content);
        $this->assertStringContainsString(route('collection.show', $this->creator->collection_url), $content);
    }

    /**
     * Test: Success message is shown after update
     */
    public function test_success_message_shown_after_update(): void
    {
        $response = $this->actingAs($this->user)->put(route('creator.settings.update'), [
            'display_name' => 'Updated Name',
            'website' => null,
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * Test: Unauthorized user cannot access other creator's settings
     */
    public function test_unauthorized_user_cannot_access_others_settings(): void
    {
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $response = $this->actingAs($otherUser)->get(route('creator.settings'));

        // Should not find the creator belonging to the first user
        $response->assertNotFound();
    }
}
