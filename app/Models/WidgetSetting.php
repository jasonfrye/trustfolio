<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WidgetSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'theme',
        'primary_color',
        'background_color',
        'text_color',
        'border_radius',
        'layout',
        'limit',
        'show_ratings',
        'show_avatars',
        'show_dates',
        'minimum_rating',
        'sort_order',
        'show_branding',
    ];

    protected $casts = [
        'show_ratings' => 'boolean',
        'show_avatars' => 'boolean',
        'show_dates' => 'boolean',
        'show_branding' => 'boolean',
        'limit' => 'integer',
        'minimum_rating' => 'integer',
    ];

    public const THEME_LIGHT = 'light';
    public const THEME_DARK = 'dark';
    public const THEME_CUSTOM = 'custom';

    public const LAYOUT_CARDS = 'cards';
    public const LAYOUT_LIST = 'list';
    public const LAYOUT_GRID = 'grid';

    public const SORT_RECENT = 'recent';
    public const SORT_RANDOM = 'random';
    public const SORT_HIGHEST_RATED = 'highest_rated';

    /**
     * Get the creator that owns these widget settings.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * Get available layout options.
     */
    public static function getLayoutOptions(): array
    {
        return [
            self::LAYOUT_CARDS => 'Cards',
            self::LAYOUT_LIST => 'List',
            self::LAYOUT_GRID => 'Grid',
        ];
    }

    /**
     * Get available theme options.
     */
    public static function getThemeOptions(): array
    {
        return [
            self::THEME_LIGHT => 'Light',
            self::THEME_DARK => 'Dark',
            self::THEME_CUSTOM => 'Custom Colors',
        ];
    }

    /**
     * Get default colors for a theme.
     */
    public static function getThemeDefaults(string $theme): array
    {
        return match ($theme) {
            self::THEME_DARK => [
                'primary_color' => '#818cf8',
                'background_color' => '#1f2937',
                'text_color' => '#f3f4f6',
            ],
            self::THEME_CUSTOM => [
                'primary_color' => '#4f46e5',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
            ],
            default => [
                'primary_color' => '#4f46e5',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
            ],
        };
    }

    /**
     * Get available sort options.
     */
    public static function getSortOptions(): array
    {
        return [
            self::SORT_RECENT => 'Most Recent',
            self::SORT_RANDOM => 'Random',
            self::SORT_HIGHEST_RATED => 'Highest Rated',
        ];
    }
}
