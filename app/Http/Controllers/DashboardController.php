<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $creator = Creator::where('user_id', $user->id)->first();

        if (! $creator) {
            return redirect()->route('creator.setup');
        }

        $stats = [
            'total' => $creator->reviews()->where('is_private_feedback', false)->count(),
            'pending' => $creator->reviews()->pending()->count(),
            'approved' => $creator->reviews()->approved()->count(),
            'rejected' => $creator->reviews()->rejected()->count(),
            'private_feedback' => $creator->reviews()->privateFeedback()->count(),
        ];

        $reviewsQuery = $creator->reviews();
        if (request('status') === 'private_feedback') {
            $reviewsQuery->where('is_private_feedback', true);
        } elseif (request('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $reviewsQuery->where('status', request('status'))->where('is_private_feedback', false);
        } else {
            $reviewsQuery->where('is_private_feedback', false);
        }
        $reviews = $reviewsQuery->latest()->get();

        return view('dashboard', compact('creator', 'stats', 'reviews'));
    }
}
