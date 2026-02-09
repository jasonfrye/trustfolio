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
    ];

    /**
     * Get the user that owns the creator profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all testimonials for this creator.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get approved testimonials only.
     */
    public function approvedTestimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)
            ->where('status', 'approved')
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
            $url = $slug . '-' . $counter++;
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
        // Free plan features
        $freeFeatures = [
            'create_testimonials',
            'approve_testimonials',
            'basic_widget',
        ];

        // Pro plan features (includes all free + these)
        $proFeatures = [
            'unlimited_testimonials',
            'custom_branding',
            'remove_watermark',
        ];

        // Business plan features (includes all pro + these)
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
     * Check if creator can remove TrustFolio branding from widget
     */
    public function canRemoveBranding(): bool
    {
        return $this->hasPremiumSubscription();
    }

    /**
     * Check if creator has unlimited testimonials
     */
    public function hasUnlimitedTestimonials(): bool
    {
        return $this->hasPremiumSubscription();
    }

    /**
     * Get the number of testimonials allowed based on plan
     */
    public function getMaxTestimonialsAttribute(): int
    {
        return $this->hasUnlimitedTestimonials() ? PHP_INT_MAX : 10;
    }
}
