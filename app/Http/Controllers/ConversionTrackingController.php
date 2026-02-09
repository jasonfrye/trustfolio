<?php

namespace App\Http\Controllers;

use App\Models\ConversionEvent;
use App\Models\Creator;
use Illuminate\Http\Request;

class ConversionTrackingController extends Controller
{
    /**
     * Track upgrade click event.
     */
    public function trackUpgradeClick(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        $user = $request->user();
        $creator = Creator::where('user_id', $user->id)->first();

        if (! $creator) {
            return response()->json(['success' => false], 404);
        }

        ConversionEvent::track(
            $creator->id,
            ConversionEvent::EVENT_UPGRADE_CLICK,
            $validated['source'],
            $validated['metadata'] ?? null
        );

        return response()->json(['success' => true]);
    }
}
