<?php

namespace App\Http\Controllers;

use App\Models\ConversionEvent;
use App\Models\Creator;
use App\Models\Review;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Initiate subscription checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        $plan = $request->input('plan');
        $priceId = $plan === 'monthly'
            ? config('services.stripe.monthly_price_id')
            : config('services.stripe.annual_price_id');

        if (! $priceId) {
            return back()->with('error', 'Pricing configuration error. Please contact support.');
        }

        $user = Auth::user();
        $creator = $user->creator;

        // Create or find Stripe customer
        $customer = $this->stripeService->findOrCreateCustomer(
            $user->email,
            $user->name ?? $user->email
        );

        // Store Stripe customer ID on creator
        $creator->stripe_customer_id = $customer->id;
        $creator->save();

        // Create checkout session
        $session = $this->stripeService->createSubscriptionCheckout(
            $customer->id,
            $priceId,
            route('subscription.success').'?session_id={CHECKOUT_SESSION_ID}',
            route('subscription.cancel')
        );

        return redirect($session->url, 303);
    }

    /**
     * Subscription success page
     */
    public function success(Request $request)
    {
        $sessionId = $request->session()->get('session_id') ?? $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('dashboard')
                ->with('error', 'Invalid session.');
        }

        try {
            // Store subscription info in user record
            // In a real app, you'd verify the session with Stripe first
            return view('subscription.success');
        } catch (\Exception $e) {
            Log::error('Subscription success error: '.$e->getMessage());

            return redirect()->route('dashboard')
                ->with('error', 'There was an issue processing your subscription.');
        }
    }

    /**
     * Subscription cancel page
     */
    public function cancel()
    {
        return view('subscription.cancel');
    }

    /**
     * Webhook handler for Stripe events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->stripeService->constructWebhookEvent($payload, $signature);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;

                default:
                    Log::info('Unhandled Stripe event: '.$event->type);
            }

            return response()->json(['received' => true]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Handle checkout completed
     */
    protected function handleCheckoutCompleted(object $session): void
    {
        $customerId = $session->customer;
        $subscriptionId = $session->subscription;

        // Find creator by Stripe customer ID
        $creator = Creator::where('stripe_customer_id', $customerId)->first();

        if (! $creator) {
            Log::warning("Creator not found for Stripe customer: {$customerId}");

            return;
        }

        // Fetch full subscription details
        $subscription = $this->stripeService->getSubscription($subscriptionId);

        // Determine plan based on price ID
        $priceId = $subscription->items->data[0]->price->id ?? null;
        $plan = $this->mapPriceToPlan($priceId);

        // Track conversion if upgrading from free
        $wasFreePlan = $creator->plan === 'free' || $creator->plan === null;

        // Update creator subscription info
        $creator->stripe_subscription_id = $subscriptionId;
        $creator->plan = $plan;
        $creator->subscription_status = $subscription->status;
        $creator->subscription_ends_at = now()->addSeconds($subscription->current_period_end - time());
        $creator->save();

        // Track conversion event for analytics
        if ($wasFreePlan && $plan === 'pro') {
            ConversionEvent::track(
                $creator->id,
                ConversionEvent::EVENT_CONVERSION,
                'stripe_checkout',
                ['plan' => $plan, 'subscription_id' => $subscriptionId]
            );
        }

        Log::info("Subscription activated for creator: {$creator->id}, plan: {$plan}");
    }

    /**
     * Handle subscription updated
     */
    protected function handleSubscriptionUpdated(object $subscription): void
    {
        $subscriptionId = $subscription->id;
        $creator = Creator::where('stripe_subscription_id', $subscriptionId)->first();

        if (! $creator) {
            Log::warning("Creator not found for subscription: {$subscriptionId}");

            return;
        }

        // Determine plan based on price ID
        $priceId = $subscription->items->data[0]->price->id ?? null;
        $plan = $this->mapPriceToPlan($priceId);

        $creator->plan = $plan;
        $creator->subscription_status = $subscription->status;
        $creator->subscription_ends_at = now()->addSeconds($subscription->current_period_end - time());
        $creator->save();

        Log::info("Subscription updated for creator: {$creator->id}, status: {$subscription->status}");
    }

    /**
     * Handle subscription deleted
     */
    protected function handleSubscriptionDeleted(object $subscription): void
    {
        $subscriptionId = $subscription->id;
        $creator = Creator::where('stripe_subscription_id', $subscriptionId)->first();

        if (! $creator) {
            Log::warning("Creator not found for subscription: {$subscriptionId}");

            return;
        }

        // Revert to free plan
        $creator->plan = 'free';
        $creator->subscription_status = 'inactive';
        $creator->stripe_subscription_id = null;
        $creator->subscription_ends_at = null;
        $creator->save();

        Log::info("Subscription canceled for creator: {$creator->id}");
    }

    /**
     * Map Stripe price ID to plan name
     */
    protected function mapPriceToPlan(?string $priceId): string
    {
        if (! $priceId) {
            return 'free';
        }

        $monthlyPriceId = config('services.stripe.monthly_price_id');
        $annualPriceId = config('services.stripe.annual_price_id');

        if ($priceId === $monthlyPriceId || $priceId === $annualPriceId) {
            return 'pro';
        }

        return 'free';
    }

    /**
     * Handle payment failed
     */
    protected function handlePaymentFailed(object $invoice): void
    {
        Log::warning("Payment failed for invoice: {$invoice->id}");
    }

    /**
     * Manage subscription - redirect to Stripe billing portal
     */
    public function manage(Request $request)
    {
        $user = Auth::user();
        $creator = $user->creator;

        if (! $creator->stripe_customer_id) {
            return redirect()->route('dashboard')
                ->with('error', 'No billing account found.');
        }

        try {
            $session = $this->stripeService->createBillingPortalSession(
                $creator->stripe_customer_id,
                route('dashboard')
            );

            return redirect($session->url, 303);
        } catch (\Exception $e) {
            Log::error('Billing portal error: '.$e->getMessage());

            return redirect()->route('dashboard')
                ->with('error', 'Unable to access billing portal. Please contact support.');
        }
    }

    /**
     * Show subscription management page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $creator = $user->creator;

        $reviewsCount = Review::where('creator_id', $creator->id)->count();
        $approvedCount = Review::where('creator_id', $creator->id)->approved()->count();
        $pendingCount = Review::where('creator_id', $creator->id)->pending()->count();

        return view('subscription.manage', compact('creator', 'reviewsCount', 'approvedCount', 'pendingCount'));
    }
}
