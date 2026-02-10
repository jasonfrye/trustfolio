<?php

namespace App\Http\Controllers;

use App\Notifications\TestimonialRequestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class TestimonialRequestController extends Controller
{
    /**
     * Display a listing of testimonial requests.
     */
    public function index(Request $request)
    {
        $creator = $request->user()->creator;

        $requests = $creator->testimonialRequests()
            ->orderBy('created_at', 'desc')
            ->with('review')
            ->paginate(20);

        return view('testimonial-requests.index', [
            'requests' => $requests,
            'creator' => $creator,
        ]);
    }

    /**
     * Show the form for creating a new testimonial request.
     */
    public function create(Request $request)
    {
        $creator = $request->user()->creator;

        return view('testimonial-requests.create', [
            'creator' => $creator,
        ]);
    }

    /**
     * Store a newly created testimonial request in storage.
     */
    public function store(Request $request)
    {
        $creator = $request->user()->creator;

        $validator = Validator::make($request->all(), [
            'recipient_email' => ['required', 'email'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check monthly limit based on plan
        $monthlyLimit = $this->getMonthlyLimit($creator);
        $requestsThisMonth = $creator->testimonialRequests()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        if ($requestsThisMonth >= $monthlyLimit) {
            return back()->withErrors([
                'limit' => "You've reached your monthly limit of {$monthlyLimit} requests. Upgrade to send more.",
            ])->withInput();
        }

        // Create the request
        $testimonialRequest = $creator->testimonialRequests()->create([
            'recipient_email' => $request->recipient_email,
            'recipient_name' => $request->recipient_name,
            'notes' => $request->notes,
        ]);

        // Send email notification
        Notification::route('mail', $request->recipient_email)
            ->notify(new TestimonialRequestEmail($testimonialRequest));

        // Mark as sent
        $testimonialRequest->markAsSent();

        return redirect()
            ->route('testimonial-requests.index')
            ->with('success', 'Testimonial request sent successfully!');
    }

    /**
     * Get monthly limit based on creator's plan.
     */
    private function getMonthlyLimit($creator): int
    {
        if ($creator->plan === 'business' && $creator->subscription_status === 'active') {
            return PHP_INT_MAX; // Unlimited
        }

        if ($creator->plan === 'pro' && $creator->subscription_status === 'active') {
            return 100;
        }

        return 10; // Free plan
    }
}
