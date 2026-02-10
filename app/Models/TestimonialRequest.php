<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TestimonialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'recipient_email',
        'recipient_name',
        'token',
        'sent_at',
        'responded_at',
        'review_id',
        'notes',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->token)) {
                $request->token = Str::random(64);
            }
        });
    }

    /**
     * Get the creator that owns the testimonial request.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * Get the review that was submitted from this request.
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at');
    }

    /**
     * Scope a query to only include sent requests.
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at')
            ->whereNull('responded_at');
    }

    /**
     * Scope a query to only include responded requests.
     */
    public function scopeResponded($query)
    {
        return $query->whereNotNull('responded_at');
    }

    /**
     * Mark this request as sent.
     */
    public function markAsSent(): void
    {
        $this->update(['sent_at' => now()]);
    }

    /**
     * Mark this request as responded with the given review.
     */
    public function markAsResponded(Review $review): void
    {
        $this->update([
            'responded_at' => now(),
            'review_id' => $review->id,
        ]);
    }
}
