<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="TrustFolio pricing - Free to start, affordable as you grow.">

        <title>Pricing - TrustFolio</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-gray-900 antialiased">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">TrustFolio</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="/" class="text-gray-600 hover:text-gray-900 transition">Home</a>
                        <a href="/#features" class="text-gray-600 hover:text-gray-900 transition">Features</a>
                        <a href="/pricing" class="text-indigo-600 font-medium">Pricing</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition shadow-sm hover:shadow-md">
                                Start Free
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Pricing Header -->
        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-indigo-50/50 to-white">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Start free and scale as you grow. No hidden fees, no surprises.</p>

                <!-- Billing Toggle -->
                <div class="mt-8 flex items-center justify-center gap-4">
                    <span class="text-gray-900 font-medium">Monthly</span>
                    <button class="relative w-14 h-7 bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-gray-600">Annual <span class="text-indigo-600 font-medium text-sm">(Save 20%)</span></span>
                </div>
            </div>
        </section>

        <!-- Pricing Cards -->
        <section class="py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Free Plan -->
                    <div class="p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-indigo-200 transition">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Starter</h3>
                        <p class="text-gray-600 mb-6">Perfect for getting started</p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">$0</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Up to 10 testimonials</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Unique collection URL</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Basic widget themes</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Moderation dashboard</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl text-center transition">
                            Get Started Free
                        </a>
                    </div>

                    <!-- Pro Plan -->
                    <div class="p-8 bg-indigo-600 rounded-2xl shadow-xl relative">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-yellow-400 text-yellow-900 text-sm font-semibold rounded-full">
                            Most Popular
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Pro</h3>
                        <p class="text-indigo-200 mb-6">For growing creators</p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-white">$15</span>
                            <span class="text-indigo-200">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Unlimited testimonials</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Custom branding</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Advanced widget customization</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Remove TrustFolio branding</span>
                            </li>
                        </ul>

                        @auth
                            <form action="{{ route('subscription.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="monthly">
                                <button type="submit" class="block w-full py-3 px-4 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-xl text-center transition">
                                    Start Free Trial
                                </button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-xl text-center transition">
                                Start Free Trial
                            </a>
                        @endauth
                    </div>

                    <!-- Business Plan -->
                    <div class="p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-indigo-200 transition">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Business</h3>
                        <p class="text-gray-600 mb-6">For teams and agencies</p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">$29</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Everything in Pro</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Multiple collections</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Team collaboration</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Priority support</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl text-center transition">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Frequently Asked Questions</h2>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change plans later?</h3>
                        <p class="text-gray-600">Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial for Pro?</h3>
                        <p class="text-gray-600">Yes, Pro plans come with a 14-day free trial. No credit card required to start.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens when I exceed my testimonial limit?</h3>
                        <p class="text-gray-600">Don't worry! We'll notify you when you're approaching your limit. You'll have time to upgrade before new submissions are blocked.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I cancel anytime?</h3>
                        <p class="text-gray-600">Absolutely. Cancel anytime from your dashboard. You'll continue to have access until the end of your billing period.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-indigo-600">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
                <p class="text-xl text-indigo-100 mb-8">Join thousands of creators building trust with TrustFolio.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-100 text-indigo-600 px-8 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl">
                    Create Your Free Account
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">TrustFolio</span>
                    </div>

                    <div class="flex items-center gap-8">
                        <a href="/" class="text-gray-600 hover:text-gray-900 transition">Home</a>
                        <a href="/pricing" class="text-indigo-600 font-medium">Pricing</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Privacy</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Terms</a>
                    </div>

                    <div class="text-gray-500 text-sm">
                        © {{ date('Y') }} TrustFolio. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
