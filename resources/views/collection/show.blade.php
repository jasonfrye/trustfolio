<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Share Your Experience - {{ $creator->display_name }}</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Inter', sans-serif;
        }

        .star-icon {
            transition: all 0.2s ease;
        }

        .star-icon:hover {
            transform: scale(1.1);
        }

        .star-icon.filled {
            color: #fbbf24;
        }

        .star-icon.empty {
            color: #d1d5db;
        }

        .premium-card {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-2xl">
            <div x-data="testimonialFlow()" x-cloak class="premium-card bg-white rounded-2xl p-6 sm:p-10">

                <!-- Step 1: Rating Selection -->
                <div x-show="step === 'rating'" x-transition.opacity.duration.300ms>
                    <div class="text-center">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-8">
                            How was your experience with {{ $creator->display_name }}?
                        </h1>

                        <div class="flex justify-center gap-2 sm:gap-4 mb-4">
                            <template x-for="star in 5" :key="star">
                                <button
                                    @click="selectRating(star)"
                                    @mouseenter="hoverRating = star"
                                    @mouseleave="hoverRating = 0"
                                    class="star-icon text-5xl sm:text-6xl cursor-pointer focus:outline-none"
                                    :class="(hoverRating >= star || rating >= star) ? 'filled' : 'empty'"
                                    type="button"
                                >
                                    ★
                                </button>
                            </template>
                        </div>

                        <p class="text-slate-500 text-sm">Tap a star to rate</p>
                    </div>
                </div>

                <!-- Step 2: Testimonial Form -->
                <div x-show="step === 'form'" x-transition.opacity.duration.300ms>
                    <div class="mb-6 text-center">
                        <div class="flex justify-center gap-1 mb-3">
                            <template x-for="star in 5" :key="star">
                                <span
                                    class="text-3xl"
                                    :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                >
                                    ★
                                </span>
                            </template>
                        </div>
                        <p class="text-slate-700 text-lg">Thank you! We'd love to hear about your experience.</p>
                    </div>

                    <form @submit.prevent="submitTestimonial" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                                Your Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                x-model="form.name"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-20 transition-colors"
                                placeholder="John Doe"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                                Email (optional)
                            </label>
                            <input
                                type="email"
                                id="email"
                                x-model="form.email"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-20 transition-colors"
                                placeholder="john@example.com"
                            >
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                                Title (optional)
                            </label>
                            <input
                                type="text"
                                id="title"
                                x-model="form.title"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-20 transition-colors"
                                placeholder="Great experience!"
                            >
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                                Your Testimonial <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="content"
                                x-model="form.content"
                                required
                                rows="5"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-20 transition-colors resize-none"
                                placeholder="Tell us about your experience..."
                            ></textarea>
                            <p class="text-xs text-slate-500 mt-1">Minimum 10 characters</p>
                        </div>

                        <div x-show="errorMessage" class="text-red-600 text-sm text-center">
                            <span x-text="errorMessage"></span>
                        </div>

                        <!-- Consent Disclaimer -->
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm text-slate-600">
                            <p class="mb-2">
                                <strong class="text-slate-700">By submitting this testimonial, you agree that:</strong>
                            </p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Your testimonial may be publicly displayed on {{ $creator->display_name }}'s website and marketing materials</li>
                                <li>All testimonials are reviewed and approved before being published</li>
                                <li>You can contact {{ $creator->display_name }} at any time to request removal</li>
                            </ul>
                        </div>

                        <button
                            type="submit"
                            :disabled="submitting"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 text-white font-semibold py-4 rounded-xl transition-colors shadow-lg shadow-emerald-600/20"
                        >
                            <span x-show="!submitting">Submit Testimonial</span>
                            <span x-show="submitting">Submitting...</span>
                        </button>
                    </form>
                </div>

                <!-- Step 3: Thank You -->
                <div x-show="step === 'done'" x-transition.opacity.duration.300ms>
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 mb-4">
                                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-slate-900 mb-2">Thank You!</h2>
                            <p class="text-slate-600">Your testimonial has been submitted successfully and will be reviewed shortly.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-6 text-center">
        <p class="text-sm text-slate-400">Powered by ReviewBridge</p>
    </footer>

    <script>
        function testimonialFlow() {
            const params = new URLSearchParams(window.location.search);
            return {
                step: 'rating',
                rating: 0,
                hoverRating: 0,
                submitting: false,
                errorMessage: '',
                token: params.get('token') || '',
                form: {
                    name: params.get('name') || @json($testimonialRequest?->recipient_name ?? ''),
                    email: params.get('email') || '',
                    title: '',
                    content: '',
                },
                selectRating(star) {
                    this.rating = star;
                    this.step = 'form';
                },
                async submitTestimonial() {
                    if (this.form.content.length < 10) {
                        this.errorMessage = 'Please enter at least 10 characters.';
                        return;
                    }
                    this.errorMessage = '';
                    this.submitting = true;
                    try {
                        const response = await fetch("{{ route('collection.submit', $creator->collection_url) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                rating: this.rating,
                                name: this.form.name || null,
                                email: this.form.email || null,
                                title: this.form.title || null,
                                content: this.form.content,
                                token: this.token || null,
                            }),
                        });
                        if (!response.ok) {
                            const data = await response.json();
                            this.errorMessage = data.message || 'Something went wrong. Please try again.';
                            return;
                        }
                        this.step = 'done';
                    } catch (e) {
                        this.errorMessage = 'Something went wrong. Please try again.';
                    } finally {
                        this.submitting = false;
                    }
                },
            };
        }
    </script>
</body>
</html>
