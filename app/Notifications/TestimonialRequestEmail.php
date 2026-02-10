<?php

namespace App\Notifications;

use App\Models\TestimonialRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestimonialRequestEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TestimonialRequest $request
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
        $creator = $this->request->creator;
        $url = route('collection.show', [
            'collectionUrl' => $creator->collection_url,
        ]).'?token='.$this->request->token;

        $greeting = $this->request->recipient_name
            ? "Hi {$this->request->recipient_name},"
            : 'Hi there,';

        return (new MailMessage)
            ->subject("{$creator->display_name} would love your feedback")
            ->greeting($greeting)
            ->line("We're always looking to improve and would love to hear about your experience with {$creator->display_name}.")
            ->line('Your feedback helps us serve you better and helps others make informed decisions.')
            ->action('Share Your Feedback', $url)
            ->line('It only takes a minute, and your honest opinion means a lot to us.')
            ->line('Thank you for your time!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'creator_id' => $this->request->creator_id,
            'recipient_email' => $this->request->recipient_email,
        ];
    }
}
