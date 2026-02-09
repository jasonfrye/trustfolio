<?php

namespace App\Observers;

use App\Models\ConversionEvent;
use App\Models\Review;
use App\Notifications\FirstReviewReceived;
use App\Notifications\ReviewLimitReached;
use App\Notifications\ReviewMilestone;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $creator = $review->creator;

        // Only send lifecycle emails to free plan users
        if (! $creator || $creator->plan !== 'free') {
            return;
        }

        $reviewCount = $creator->reviews()->count();
        $limit = $creator->max_reviews;

        // First review - celebration email
        if ($reviewCount === 1) {
            $creator->user->notify(new FirstReviewReceived($review));

            return;
        }

        // Milestone emails at 5, 7, and 9 reviews
        if (in_array($reviewCount, [5, 7, 9])) {
            $creator->user->notify(new ReviewMilestone($reviewCount, $limit));

            // Track limit warning events (at 7 and 9 reviews)
            if (in_array($reviewCount, [7, 9])) {
                ConversionEvent::track(
                    $creator->id,
                    ConversionEvent::EVENT_LIMIT_WARNING,
                    'review_milestone',
                    ['review_count' => $reviewCount, 'limit' => $limit]
                );
            }

            return;
        }

        // Limit reached at 10 reviews
        if ($reviewCount === $limit) {
            $creator->user->notify(new ReviewLimitReached);

            // Track limit reached event
            ConversionEvent::track(
                $creator->id,
                ConversionEvent::EVENT_LIMIT_REACHED,
                'review_limit',
                ['review_count' => $reviewCount]
            );
        }
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        //
    }
}
