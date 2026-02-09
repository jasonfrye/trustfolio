<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CreatorSettingsController extends Controller
{
    /**
     * Display the creator settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $creator = Creator::where('user_id', $user->id)->firstOrFail();

        $collectionUrl = route('collection.show', $creator->collection_url);
        $embedCode = $this->generateEmbedCode($creator);

        return view('creator-settings', compact('creator', 'collectionUrl', 'embedCode'));
    }

    /**
     * Update creator settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $creator = Creator::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'review_threshold' => ['nullable', 'integer', 'min:1', 'max:5'],
            'google_review_url' => ['nullable', 'url', 'max:2048'],
            'redirect_platforms' => ['nullable', 'array'],
            'redirect_platforms.*.name' => ['required_with:redirect_platforms', 'string', 'max:255'],
            'redirect_platforms.*.url' => ['required_with:redirect_platforms', 'url', 'max:2048'],
            'prefill_enabled' => ['nullable', 'boolean'],
            'review_prompt_text' => ['nullable', 'string', 'max:1000'],
            'private_feedback_text' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check if display_name changed - if so, regenerate collection URL
        if ($validated['display_name'] !== $creator->display_name) {
            $newCollectionUrl = Creator::generateCollectionUrlFromName($validated['display_name']);
            $creator->collection_url = $newCollectionUrl;
        }

        $creator->display_name = $validated['display_name'];
        $creator->website = $validated['website'] ?? null;
        $creator->review_threshold = $validated['review_threshold'] ?? 4;
        $creator->google_review_url = $validated['google_review_url'] ?? null;
        $creator->redirect_platforms = $validated['redirect_platforms'] ?? null;
        $creator->prefill_enabled = $request->boolean('prefill_enabled');
        $creator->review_prompt_text = $validated['review_prompt_text'] ?? null;
        $creator->private_feedback_text = $validated['private_feedback_text'] ?? null;
        $creator->save();

        return Redirect::route('creator.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Generate embed code for the creator's widget.
     */
    private function generateEmbedCode(Creator $creator): string
    {
        $fullUrl = url('/embed/'.$creator->collection_url);

        return <<<HTML
<!-- ReviewBridge Widget -->
<div id="reviewbridge-widget" data-collection="{$creator->collection_url}"></div>
<script src="{$fullUrl}" defer></script>
HTML;
    }
}
