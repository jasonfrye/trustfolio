<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that registration creates both User and Creator records
     */
    public function test_registration_creates_user_and_creator_records(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jane Smith',
            'display_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));

        // User was created
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        // Creator record was also created
        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('creators', [
            'user_id' => $user->id,
            'display_name' => 'Jane Smith',
        ]);

        // Collection URL was generated
        $creator = $user->creator;
        $this->assertNotNull($creator->collection_url);
        $this->assertStringContainsString('jane-smith', $creator->collection_url);
    }

    /**
     * Test that collection URL is unique for duplicate names
     */
    public function test_collection_url_is_unique_for_duplicate_names(): void
    {
        // Create first creator directly
        $user1 = User::factory()->create([
            'email' => 'john1@example.com',
        ]);
        $creator1 = Creator::create([
            'user_id' => $user1->id,
            'display_name' => 'John Doe',
            'collection_url' => Creator::generateCollectionUrlFromName('John Doe'),
        ]);

        // Create second creator with same name
        $user2 = User::factory()->create([
            'email' => 'john2@example.com',
        ]);
        $creator2 = Creator::create([
            'user_id' => $user2->id,
            'display_name' => 'John Doe',
            'collection_url' => Creator::generateCollectionUrlFromName('John Doe'),
        ]);

        // Collection URLs should be unique
        $this->assertNotEquals($creator1->collection_url, $creator2->collection_url);
        $this->assertStringContainsString('john-doe', $creator1->collection_url);
        $this->assertStringContainsString('john-doe', $creator2->collection_url);
    }

    /**
     * Test that creator has default widget settings
     */
    public function test_creator_has_default_widget_settings(): void
    {
        $this->post('/register', [
            'name' => 'Test Creator',
            'display_name' => 'Test Creator',
            'email' => 'testcreator@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'testcreator@example.com')->first();
        $creator = $user->creator;

        $this->assertEquals('light', $creator->widget_theme);
        $this->assertTrue($creator->show_branding);
        $this->assertEquals(10, $creator->max_testimonials);
    }
}
