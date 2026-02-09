<x-app-layout>
<div class="max-w-4xl mx-auto py-12">
    <h1 class="text-3xl font-bold mb-8">Subscription Management</h1>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Current Plan Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Current Plan</h2>

            <div class="mb-4">
                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                    @if($creator->plan === 'free') bg-gray-100 text-gray-800
                    @elseif($creator->plan === 'pro') bg-blue-100 text-blue-800
                    @else bg-purple-100 text-purple-800 @endif">
                    {{ ucfirst($creator->plan) }}
                </span>
            </div>

            @if($creator->subscription_status === 'active')
                <p class="text-gray-600 mb-2">
                    Status: <span class="text-green-600 font-medium">Active</span>
                </p>
                @if($creator->subscription_ends_at)
                    <p class="text-gray-600 mb-4">
                        Renews on: {{ $creator->subscription_ends_at->format('F j, Y') }}
                    </p>
                @endif
            @elseif($creator->subscription_status === 'past_due')
                <p class="text-gray-600 mb-2">
                    Status: <span class="text-red-600 font-medium">Past Due</span>
                </p>
                <p class="text-sm text-gray-500 mb-4">Please update your payment method to avoid service interruption.</p>
            @else
                <p class="text-gray-600 mb-2">
                    Status: <span class="text-gray-600 font-medium">Inactive</span>
                </p>
                <p class="text-sm text-gray-500 mb-4">You're on the free plan with limited features.</p>
            @endif

            <a href="{{ route('subscription.manage') }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Manage Billing
            </a>
        </div>

        <!-- Plan Details Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Plan Features</h2>

            <ul class="space-y-3">
                <li class="flex items-center">
                    @if($creator->plan !== 'free')
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    Up to 10 reviews
                </li>
                <li class="flex items-center">
                    @if($creator->hasPremiumSubscription())
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    Unlimited reviews
                </li>
                <li class="flex items-center">
                    @if($creator->hasPremiumSubscription())
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    Remove "Powered by ReviewBridge" branding
                </li>
                <li class="flex items-center">
                    @if($creator->hasPremiumSubscription())
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    Custom widget colors
                </li>
            </ul>

            @if($creator->plan === 'free')
                <div class="mt-6 pt-6 border-t">
                    <a href="{{ route('pricing') }}"
                       class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Upgrade to Pro
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Usage Stats -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Usage</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Reviews</p>
                <p class="text-2xl font-bold">
                    {{ $reviewsCount }}
                    @if(!$creator->hasUnlimitedReviews())
                        <span class="text-sm font-normal text-gray-500">/ 10</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Approved</p>
                <p class="text-2xl font-bold">{{ $approvedCount }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Pending</p>
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
