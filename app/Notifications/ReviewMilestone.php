<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewMilestone extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public int $reviewCount,
        public int $limit
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $remaining = $this->limit - $this->reviewCount;
        $isApproachingLimit = $this->reviewCount >= 7;

        $message = (new MailMessage)
            ->subject("You've collected {$this->reviewCount} reviews!")
            ->greeting('Great progress!')
            ->line("You've collected {$this->reviewCount} reviews on your Starter plan. That's fantastic!");

        if ($isApproachingLimit) {
            $message->line("**Heads up:** You have {$remaining} review(s) remaining on your Starter plan.")
                ->line('Once you reach 10 reviews, you\'ll need to upgrade to Pro to continue collecting reviews.')
                ->line('**With Pro, you get:**')
                ->line('✓ Unlimited reviews')
                ->line('✓ Custom branding')
                ->line('✓ Remove ReviewBridge badge')
                ->line('✓ Multi-platform routing')
                ->action('Upgrade to Pro - 14 Day Trial', route('pricing'));
        } else {
            $message->line('Keep up the great work collecting feedback from your customers!')
                ->action('View Your Reviews', route('dashboard'));
        }

        return $message->salutation('The ReviewBridge Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'review_count' => $this->reviewCount,
            'limit' => $this->limit,
        ];
    }
}
