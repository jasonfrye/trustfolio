<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="TrustFolio - Collect and display testimonials from your customers with beautiful embeddable widgets.">

        <title>TrustFolio - Collect Beautiful Customer Testimonials</title>

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
                        <a href="#features" class="text-gray-600 hover:text-gray-900 transition">Features</a>
                        <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 transition">How It Works</a>
                        <a href="/pricing" class="text-gray-600 hover:text-gray-900 transition">Pricing</a>
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

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-indigo-50/50 to-white">
            <div class="max-w-7xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-8">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Trusted by 500+ creators
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Turn Customer Love Into <span class="text-indigo-600">Social Proof</span>
                </h1>

                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                    Collect and display beautiful testimonials on your website. Build trust, boost conversions, and grow your audience.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl w-full sm:w-auto">
                        Start Collecting Free
                    </a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 font-medium px-8 py-4 rounded-xl transition w-full sm:w-auto">
                        See How It Works →
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-16 flex justify-center gap-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">10K+</div>
                        <div class="text-gray-600">Testimonials</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">500+</div>
                        <div class="text-gray-600">Creators</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">4.9★</div>
                        <div class="text-gray-600">Average Rating</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Everything You Need to Showcase Social Proof</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simple tools to collect, manage, and display testimonials that convert.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Unique Collection Link</h3>
                        <p class="text-gray-600">Share a simple link with your customers to collect testimonials effortlessly.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Moderation Dashboard</h3>
                        <p class="text-gray-600">Approve or reject testimonials before they go live on your site.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Customizable Widgets</h3>
                        <p class="text-gray-600">Match your brand with custom colors, layouts, and styling options.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Embed Code</h3>
                        <p class="text-gray-600">Copy and paste a simple script tag to display testimonials anywhere.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Simple Pricing</h3>
                        <p class="text-gray-600">Free to start, affordable upgrades as you grow.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Spam, Ever</h3>
                        <p class="text-gray-600">Your data stays yours. We never sell or share customer information.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">How TrustFolio Works</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Get up and running in minutes, not hours.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">1</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Create Your Account</h3>
                        <p class="text-gray-600">Sign up in seconds and get your unique collection URL.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">2</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Share the Link</h3>
                        <p class="text-gray-600">Send your collection link to customers or embed the form on your site.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">3</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Display Everywhere</h3>
                        <p class="text-gray-600">Add the embed code to your website and watch testimonials roll in.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-indigo-600">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Build Trust?</h2>
                <p class="text-xl text-indigo-100 mb-8">Start collecting testimonials today. Free forever for up to 10 testimonials.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-100 text-indigo-600 px-8 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl">
                    Get Started Free
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
                        <a href="#features" class="text-gray-600 hover:text-gray-900 transition">Features</a>
                        <a href="/pricing" class="text-gray-600 hover:text-gray-900 transition">Pricing</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Privacy</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition">Terms</a>
                    </div>

                    <div class="text-gray-500 text-sm">
                        © {{ date('Y') }} TrustFolio. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>

        <script>
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
