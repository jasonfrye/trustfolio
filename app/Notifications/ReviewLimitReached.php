<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewLimitReached extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct() {}

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
            ->subject('🎯 You\'ve reached your review limit!')
            ->greeting('Congratulations!')
            ->line('You\'ve reached 10 reviews on your Starter plan! This shows that ReviewBridge is working for your business.')
            ->line('**Ready to keep growing?** Upgrade to Pro and unlock unlimited reviews plus powerful features:')
            ->line('✓ **Unlimited reviews** - Never hit a limit again')
            ->line('✓ **Custom branding** - Make the widget match your brand')
            ->line('✓ **Remove ReviewBridge badge** - Professional white-label experience')
            ->line('✓ **Multi-platform routing** - Send great reviews to Google, Yelp, and more')
            ->line('**Special Offer:** Start your 14-day free trial today. No credit card required.')
            ->action('Upgrade to Pro Now', route('pricing'))
            ->line('Questions? Just reply to this email and we\'ll help you out.')
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
            'limit_reached' => true,
        ];
    }
}
