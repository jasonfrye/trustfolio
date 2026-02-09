<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ReviewBridge pricing - Free to start, affordable as you grow. Simple, transparent pricing for review management.">

        <title>Pricing - ReviewBridge</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">ReviewBridge</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 transition">Home</a>
                        <a href="{{ url('/') }}#features" class="text-gray-600 hover:text-gray-900 transition">Features</a>
                        <a href="{{ url('/pricing') }}" class="text-emerald-600 font-medium">Pricing</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg font-medium transition shadow-sm hover:shadow-md">
                                Start Free
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div x-data="{ annual: false }">
            <!-- Pricing Header -->
            <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900" style="background: linear-gradient(135deg, #102a43 0%, #1e3a52 50%, #102a43 100%);">
                <div class="max-w-7xl mx-auto text-center">
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Simple, Transparent Pricing</h1>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-10">Start free and scale as you grow. No hidden fees, no surprises.</p>

                    <!-- Billing Toggle -->
                    <div class="flex items-center justify-center gap-4">
                        <span :class="annual ? 'text-gray-400' : 'text-white font-semibold'" class="transition text-base">Monthly</span>
                        <button
                            @click="annual = !annual"
                            :class="annual ? 'bg-emerald-500' : 'bg-gray-600'"
                            class="relative inline-flex h-8 w-14 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                        >
                            <span
                                :class="annual ? 'translate-x-7' : 'translate-x-1'"
                                class="inline-block h-6 w-6 rounded-full bg-white shadow-lg transition-transform duration-200 ease-in-out"
                            ></span>
                        </button>
                        <span :class="annual ? 'text-white font-semibold' : 'text-gray-400'" class="transition text-base">
                            Annual
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-500 text-amber-900 ml-1">
                                Save 20%
                            </span>
                        </span>
                    </div>
                </div>
            </section>

            <!-- Pricing Cards -->
            <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-white">
                <div class="max-w-7xl mx-auto">
                    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        <!-- Starter Plan -->
                        <div class="p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-gray-300 transition-all hover:shadow-lg">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                            <p class="text-gray-600 mb-6">Perfect for getting started</p>
                            <div class="mb-6">
                                <span class="text-5xl font-bold text-gray-900">$0</span>
                                <span class="text-gray-600 text-lg">/month</span>
                            </div>
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Up to 10 reviews</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Review funnel</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Basic widget</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Private feedback capture</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-center transition">
                                Get Started Free
                            </a>
                        </div>

                        <!-- Pro Plan (Featured) -->
                        <div class="p-8 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl shadow-2xl relative transform md:scale-105 hover:scale-110 transition-transform duration-200" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-amber-500 text-amber-900 text-sm font-bold rounded-full shadow-lg">
                                Most Popular
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                            <p class="text-emerald-100 mb-6">For growing businesses</p>
                            <div class="mb-6">
                                <span x-show="!annual" class="text-5xl font-bold text-white">$15</span>
                                <span x-show="annual" x-cloak class="text-5xl font-bold text-white">$12</span>
                                <span class="text-emerald-100 text-lg" x-text="annual ? '/month' : '/month'"></span>
                            </div>
                            <p class="text-emerald-100 text-sm mb-6" x-show="annual" x-cloak>Billed annually at $144/year</p>
                            <p class="text-emerald-100 text-sm mb-6" x-show="!annual">Billed monthly</p>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-200 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-emerald-100">Everything in Starter</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Unlimited reviews</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Custom branding</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Multi-platform routing</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Remove branding</span>
                                </li>
                            </ul>

                            @auth
                                <form action="{{ route('subscription.checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" :value="annual ? 'annual' : 'monthly'">
                                    <button type="submit" class="block w-full py-3.5 px-4 bg-white hover:bg-gray-50 text-emerald-700 font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl">
                                        Start 14-Day Free Trial
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('register') }}" class="block w-full py-3.5 px-4 bg-white hover:bg-gray-50 text-emerald-700 font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl">
                                    Start 14-Day Free Trial
                                </a>
                            @endauth

                            <p class="text-emerald-100 text-xs text-center mt-4">No credit card required • Cancel anytime</p>
                        </div>

                        <!-- Business Plan -->
                        <div class="p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-gray-300 transition-all hover:shadow-lg">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Business</h3>
                            <p class="text-gray-600 mb-6">For teams and agencies</p>
                            <div class="mb-6">
                                <span x-show="!annual" class="text-5xl font-bold text-gray-900">$29</span>
                                <span x-show="annual" x-cloak class="text-5xl font-bold text-gray-900">$23</span>
                                <span class="text-gray-600 text-lg" x-text="annual ? '/month' : '/month'"></span>
                            </div>
                            <p class="text-gray-600 text-sm mb-6" x-show="annual" x-cloak>Billed annually at $276/year</p>
                            <p class="text-gray-600 text-sm mb-6" x-show="!annual">Billed monthly</p>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Everything in Pro</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Multiple collections</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Team collaboration</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Priority support</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-center transition">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- end Alpine x-data wrapper -->

        <!-- FAQ Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8" style="background-color: #f0f4f8;">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-bold text-center mb-4" style="color: #102a43;">Frequently Asked Questions</h2>
                <p class="text-center text-gray-600 mb-12 text-lg">Everything you need to know about our pricing</p>

                <div class="space-y-4" x-data="{ openFaq: null }">
                    <!-- FAQ 1 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 1 ? null : 1"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">Can I change plans later?</h3>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="openFaq === 1 ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === 1"
                            x-collapse
                            class="px-6 pb-5"
                        >
                            <p class="text-gray-600 leading-relaxed">Yes! You can upgrade or downgrade your plan at any time from your dashboard. Changes take effect immediately, and we'll prorate any charges or credits accordingly.</p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 2 ? null : 2"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">Is there a free trial for Pro?</h3>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="openFaq === 2 ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === 2"
                            x-collapse
                            class="px-6 pb-5"
                        >
                            <p class="text-gray-600 leading-relaxed">Yes! Pro plans come with a 14-day free trial. No credit card required to start. You'll have full access to all Pro features during your trial period.</p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 3 ? null : 3"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">What happens when I exceed my review limit?</h3>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="openFaq === 3 ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === 3"
                            x-collapse
                            class="px-6 pb-5"
                        >
                            <p class="text-gray-600 leading-relaxed">Don't worry! We'll notify you when you're approaching your limit. You'll have time to upgrade before new submissions are blocked. Your existing reviews will always remain accessible.</p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 4 ? null : 4"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">Can I cancel anytime?</h3>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="openFaq === 4 ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === 4"
                            x-collapse
                            class="px-6 pb-5"
                        >
                            <p class="text-gray-600 leading-relaxed">Absolutely. Cancel anytime from your dashboard with just one click. You'll continue to have access until the end of your billing period, and we won't charge you again.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900" style="background: linear-gradient(135deg, #102a43 0%, #1e3a52 50%, #102a43 100%);">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Join hundreds of businesses protecting their online reputation with ReviewBridge. Start your free trial today.</p>
                <a href="{{ route('register') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl hover:shadow-2xl transform hover:scale-105">
                    Create Your Free Account
                </a>
                <p class="text-gray-400 text-sm mt-4">No credit card required • Free forever for up to 10 reviews</p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">ReviewBridge</span>
                    </div>

                    <div class="flex items-center gap-8">
                        <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 transition">Home</a>
                        <a href="{{ url('/pricing') }}" class="text-emerald-600 font-medium">Pricing</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Privacy</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Terms</a>
                    </div>

                    <div class="text-gray-500 text-sm">
                        © {{ date('Y') }} ReviewBridge. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
