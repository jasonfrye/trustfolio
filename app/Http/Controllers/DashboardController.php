<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        
        // Check if user has a creator profile
        $creator = Creator::where('user_id', $user->id)->first();
        
        if (!$creator) {
            // Creator profile doesn't exist, redirect to create one
            return redirect()->route('creator.setup');
        }

        $stats = [
            'total' => $creator->testimonials()->count(),
            'pending' => $creator->testimonials()->pending()->count(),
            'approved' => $creator->testimonials()->approved()->count(),
            'rejected' => $creator->testimonials()->rejected()->count(),
        ];

        // Get testimonials with optional status filter
        $testimonialsQuery = $creator->testimonials();
        if (request('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $testimonialsQuery->where('status', request('status'));
        }
        $testimonials = $testimonialsQuery->latest()->get();

        return view('dashboard', compact('creator', 'stats', 'testimonials'));
    }
}
