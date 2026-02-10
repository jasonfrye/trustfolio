<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Collect customer testimonials and display them on your website with a beautiful widget. Simple setup, professional results. Free to start.">

        <title>ReviewBridge - Collect & Display Customer Testimonials</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
    </head>
    <body class="antialiased">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <a href="/" class="group flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25">
                            <svg class="w-6 h-6 text-white group-hover:text-amber-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">ReviewBridge</span>
                    </a>

                    <div class="hidden md:flex items-center gap-10">
                        <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">How It Works</a>
                        <a href="#features" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Features</a>
                        <a href="/pricing" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Pricing</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">Start Free</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-24 px-6 lg:px-8 overflow-hidden">
            <div class="absolute inset-0 bg-[#102a43]">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 50% 0%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);"></div>
            </div>

            <div class="relative max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-900/30 border border-emerald-700/30 text-emerald-300 rounded-full text-sm font-medium mb-8">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        Free forever • No credit card required
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white mb-8 leading-tight">
                        Collect Customer Testimonials.<br>
                        <span class="text-emerald-400">Display Them Anywhere.</span>
                    </h1>

                    <p class="text-xl sm:text-2xl text-gray-300 mb-6 max-w-3xl mx-auto leading-relaxed">
                        Simple testimonial collection for your business. Get authentic customer feedback and showcase it beautifully on your website in minutes.
                    </p>

                    <p class="text-base text-gray-400 mb-12 max-w-2xl mx-auto">
                        Perfect for service businesses, consultants, agencies, and local businesses who want to showcase customer success stories and build trust.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center mb-16">
                        <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-xl shadow-emerald-900/30 hover:shadow-2xl w-full sm:w-auto">
                            Start Collecting Testimonials Free
                        </a>
                        <a href="#how-it-works" class="border-2 border-gray-500 text-gray-300 hover:text-white hover:border-gray-300 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 w-full sm:w-auto">
                            See How It Works
                        </a>
                    </div>

                    <!-- Product Preview Card -->
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-[#243b53] rounded-2xl shadow-2xl p-8 border border-gray-700/50">
                            <div class="bg-white rounded-xl p-8 shadow-xl">
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">How It Works</h3>
                                    <p class="text-gray-600">Three simple steps to showcase testimonials</p>
                                </div>
                                <div class="grid sm:grid-cols-3 gap-6">
                                    <!-- Step 1 -->
                                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold mx-auto mb-4">1</div>
                                        <p class="font-semibold text-gray-900 mb-2 text-center">Share Your Link</p>
                                        <p class="text-sm text-gray-600 text-center">Send customers a simple form to rate and review you</p>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-200">
                                        <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold mx-auto mb-4">2</div>
                                        <p class="font-semibold text-emerald-900 mb-2 text-center">Approve the Best</p>
                                        <p class="text-sm text-emerald-800 text-center">Review submissions and approve testimonials for display</p>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold mx-auto mb-4">3</div>
                                        <p class="font-semibold text-gray-900 mb-2 text-center">Display on Your Site</p>
                                        <p class="text-sm text-gray-600 text-center">Embed a beautiful widget anywhere on your website</p>
                                    </div>
                                </div>
                                <div class="mt-6 text-center">
                                    <p class="text-xs text-gray-400">Powered by ReviewBridge</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-24 px-6 lg:px-8 bg-white">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Set Up in Under 5 Minutes</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">No integrations, no code required. Just copy, paste, and start collecting testimonials.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">1</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Create your account</h3>
                        <p class="text-gray-600 leading-relaxed">Sign up free. You'll get a unique collection link instantly—no setup wizard, no complicated forms.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">2</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Collect testimonials</h3>
                        <p class="text-gray-600 leading-relaxed">Text or email your link to customers. They rate their experience and write a testimonial. Simple.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">3</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Embed on your website</h3>
                        <p class="text-gray-600 leading-relaxed">Copy one line of code to display approved testimonials anywhere. Automatically updates as you approve new ones.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Who It's For -->
        <section class="py-24 px-6 lg:px-8 bg-[#f0f4f8]">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Built for Businesses That Value Trust</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">If customer success stories matter to your business, ReviewBridge helps you collect and showcase them.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        ['Consultants & Coaches', 'Build credibility with client success stories and testimonials.'],
                        ['Agencies', 'Showcase project results and client satisfaction on your portfolio.'],
                        ['SaaS Companies', 'Turn happy users into powerful social proof for your homepage.'],
                        ['Service Businesses', 'Display customer testimonials to build trust with new prospects.'],
                        ['Local Businesses', 'Collect and display reviews from satisfied customers automatically.'],
                        ['Freelancers', 'Build your reputation with testimonials from past clients.'],
                    ] as [$title, $desc])
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-[#102a43] mb-2">{{ $title }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-24 px-6 lg:px-8 bg-white">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Everything You Need, Nothing You Don't</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simple testimonial collection and display. Even on the free plan.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-x-16 gap-y-8 max-w-3xl mx-auto">
                    @foreach([
                        'Shareable collection link for easy testimonial gathering',
                        'Beautiful mobile-friendly testimonial form',
                        'Approve or reject submissions from your dashboard',
                        'Embeddable testimonial widget for any website',
                        'Customizable widget themes and colors (Pro)',
                        'Star ratings and written testimonials',
                        'Automatic updates—new testimonials appear instantly',
                        'No coding required—copy and paste embed code',
                    ] as $feature)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-gray-700">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Social Proof / Stats -->
        <section class="py-16 px-6 lg:px-8 bg-emerald-50">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-emerald-700 mb-2">10k+</div>
                        <div class="text-gray-600 font-medium">Testimonials Collected</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-700 mb-2">500+</div>
                        <div class="text-gray-600 font-medium">Businesses</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-700 mb-2">2 min</div>
                        <div class="text-gray-600 font-medium">Setup Time</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-700 mb-2">Free</div>
                        <div class="text-gray-600 font-medium">To Start</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-24 px-6 lg:px-8 bg-[#f0f4f8]">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-[#102a43] mb-6">Common Questions</h2>
                </div>

                <div class="space-y-6">
                    @foreach([
                        ['Is this actually free?', 'Yes. The Starter plan is free forever—up to 10 testimonials per month, no credit card required. Upgrade to Pro when you need unlimited testimonials and custom branding.'],
                        ['How do I collect testimonials?', 'Share your unique collection link via text, email, or QR code. Customers click it, rate their experience (1-5 stars), and write a testimonial. You approve the best ones from your dashboard.'],
                        ['Can I customize how testimonials look?', 'Yes! On Pro plans, you can customize widget colors, themes, and remove our branding. The widget is designed to match your website seamlessly.'],
                        ['Do I need technical skills?', 'No. Just copy and paste one line of code to embed the widget on your website. We provide simple instructions—if you can edit your website, you can add the widget.'],
                        ['Can customers leave fake testimonials?', 'You approve every testimonial before it appears on your widget. You have full control over what\'s displayed publicly.'],
                        ['What if I exceed my testimonial limit?', 'We\'ll notify you when you\'re approaching your monthly limit. You can upgrade to Pro for unlimited testimonials anytime, or your existing testimonials will remain visible.'],
                    ] as [$q, $a])
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-[#102a43] mb-2">{{ $q }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $a }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="relative py-24 px-6 lg:px-8 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#102a43] to-[#243b53]"></div>
            <div class="relative max-w-3xl mx-auto text-center">
                <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Start Collecting Testimonials Today</h2>
                <p class="text-xl text-gray-300 mb-4">Turn happy customers into powerful social proof.</p>
                <p class="text-base text-gray-400 mb-10">Free forever for up to 10 testimonials per month. No credit card required.</p>
                <a href="{{ route('register') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-xl hover:shadow-2xl">
                    Create Your Free Account
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 px-6 lg:px-8 bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="group flex items-center gap-3 cursor-default">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25">
                            <svg class="w-6 h-6 text-white group-hover:text-amber-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">ReviewBridge</span>
                    </div>
                    <div class="flex items-center gap-10">
                        <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">How It Works</a>
                        <a href="/pricing" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Pricing</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Privacy</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Terms</a>
                    </div>
                    <div class="text-gray-500 text-sm font-medium">© {{ date('Y') }} ReviewBridge. All rights reserved.</div>
                </div>
            </div>
        </footer>

        <script>
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        window.scrollTo({ top: target.offsetTop - 80, behavior: 'smooth' });
                    }
                });
            });
        </script>
    </body>
</html>
