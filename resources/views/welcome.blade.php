<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ReviewBridge helps local businesses get more 5-star Google reviews by filtering unhappy customers to private feedback first. Free to start.">

        <title>ReviewBridge - Get More 5-Star Google Reviews</title>

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
                        <a href="#who-its-for" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Who It's For</a>
                        <a href="/pricing" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Pricing</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">Get Started Free</a>
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
                        Free to start, no credit card required
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white mb-8 leading-tight">
                        Get More 5-Star Reviews.<br>
                        <span class="text-emerald-400">Fewer Bad Ones.</span>
                    </h1>

                    <p class="text-xl sm:text-2xl text-gray-300 mb-6 max-w-3xl mx-auto leading-relaxed">
                        Send customers a simple link after every appointment. Happy ones go straight to Google. Unhappy ones come to you first.
                    </p>

                    <p class="text-base text-gray-400 mb-12 max-w-2xl mx-auto">
                        Used by dentists, contractors, salons, clinics, and local businesses who want to grow their Google reviews without the risk.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center mb-16">
                        <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-xl shadow-emerald-900/30 hover:shadow-2xl w-full sm:w-auto">
                            Create Your Free Review Link
                        </a>
                        <a href="#how-it-works" class="border-2 border-gray-500 text-gray-300 hover:text-white hover:border-gray-300 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 w-full sm:w-auto">
                            See How It Works
                        </a>
                    </div>

                    <!-- Product Preview Card -->
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-[#243b53] rounded-2xl shadow-2xl p-8 border border-gray-700/50">
                            <div class="bg-white rounded-xl p-8 shadow-xl">
                                <div class="text-left mb-6">
                                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Here's What Your Customers See</span>
                                </div>
                                <div class="grid sm:grid-cols-3 gap-6">
                                    <!-- Step 1: Rate -->
                                    <div class="bg-gray-50 rounded-xl p-6 flex flex-col items-center justify-center border border-gray-200">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-sm mb-4">1</div>
                                        <p class="text-gray-800 text-sm font-semibold mb-3">Customer rates you</p>
                                        <div class="flex gap-1 text-yellow-400">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                    </div>

                                    <!-- Step 2: Happy path -->
                                    <div class="bg-emerald-50 rounded-xl p-6 flex flex-col items-center justify-center border border-emerald-200">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-sm mb-4">2a</div>
                                        <p class="text-emerald-800 text-sm font-semibold mb-3">Happy? Go to Google</p>
                                        <div class="bg-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-md">
                                            Leave a Google Review
                                        </div>
                                    </div>

                                    <!-- Step 2: Unhappy path -->
                                    <div class="bg-gray-50 rounded-xl p-6 flex flex-col items-center justify-center border border-gray-200">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-sm mb-4">2b</div>
                                        <p class="text-gray-800 text-sm font-semibold mb-3">Unhappy? Private feedback</p>
                                        <div class="bg-gray-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                                            Send Private Feedback
                                        </div>
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
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Set Up in 2 Minutes. Seriously.</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">No integrations, no code, no technical skills needed. Just a link you text or email to customers.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">1</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Create your account</h3>
                        <p class="text-gray-600 leading-relaxed">Sign up free. Set your rating threshold (e.g. 4 stars and up go to Google). Add your Google review link.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">2</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Share your link</h3>
                        <p class="text-gray-600 leading-relaxed">Text it after appointments, add it to emails, or put it on a QR code in your office. One link does everything.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-8 shadow-lg shadow-emerald-600/25">3</div>
                        <h3 class="text-xl font-bold text-[#102a43] mb-3">Reviews grow, complaints don't</h3>
                        <p class="text-gray-600 leading-relaxed">Happy customers land on Google. Unhappy ones send feedback directly to you, so you can fix issues privately.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Who It's For -->
        <section id="who-its-for" class="py-24 px-6 lg:px-8 bg-[#f0f4f8]">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Built for Local Businesses</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">If you see customers in person and want more Google reviews, this is for you.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        ['Dental Practices', 'Follow up after cleanings and procedures to build your Google presence.'],
                        ['Home Services', 'Plumbers, electricians, HVAC — text the link when the job is done.'],
                        ['Salons & Spas', 'Turn every great haircut into a 5-star review automatically.'],
                        ['Medical Clinics', 'Capture patient satisfaction privately and grow your online reputation.'],
                        ['Auto Repair', 'Happy customers rarely leave reviews on their own. Now they will.'],
                        ['Any Local Business', 'If customers rate you in person, ReviewBridge works for you.'],
                    ] as [$title, $desc])
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-[#102a43] mb-2">{{ $title }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- What You Get -->
        <section class="py-24 px-6 lg:px-8 bg-white">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-[#102a43] mb-6">Everything Included, Even on Free</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-x-16 gap-y-8 max-w-3xl mx-auto">
                    @foreach([
                        'Smart review routing to Google, Yelp, or any platform',
                        'Private feedback capture for unhappy customers',
                        'Customizable rating threshold (you choose the cutoff)',
                        'Embeddable review widget for your website',
                        'Works on any device — just a link, no app needed',
                        'Dashboard to manage and approve reviews',
                        'Multiple platform routing (Google, Trustpilot, Yelp)',
                        'Custom branding and messaging',
                    ] as $feature)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-gray-700">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Objection Handling / Trust -->
        <section class="py-24 px-6 lg:px-8 bg-[#f0f4f8]">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-[#102a43] mb-6">Common Questions</h2>
                </div>

                <div class="space-y-6">
                    @foreach([
                        ['Is this actually free?', 'Yes. The Starter plan is free forever — up to 10 reviews, no credit card required. We make money when businesses grow and upgrade to Pro for unlimited reviews.'],
                        ['How is this different from just asking for reviews?', 'When you ask everyone to review you on Google, unhappy customers leave bad reviews publicly. ReviewBridge filters by rating first — only happy customers get sent to Google. Unhappy ones give you private feedback instead.'],
                        ['Do I need any technical skills?', 'No. Sign up, paste your Google review link, and you get a URL to share. That\'s it. If you can send a text message, you can use ReviewBridge.'],
                        ['Will customers know they\'re being filtered?', 'No. They just see a simple "How was your experience?" rating page. It feels natural and professional — like any feedback form.'],
                        ['Can I try it before committing?', 'The free plan is permanent, not a trial. Use it as long as you want. If you need unlimited reviews or custom branding, Pro is $15/month with a 14-day trial.'],
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
                <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">Your next customer is about to leave a review.</h2>
                <p class="text-xl text-gray-300 mb-4">Will it go to Google, or will it go to you first?</p>
                <p class="text-base text-gray-400 mb-10">Free forever for up to 10 reviews. Takes 2 minutes to set up.</p>
                <a href="{{ route('register') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-xl hover:shadow-2xl">
                    Create Your Free Review Link
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
