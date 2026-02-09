<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'author_name',
        'author_email',
        'author_title',
        'author_avatar_url',
        'content',
        'rating',
        'status',
        'source',
        'ip_address',
        'approved_at',
        'is_private_feedback',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
        'is_private_feedback' => 'boolean',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    /**
     * Get the creator that owns the review.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * Scope for approved reviews (excludes private feedback).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
            ->where('is_private_feedback', false);
    }

    /**
     * Scope for pending reviews (excludes private feedback).
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where('is_private_feedback', false);
    }

    /**
     * Scope for rejected reviews (excludes private feedback).
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED)
            ->where('is_private_feedback', false);
    }

    /**
     * Scope for private feedback only.
     */
    public function scopePrivateFeedback($query)
    {
        return $query->where('is_private_feedback', true);
    }

    /**
     * Mark as approved.
     */
    public function approve(): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark as rejected.
     */
    public function reject(): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
        ]);
    }
}
