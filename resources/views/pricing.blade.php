<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Simple, transparent pricing for testimonial collection. Start free, upgrade when you grow. No hidden fees.">

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
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Simple, Honest Pricing</h1>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-10">Start free. Upgrade when you need unlimited testimonials. No contracts, cancel anytime.</p>

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
                    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
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
                                    <span class="text-gray-700"><strong>10 testimonials</strong> per month</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Testimonial collection form</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Embeddable widget</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Approve/reject workflow</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Star ratings & text reviews</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-gray-400">ReviewBridge branding shown</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-center transition">
                                Get Started Free
                            </a>
                            <p class="text-gray-500 text-xs text-center mt-4">Free forever • No credit card</p>
                        </div>

                        <!-- Pro Plan (Featured) -->
                        <div class="p-8 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl shadow-2xl relative ring-4 ring-emerald-400/50 hover:ring-emerald-400 transition-all duration-200" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-amber-500 text-amber-900 text-sm font-bold rounded-full shadow-lg">
                                Most Popular
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                            <p class="text-emerald-100 mb-6">For growing businesses</p>
                            <div class="mb-6">
                                <span x-show="!annual" class="text-5xl font-bold text-white">$19</span>
                                <span x-show="annual" x-cloak class="text-5xl font-bold text-white">$15</span>
                                <span class="text-emerald-100 text-lg">/month</span>
                            </div>
                            <p class="text-emerald-100 text-sm mb-6" x-show="annual" x-cloak>Billed annually at $180/year</p>
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
                                    <span class="text-white font-medium"><strong>Unlimited</strong> testimonials</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Custom widget colors & themes</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Remove "Powered by" branding</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white font-medium">Priority email support</span>
                                </li>
                            </ul>

                            @auth
                                <form action="{{ route('subscription.checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" :value="annual ? 'annual' : 'monthly'">
                                    <button type="submit" class="block w-full py-3.5 px-4 bg-white hover:bg-gray-50 text-emerald-700 font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl">
                                        Upgrade to Pro
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('register') }}" class="block w-full py-3.5 px-4 bg-white hover:bg-gray-50 text-emerald-700 font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl">
                                    Start Free Trial
                                </a>
                            @endauth

                            <p class="text-emerald-100 text-xs text-center mt-4">14-day free trial • Cancel anytime</p>
                        </div>
                    </div>

                    <!-- Value Prop Under Pricing -->
                    <div class="mt-16 text-center max-w-3xl mx-auto">
                        <p class="text-lg text-gray-600 mb-6">
                            All plans include access to your testimonial collection link, approval dashboard, and embeddable widget.
                            Upgrade to Pro when you need unlimited testimonials and custom branding.
                        </p>
                    </div>
                </div>
            </section>
        </div><!-- end Alpine x-data wrapper -->

        <!-- Comparison Table -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Feature Comparison</h2>
                    <p class="text-gray-600">See exactly what's included in each plan</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Feature</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Starter</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-emerald-700">Pro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">Monthly testimonials</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">10</td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-emerald-700">Unlimited</td>
                            </tr>
                            <tr class="bg-gray-50/50">
                                <td class="px-6 py-4 text-sm text-gray-700">Collection form & link</td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">Embeddable widget</td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr class="bg-gray-50/50">
                                <td class="px-6 py-4 text-sm text-gray-700">Approve/reject dashboard</td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">Custom widget colors & themes</td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr class="bg-gray-50/50">
                                <td class="px-6 py-4 text-sm text-gray-700">Remove ReviewBridge branding</td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-5 h-5 text-emerald-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">Priority support</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500">Email</td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-emerald-700">Priority email</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-bold text-center mb-4" style="color: #102a43;">Frequently Asked Questions</h2>
                <p class="text-center text-gray-600 mb-12 text-lg">Everything you need to know about pricing</p>

                <div class="space-y-4" x-data="{ openFaq: null }">
                    <!-- FAQ 1 -->
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 1 ? null : 1"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-100 transition"
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
                            <p class="text-gray-600 leading-relaxed">Yes! Upgrade to Pro anytime from your dashboard. Changes take effect immediately. If you downgrade from Pro to Starter, you'll keep Pro features until the end of your billing period.</p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 2 ? null : 2"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-100 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">Is the free plan really free forever?</h3>
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
                            <p class="text-gray-600 leading-relaxed">Yes! The Starter plan is free forever. No time limits, no credit card required. You can collect up to 10 testimonials per month and display them on your website indefinitely.</p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 3 ? null : 3"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-100 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">What happens when I exceed my limit on the free plan?</h3>
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
                            <p class="text-gray-600 leading-relaxed">We'll notify you when you're approaching your 10 testimonial monthly limit. Your existing testimonials remain visible on your widget. You can upgrade to Pro anytime for unlimited testimonials.</p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 4 ? null : 4"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-100 transition"
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
                            <p class="text-gray-600 leading-relaxed">Absolutely. Cancel your Pro subscription anytime from your dashboard. You'll continue to have Pro access until the end of your billing period, then you'll automatically move to the free Starter plan.</p>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <button
                            @click="openFaq = openFaq === 5 ? null : 5"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-100 transition"
                        >
                            <h3 class="text-lg font-semibold" style="color: #102a43;">Do you offer refunds?</h3>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="openFaq === 5 ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === 5"
                            x-collapse
                            class="px-6 pb-5"
                        >
                            <p class="text-gray-600 leading-relaxed">Yes. We offer a 14-day money-back guarantee on all Pro subscriptions. If you're not satisfied, contact us within 14 days of your purchase for a full refund.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900" style="background: linear-gradient(135deg, #102a43 0%, #1e3a52 50%, #102a43 100%);">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Start Collecting Testimonials Today</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Turn customer success stories into powerful social proof. Free forever for up to 10 testimonials per month.</p>
                <a href="{{ route('register') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl hover:shadow-2xl transform hover:scale-105">
                    Create Your Free Account
                </a>
                <p class="text-gray-400 text-sm mt-4">No credit card required • Takes 2 minutes to set up</p>
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
