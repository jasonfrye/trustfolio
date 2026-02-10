<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\TestimonialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    public function __invoke(Request $request, string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)
            ->with('user')
            ->firstOrFail();

        $testimonialRequest = null;
        if ($request->has('token')) {
            $testimonialRequest = TestimonialRequest::where('token', $request->token)
                ->where('creator_id', $creator->id)
                ->whereNull('responded_at')
                ->first();
        }

        return view('collection.show', [
            'creator' => $creator,
            'testimonialRequest' => $testimonialRequest,
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
            'token' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $review = $creator->reviews()->create([
            'author_name' => $request->name,
            'author_email' => $request->email,
            'author_title' => $request->title,
            'content' => $request->content,
            'rating' => (int) $request->rating,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'is_private_feedback' => false,
        ]);

        // Mark testimonial request as responded if token provided
        if ($request->has('token')) {
            $testimonialRequest = TestimonialRequest::where('token', $request->token)
                ->where('creator_id', $creator->id)
                ->whereNull('responded_at')
                ->first();

            if ($testimonialRequest) {
                $testimonialRequest->markAsResponded($review);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return back()->with('success', 'Thank you for your testimonial! It will be reviewed shortly.');
    }
}
