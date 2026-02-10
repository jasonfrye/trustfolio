<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('testimonial-requests.index') }}" class="text-navy-500 hover:text-navy-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-navy-900">Send Testimonial Request</h1>
            </div>
            <p class="mt-2 text-navy-600">Request a testimonial from a customer via email</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 card-elevated p-4 border-l-4 border-red-500 bg-red-50/50">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-red-800 font-medium mb-1">There were some errors:</p>
                        <ul class="list-disc list-inside text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('testimonial-requests.store') }}" class="card-elevated p-6">
            @csrf

            <h2 class="text-xl font-semibold text-navy-900 mb-6">Recipient Information</h2>

            <!-- Recipient Email -->
            <div class="mb-6">
                <label for="recipient_email" class="block text-sm font-medium text-navy-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="recipient_email"
                    name="recipient_email"
                    value="{{ old('recipient_email') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-navy-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:ring-opacity-20 transition-colors @error('recipient_email') border-red-500 @enderror"
                    placeholder="customer@example.com"
                >
                @error('recipient_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recipient Name -->
            <div class="mb-6">
                <label for="recipient_name" class="block text-sm font-medium text-navy-700 mb-2">
                    Name (Optional)
                </label>
                <input
                    type="text"
                    id="recipient_name"
                    name="recipient_name"
                    value="{{ old('recipient_name') }}"
                    class="w-full px-4 py-3 rounded-xl border border-navy-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:ring-opacity-20 transition-colors @error('recipient_name') border-red-500 @enderror"
                    placeholder="John Doe"
                >
                <p class="mt-1 text-xs text-navy-500">The email will be personalized with their name if provided</p>
                @error('recipient_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-navy-700 mb-2">
                    Internal Notes (Optional)
                </label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-navy-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:ring-opacity-20 transition-colors resize-none @error('notes') border-red-500 @enderror"
                    placeholder="Add any internal notes about this request..."
                >{{ old('notes') }}</textarea>
                <p class="mt-1 text-xs text-navy-500">These notes are for your reference only and won't be included in the email</p>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Preview -->
            <div class="mb-6 bg-navy-50 border border-navy-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-navy-700 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Email Preview
                </h3>
                <div class="bg-white rounded-lg p-4 text-sm text-navy-700">
                    <p class="font-semibold mb-2">Subject: {{ $creator->display_name }} would love your feedback</p>
                    <div class="space-y-2 text-navy-600">
                        <p>Hi [Name],</p>
                        <p>We're always looking to improve and would love to hear about your experience with {{ $creator->display_name }}.</p>
                        <p>Your feedback helps us serve you better and helps others make informed decisions.</p>
                        <p class="text-brand-600 font-medium">[Share Your Feedback Button]</p>
                        <p>It only takes a minute, and your honest opinion means a lot to us.</p>
                        <p>Thank you for your time!</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Request
                </button>
                <a href="{{ route('testimonial-requests.index') }}" class="btn-ghost">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
