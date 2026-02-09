<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Approve a testimonial.
     */
    public function approve(Request $request, Testimonial $testimonial)
    {
        $this->authorizeTestimonial($testimonial);
        
        $testimonial->approve();
        
        return back()->with('status', 'Testimonial approved successfully.');
    }

    /**
     * Reject a testimonial.
     */
    public function reject(Request $request, Testimonial $testimonial)
    {
        $this->authorizeTestimonial($testimonial);
        
        $testimonial->reject();
        
        return back()->with('status', 'Testimonial rejected.');
    }

    /**
     * Delete a testimonial.
     */
    public function destroy(Request $request, Testimonial $testimonial)
    {
        $this->authorizeTestimonial($testimonial);
        
        $testimonial->delete();
        
        return back()->with('status', 'Testimonial deleted.');
    }

    /**
     * Ensure the authenticated user owns this testimonial's creator.
     */
    private function authorizeTestimonial(Testimonial $testimonial): void
    {
        $user = Auth::user();
        $creator = $testimonial->creator;
        
        if (!$creator || $creator->user_id !== $user->id) {
            abort(403, 'You do not own this testimonial.');
        }
    }
}
