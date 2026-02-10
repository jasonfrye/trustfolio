<?php

namespace Tests\Feature;

use App\Models\Creator;
use App\Models\TestimonialRequest;
use App\Models\User;
use App\Notifications\TestimonialRequestEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TestimonialRequestTest extends TestCase
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
            'plan' => 'pro',
            'subscription_status' => 'active',
        ]);
    }

    public function test_sending_request_creates_database_record(): void
    {
        Notification::fake();

        $this->actingAs($this->user);

        $response = $this->post(route('testimonial-requests.store'), [
            'recipient_email' => 'customer@example.com',
            'recipient_name' => 'John Doe',
            'notes' => 'Spoke with him on the phone yesterday',
        ]);

        $response->assertRedirect(route('testimonial-requests.index'));

        $this->assertDatabaseHas('testimonial_requests', [
            'creator_id' => $this->creator->id,
            'recipient_email' => 'customer@example.com',
            'recipient_name' => 'John Doe',
        ]);
    }

    public function test_email_is_queued_when_request_sent(): void
    {
        Notification::fake();

        $this->actingAs($this->user);

        $this->post(route('testimonial-requests.store'), [
            'recipient_email' => 'customer@example.com',
            'recipient_name' => 'John Doe',
        ]);

        Notification::assertSentTo(
            [Notification::route('mail', 'customer@example.com')],
            TestimonialRequestEmail::class
        );
    }

    public function test_token_prefills_form(): void
    {
        $request = TestimonialRequest::factory()->create([
            'creator_id' => $this->creator->id,
            'recipient_email' => 'customer@example.com',
            'recipient_name' => 'John Doe',
            'sent_at' => now(),
        ]);

        $response = $this->get(route('collection.show', [
            'collectionUrl' => $this->creator->collection_url,
            'token' => $request->token,
        ]));

        $response->assertOk();
        $response->assertSee('John Doe');
    }

    public function test_marking_as_responded_when_review_submitted(): void
    {
        $request = TestimonialRequest::factory()->create([
            'creator_id' => $this->creator->id,
            'recipient_email' => 'customer@example.com',
            'recipient_name' => 'John Doe',
            'sent_at' => now(),
        ]);

        $this->assertNull($request->fresh()->responded_at);

        $response = $this->postJson(route('collection.submit', $this->creator->collection_url), [
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'content' => 'Great service, highly recommend!',
            'rating' => 5,
            'token' => $request->token,
        ]);

        $response->assertOk();

        $request->refresh();
        $this->assertNotNull($request->responded_at);
        $this->assertNotNull($request->review_id);
    }

    public function test_free_plan_limited_to_10_requests_per_month(): void
    {
        Notification::fake();

        $this->creator->plan = 'free';
        $this->creator->subscription_status = 'inactive';
        $this->creator->save();

        $this->actingAs($this->user);

        // Create 10 requests this month (at the limit)
        for ($i = 0; $i < 10; $i++) {
            TestimonialRequest::create([
                'creator_id' => $this->creator->id,
                'recipient_email' => "customer{$i}@example.com",
                'recipient_name' => "Customer {$i}",
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $count = $this->creator->testimonialRequests()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $this->assertEquals(10, $count, 'Should have exactly 10 requests');

        // Attempt to create the 11th request
        $response = $this->post(route('testimonial-requests.store'), [
            'recipient_email' => 'customer11@example.com',
            'recipient_name' => 'Customer 11',
        ]);

        $response->assertSessionHasErrors('limit');
    }

    public function test_pro_plan_limited_to_100_requests_per_month(): void
    {
        Notification::fake();

        $this->actingAs($this->user);

        // Create 100 requests this month (at the limit)
        TestimonialRequest::factory()->count(100)->create([
            'creator_id' => $this->creator->id,
            'created_at' => now(),
        ]);

        // Attempt to create the 101st request
        $response = $this->post(route('testimonial-requests.store'), [
            'recipient_email' => 'customer101@example.com',
            'recipient_name' => 'Customer 101',
        ]);

        $response->assertSessionHasErrors('limit');
    }

    public function test_business_plan_has_unlimited_requests(): void
    {
        Notification::fake();

        $this->creator->plan = 'business';
        $this->creator->subscription_status = 'active';
        $this->creator->save();

        $this->actingAs($this->user);

        // Create 200 requests this month (well beyond pro limit)
        TestimonialRequest::factory()->count(200)->create([
            'creator_id' => $this->creator->id,
            'created_at' => now(),
        ]);

        // Business plan should still be able to send more
        $response = $this->post(route('testimonial-requests.store'), [
            'recipient_email' => 'customer201@example.com',
            'recipient_name' => 'Customer 201',
        ]);

        $response->assertRedirect(route('testimonial-requests.index'));
        $this->assertDatabaseHas('testimonial_requests', [
            'recipient_email' => 'customer201@example.com',
        ]);
    }

    public function test_request_generates_unique_token(): void
    {
        $request1 = TestimonialRequest::factory()->create([
            'creator_id' => $this->creator->id,
        ]);

        $request2 = TestimonialRequest::factory()->create([
            'creator_id' => $this->creator->id,
        ]);

        $this->assertNotNull($request1->token);
        $this->assertNotNull($request2->token);
        $this->assertNotEquals($request1->token, $request2->token);
    }
}
