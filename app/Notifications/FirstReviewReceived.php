<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FirstReviewReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Review $review
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
        return (new MailMessage)
            ->subject('🎉 You got your first review!')
            ->greeting('Congratulations!')
            ->line('You just received your first review on ReviewBridge. This is the beginning of building trust with your customers.')
            ->line("**{$this->review->reviewer_name}** left you a {$this->review->rating}-star review.")
            ->line('**Here are some quick tips to get the most out of ReviewBridge:**')
            ->line('• **Embed your widget** on your website to showcase reviews')
            ->line('• **Share your review link** with customers after service')
            ->line('• **Configure routing** to send positive reviews to Google/Yelp')
            ->action('View Your Dashboard', route('dashboard'))
            ->line('Keep up the great work! Each review you collect helps build your online reputation.')
            ->salutation('The ReviewBridge Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'rating' => $this->review->rating,
            'reviewer_name' => $this->review->reviewer_name,
        ];
    }
}
