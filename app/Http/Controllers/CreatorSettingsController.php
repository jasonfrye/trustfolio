<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        ]);

        // Check if display_name changed - if so, regenerate collection URL
        if ($validated['display_name'] !== $creator->display_name) {
            $newCollectionUrl = Creator::generateCollectionUrlFromName($validated['display_name']);
            $creator->collection_url = $newCollectionUrl;
        }

        $creator->display_name = $validated['display_name'];
        $creator->website = $validated['website'] ?? null;
        $creator->save();

        return Redirect::route('creator.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Generate embed code for the creator's widget.
     */
    private function generateEmbedCode(Creator $creator): string
    {
        $fullUrl = url('/embed/' . $creator->collection_url);
        return <<<HTML
<!-- TrustFolio Widget -->
<div id="trustfolio-widget" data-collection="{$creator->collection_url}"></div>
<script src="{$fullUrl}" defer></script>
HTML;
    }
}
