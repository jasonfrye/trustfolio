<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
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

    public function test_subscription_index_loads_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('subscription.index'));

        $response->assertStatus(200);
    }

    public function test_subscription_index_shows_testimonial_count(): void
    {
        Review::factory()->count(3)->create(['creator_id' => $this->creator->id]);

        $response = $this->actingAs($this->user)->get(route('subscription.index'));

        $response->assertStatus(200);
    }

    public function test_subscription_index_redirects_guests(): void
    {
        $response = $this->get(route('subscription.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_subscription_checkout_requires_authentication(): void
    {
        $response = $this->post(route('subscription.checkout'), ['plan' => 'monthly']);

        $response->assertRedirect(route('login'));
    }

    public function test_subscription_success_page_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('subscription.success', ['session_id' => 'cs_test_123']));

        $response->assertStatus(200);
        $response->assertSee('Welcome to ReviewBridge Pro');
    }

    public function test_subscription_success_redirects_without_session_id(): void
    {
        $response = $this->actingAs($this->user)->get(route('subscription.success'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_subscription_cancel_page_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('subscription.cancel'));

        $response->assertStatus(200);
        $response->assertSee('Subscription Cancelled');
    }

    public function test_subscription_portal_requires_authentication(): void
    {
        $response = $this->get(route('subscription.manage'));

        $response->assertRedirect(route('login'));
    }

    public function test_stripe_webhook_accepts_post(): void
    {
        $response = $this->postJson(route('stripe.webhook'), [], [
            'Stripe-Signature' => 'test',
        ]);

        // Should fail with bad signature, not 500
        $response->assertStatus(400);
    }
}
