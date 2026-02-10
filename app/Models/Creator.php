<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Creator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collection_url',
        'display_name',
        'website',
        'avatar_url',
        'widget_theme',
        'primary_color',
        'font_family',
        'show_branding',
        'review_threshold',
        'google_review_url',
        'redirect_platforms',
        'prefill_enabled',
        'review_prompt_text',
        'private_feedback_text',
    ];

    protected $guarded = [
        'stripe_customer_id',
        'stripe_subscription_id',
        'plan',
        'subscription_status',
        'subscription_ends_at',
        'trial_ends_at',
    ];

    protected $casts = [
        'show_branding' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'review_threshold' => 'integer',
        'redirect_platforms' => 'array',
        'prefill_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the creator profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all reviews for this creator.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews only.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('status', 'approved')
            ->where('is_private_feedback', false)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get widget settings for this creator.
     */
    public function widgetSettings(): HasMany
    {
        return $this->hasMany(WidgetSetting::class);
    }

    /**
     * Get testimonial requests for this creator.
     */
    public function testimonialRequests(): HasMany
    {
        return $this->hasMany(TestimonialRequest::class);
    }

    /**
     * Generate a unique collection URL if not set.
     */
    public function generateCollectionUrl(): string
    {
        $base = $this->display_name ?? $this->user->name ?? 'creator';

        return static::generateCollectionUrlFromName($base);
    }

    /**
     * Static method to generate a unique collection URL from a name.
     */
    public static function generateCollectionUrlFromName(string $name): string
    {
        $slug = str($name)->slug();
        $url = $slug;
        $counter = 1;

        while (static::where('collection_url', $url)->exists()) {
            $url = $slug.'-'.$counter++;
        }

        return $url;
    }

    /**
     * Check if creator has an active premium subscription (pro or business)
     */
    public function hasPremiumSubscription(): bool
    {
        return in_array($this->plan, ['pro', 'business'])
            && $this->subscription_status === 'active';
    }

    /**
     * Check if creator can use a feature based on their plan
     */
    public function canUseFeature(string $feature): bool
    {
        $freeFeatures = [
            'create_reviews',
            'approve_reviews',
            'basic_widget',
        ];

        $proFeatures = [
            'unlimited_reviews',
            'custom_branding',
            'remove_watermark',
            'email_requests',
        ];

        $businessFeatures = [
            'advanced_widgets',
            'api_access',
            'priority_support',
        ];

        if (in_array($feature, $freeFeatures)) {
            return true;
        }

        if (in_array($feature, $proFeatures) && $this->hasPremiumSubscription()) {
            return true;
        }

        if (in_array($feature, $businessFeatures) && $this->plan === 'business' && $this->subscription_status === 'active') {
            return true;
        }

        return false;
    }

    /**
     * Check if creator can remove ReviewBridge branding from widget
     */
    public function canRemoveBranding(): bool
    {
        return $this->hasPremiumSubscription();
    }

    /**
     * Check if creator has unlimited reviews
     */
    public function hasUnlimitedReviews(): bool
    {
        return $this->hasPremiumSubscription();
    }

    /**
     * Get the number of reviews allowed based on plan
     */
    public function getMaxReviewsAttribute(): int
    {
        return $this->hasUnlimitedReviews() ? PHP_INT_MAX : 10;
    }
}
