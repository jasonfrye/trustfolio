<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    public function __invoke(string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)
            ->with('user')
            ->firstOrFail();

        return view('collection.show', [
            'creator' => $creator,
            'threshold' => $creator->getReviewThreshold(),
            'reviewPromptText' => $creator->getReviewPromptText(),
            'privateFeedbackText' => $creator->getPrivateFeedbackText(),
            'platforms' => $creator->getRedirectPlatforms(),
            'googleReviewUrl' => $creator->google_review_url,
            'prefillEnabled' => $creator->prefill_enabled,
        ]);
    }

    public function submit(Request $request, string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $rating = (int) $request->rating;
        $isPrivateFeedback = $rating < $creator->getReviewThreshold();

        $review = $creator->reviews()->create([
            'author_name' => $request->name,
            'author_email' => $request->email,
            'author_title' => $request->title,
            'content' => $request->content,
            'rating' => $rating,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'is_private_feedback' => $isPrivateFeedback,
        ]);

        if ($request->expectsJson()) {
            $responseData = [
                'success' => true,
                'type' => $isPrivateFeedback ? 'private_feedback' : 'review',
            ];

            if (! $isPrivateFeedback) {
                $responseData['google_review_url'] = $creator->google_review_url;
                $responseData['platforms'] = $creator->getRedirectPlatforms();
            }

            return response()->json($responseData);
        }

        return back()->with('success', 'Thank you for your review! It will be reviewed shortly.');
    }
}
