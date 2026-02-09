<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\WidgetSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WidgetSettingsController extends Controller
{
    /**
     * Display the widget settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $creator = Creator::where('user_id', $user->id)->firstOrFail();

        $settings = WidgetSetting::firstOrCreate(
            ['creator_id' => $creator->id],
            array_merge(
                [
                    'layout' => 'cards',
                    'limit' => 10,
                    'show_ratings' => true,
                    'show_avatars' => true,
                    'show_dates' => true,
                    'minimum_rating' => 1,
                    'sort_order' => 'recent',
                ],
                WidgetSetting::getThemeDefaults(WidgetSetting::THEME_LIGHT)
            )
        );

        $embedCode = $this->generateEmbedCode($creator);

        return view('widget-settings', compact('creator', 'settings', 'embedCode'));
    }

    /**
     * Update widget settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $creator = Creator::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:light,dark,custom'],
            'primary_color' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'background_color' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'text_color' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'border_radius' => ['nullable', 'numeric'],
            'layout' => ['required', 'string', 'in:cards,list,grid'],
            'limit' => ['required', 'integer', 'min:1', 'max:50'],
            'show_ratings' => ['nullable', 'boolean'],
            'show_avatars' => ['nullable', 'boolean'],
            'show_dates' => ['nullable', 'boolean'],
            'minimum_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['required', 'string', 'in:recent,random,highest_rated'],
            'show_branding' => ['nullable', 'boolean'],
        ]);

        // Sanitize border radius - clamp to valid range (0-16)
        $borderRadius = isset($validated['border_radius']) 
            ? max(0, min(16, (int) round((float) $validated['border_radius'])))
            : 8;

        // Sanitize colors - expand short hex to full hex
        $primaryColor = $this->expandShortHex($validated['primary_color'] ?? '#4f46e5');
        $backgroundColor = $this->expandShortHex($validated['background_color'] ?? '#ffffff');
        $textColor = $this->expandShortHex($validated['text_color'] ?? '#1f2937');

        // Sanitize theme - default to light if invalid (shouldn't happen due to validation)
        $theme = in_array($validated['theme'], ['light', 'dark', 'custom']) 
            ? $validated['theme'] 
            : 'light';

        $settings = WidgetSetting::where('creator_id', $creator->id)->firstOrFail();
        $settings->update([
            'theme' => $theme,
            'primary_color' => $primaryColor,
            'background_color' => $backgroundColor,
            'text_color' => $textColor,
            'border_radius' => (string) $borderRadius,
            'layout' => $validated['layout'],
            'limit' => $validated['limit'],
            'show_ratings' => $request->boolean('show_ratings'),
            'show_avatars' => $request->boolean('show_avatars'),
            'show_dates' => $request->boolean('show_dates'),
            'minimum_rating' => $validated['minimum_rating'],
            'sort_order' => $validated['sort_order'],
            'show_branding' => $request->boolean('show_branding'),
        ]);

        return redirect()->route('widget.settings')->with('success', 'Widget settings updated successfully!');
    }

    /**
     * Expand short hex color to full 6-digit format.
     */
    private function expandShortHex(string $color): string
    {
        // If already full hex, return as-is
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            return $color;
        }

        // Expand short hex (#fff → #ffffff)
        if (preg_match('/^#([0-9A-Fa-f]{3})$/', $color, $matches)) {
            $expanded = '';
            foreach (str_split($matches[1]) as $char) {
                $expanded .= $char . $char;
            }
            return '#' . $expanded;
        }

        // Fallback to default
        return '#4f46e5';
    }

    /**
     * Generate embed code for the creator's widget.
     */
    private function generateEmbedCode(Creator $creator): string
    {
        $scriptUrl = route('widget.script', ['collectionUrl' => $creator->collection_url]);
        $fullUrl = url('/embed/' . $creator->collection_url);
        return <<<HTML
<!-- TrustFolio Widget -->
<div id="trustfolio-widget" data-collection="{$creator->collection_url}"></div>
<script src="{$fullUrl}" defer></script>
HTML;
    }
}
