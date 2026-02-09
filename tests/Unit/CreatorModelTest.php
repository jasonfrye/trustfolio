<?php

namespace Tests\Unit;

use App\Models\Creator;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $creator = Creator::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $creator->user);
        $this->assertEquals($user->id, $creator->user->id);
    }

    public function test_creator_has_many_testimonials(): void
    {
        $creator = Creator::factory()->create();
        Review::factory()->count(3)->create(['creator_id' => $creator->id]);

        $this->assertCount(3, $creator->reviews);
    }

    public function test_approved_testimonials_filters_correctly(): void
    {
        $creator = Creator::factory()->create();
        Review::factory()->approved()->count(2)->create(['creator_id' => $creator->id]);
        Review::factory()->create(['creator_id' => $creator->id]); // pending
        Review::factory()->rejected()->create(['creator_id' => $creator->id]);

        $this->assertCount(2, $creator->approvedReviews);
    }

    public function test_generate_collection_url_from_name(): void
    {
        $url = Creator::generateCollectionUrlFromName('John Doe');

        $this->assertEquals('john-doe', $url);
    }

    public function test_generate_collection_url_handles_duplicates(): void
    {
        Creator::factory()->create(['collection_url' => 'john-doe']);

        $url = Creator::generateCollectionUrlFromName('John Doe');

        $this->assertEquals('john-doe-1', $url);
    }

    public function test_has_premium_subscription_for_pro(): void
    {
        $creator = Creator::factory()->create();
        $creator->forceFill(['plan' => 'pro', 'subscription_status' => 'active'])->save();

        $this->assertTrue($creator->hasPremiumSubscription());
    }

    public function test_has_premium_subscription_for_free(): void
    {
        $creator = Creator::factory()->create();
        $creator->forceFill(['plan' => 'free', 'subscription_status' => 'inactive'])->save();

        $this->assertFalse($creator->hasPremiumSubscription());
    }

    public function test_can_use_feature_free_features(): void
    {
        $creator = Creator::factory()->create();

        $this->assertTrue($creator->canUseFeature('create_reviews'));
        $this->assertTrue($creator->canUseFeature('approve_reviews'));
        $this->assertTrue($creator->canUseFeature('basic_widget'));
    }

    public function test_cannot_use_pro_features_on_free_plan(): void
    {
        $creator = Creator::factory()->create();

        $this->assertFalse($creator->canUseFeature('unlimited_reviews'));
        $this->assertFalse($creator->canUseFeature('custom_branding'));
        $this->assertFalse($creator->canUseFeature('remove_watermark'));
    }

    public function test_can_use_pro_features_on_pro_plan(): void
    {
        $creator = Creator::factory()->create();
        $creator->forceFill(['plan' => 'pro', 'subscription_status' => 'active'])->save();

        $this->assertTrue($creator->canUseFeature('unlimited_reviews'));
        $this->assertTrue($creator->canUseFeature('custom_branding'));
    }

    public function test_max_reviews_attribute_free(): void
    {
        $creator = Creator::factory()->create();

        $this->assertEquals(10, $creator->max_reviews);
    }

    public function test_max_reviews_attribute_pro(): void
    {
        $creator = Creator::factory()->create();
        $creator->forceFill(['plan' => 'pro', 'subscription_status' => 'active'])->save();

        $this->assertEquals(PHP_INT_MAX, $creator->max_reviews);
    }

    public function test_can_remove_branding_pro(): void
    {
        $creator = Creator::factory()->create();
        $creator->forceFill(['plan' => 'pro', 'subscription_status' => 'active'])->save();

        $this->assertTrue($creator->canRemoveBranding());
    }

    public function test_cannot_remove_branding_free(): void
    {
        $creator = Creator::factory()->create();

        $this->assertFalse($creator->canRemoveBranding());
    }

    public function test_stripe_fields_are_guarded(): void
    {
        $creator = Creator::factory()->create();

        $creator->fill(['stripe_customer_id' => 'cus_123']);

        $this->assertNull($creator->stripe_customer_id);
    }
}
