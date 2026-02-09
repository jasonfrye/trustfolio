<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Approve a review.
     */
    public function approve(Request $request, Review $testimonial): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeReview($testimonial);

        $testimonial->approve();

        return back()->with('status', 'Review approved successfully.');
    }

    /**
     * Reject a review.
     */
    public function reject(Request $request, Review $testimonial): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeReview($testimonial);

        $testimonial->reject();

        return back()->with('status', 'Review rejected.');
    }

    /**
     * Delete a review.
     */
    public function destroy(Request $request, Review $testimonial): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeReview($testimonial);

        $testimonial->delete();

        return back()->with('status', 'Review deleted.');
    }

    /**
     * Ensure the authenticated user owns this review's creator.
     */
    private function authorizeReview(Review $review): void
    {
        $user = Auth::user();
        $creator = $review->creator;

        if (! $creator || $creator->user_id !== $user->id) {
            abort(403, 'You do not own this review.');
        }
    }
}
