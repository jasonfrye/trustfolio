<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversionEvent extends Model
{
    protected $fillable = [
        'creator_id',
        'event_type',
        'source',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Event type constants
     */
    public const EVENT_LIMIT_WARNING = 'limit_warning';

    public const EVENT_LIMIT_REACHED = 'limit_reached';

    public const EVENT_UPGRADE_CLICK = 'upgrade_click';

    public const EVENT_CONVERSION = 'conversion';

    /**
     * Get the creator for this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * Track a conversion event.
     */
    public static function track(int $creatorId, string $eventType, ?string $source = null, ?array $metadata = null): self
    {
        return static::create([
            'creator_id' => $creatorId,
            'event_type' => $eventType,
            'source' => $source,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get conversion funnel metrics for analytics.
     */
    public static function getConversionMetrics(?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        $query = static::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $events = $query->get()->groupBy('event_type');

        return [
            'limit_warnings' => $events->get(self::EVENT_LIMIT_WARNING, collect())->count(),
            'limit_reached' => $events->get(self::EVENT_LIMIT_REACHED, collect())->count(),
            'upgrade_clicks' => $events->get(self::EVENT_UPGRADE_CLICK, collect())->count(),
            'conversions' => $events->get(self::EVENT_CONVERSION, collect())->count(),
        ];
    }
}
