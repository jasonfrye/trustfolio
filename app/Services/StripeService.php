<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new Stripe;
        $this->stripe->setApiKey(config('services.stripe.secret'));
        $this->stripe->setApiVersion('2024-12-18.acacia');
    }

    /**
     * Create or retrieve a Stripe customer
     */
    public function findOrCreateCustomer(string $email, string $name): Customer
    {
        $customers = Customer::all(['email' => $email, 'limit' => 1]);

        if ($customers->data) {
            return $customers->data[0];
        }

        return Customer::create([
            'email' => $email,
            'name' => $name,
        ]);
    }

    /**
     * Create a checkout session for subscription
     */
    public function createSubscriptionCheckout(
        string $customerId,
        string $priceId,
        string $successUrl,
        string $cancelUrl
    ): Session {
        return Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'allow_promotion_codes' => true,
        ]);
    }

    /**
     * Create a checkout session for one-time payment (if needed)
     */
    public function createOneTimeCheckout(
        int $amount, // in cents
        string $productName,
        string $successUrl,
        string $cancelUrl
    ): Session {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $productName,
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): Subscription
    {
        $subscription = Subscription::retrieve($subscriptionId);

        return $subscription->cancel();
    }

    /**
     * Get subscription details
     */
    public function getSubscription(string $subscriptionId): Subscription
    {
        return Subscription::retrieve($subscriptionId);
    }

    /**
     * Create a product (for admin use)
     */
    public function createProduct(string $name, string $description): Product
    {
        return Product::create([
            'name' => $name,
            'description' => $description,
        ]);
    }

    /**
     * Create a price for a product (for admin use)
     */
    public function createPrice(string $productId, int $amount, string $interval): Price
    {
        return Price::create([
            'product' => $productId,
            'unit_amount' => $amount,
            'currency' => 'usd',
            'recurring' => [
                'interval' => $interval,
            ],
        ]);
    }

    /**
     * Construct webhook event
     */
    public function constructWebhookEvent(string $payload, string $signature): object
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        return \Stripe\Webhook::constructEvent($payload, $signature, $webhookSecret);
    }

    /**
     * Create a billing portal session for customer
     */
    public function createBillingPortalSession(string $customerId, string $returnUrl): object
    {
        $portalSession = new \Stripe\BillingPortal\Session;

        return $portalSession->create([
            'customer' => $customerId,
            'return_url' => $returnUrl,
        ]);
    }
}
