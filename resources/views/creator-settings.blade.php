<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-navy-900">Settings</h1>
            <p class="mt-2 text-navy-600">Manage your testimonial collection page and embed widget.</p>
        </div>

        <!-- Success Flash Message -->
        @if (session('success'))
            <div class="mb-6 card-elevated p-4 border-l-4 border-brand-500 bg-brand-50/50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-brand-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-brand-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Collection URL Section -->
        <div class="card-elevated p-6 mb-6">
            <h2 class="text-xl font-semibold text-navy-900 mb-4">Your Testimonial Collection Link</h2>
            <p class="text-navy-600 mb-4">Share this link to collect testimonials from your customers.</p>

            <div class="bg-navy-50 rounded-xl p-4 mb-4 border border-navy-200">
                <code class="text-brand-600 text-sm font-mono break-all">{{ $collectionUrl }}</code>
            </div>

            <div class="flex flex-wrap gap-3">
                <button onclick="copyCollectionUrl()" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy URL
                </button>
                <a href="{{ $collectionUrl }}" target="_blank" class="btn-ghost">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Open Page
                </a>
            </div>
        </div>

        <!-- Embed Widget Section -->
        <div class="card-elevated p-6 mb-6">
            <h2 class="text-xl font-semibold text-navy-900 mb-4">Embed Widget</h2>
            <p class="text-navy-600 mb-4">Copy this code to embed your approved reviews on any website.</p>

            <div class="bg-navy-900 rounded-xl p-4 mb-4 border border-navy-700 overflow-x-auto">
                <code class="text-brand-400 text-sm font-mono whitespace-pre">{{ $embedCode }}</code>
            </div>

            <div class="flex flex-wrap gap-3">
                <button onclick="copyEmbedCode()" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy Embed Code
                </button>
                <a href="{{ route('widget.settings') }}" class="btn-ghost">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Customize Widget
                </a>
            </div>
        </div>

        <!-- Profile Settings Form -->
        <form method="POST" action="{{ route('creator.settings.update') }}" class="card-elevated p-6">
            @csrf
            @method('PUT')

            <h2 class="text-xl font-semibold text-navy-900 mb-6">Profile Settings</h2>

            <!-- Display Name -->
            <div class="mb-6">
                <x-input-label for="display_name" value="Display Name" class="text-navy-900 font-semibold mb-2" />
                <x-text-input
                    id="display_name"
                    name="display_name"
                    type="text"
                    class="input-field"
                    :value="old('display_name', $creator->display_name)"
                    required
                    autofocus
                />
                <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
            </div>

            <!-- Website -->
            <div class="mb-6">
                <x-input-label for="website" value="Website" class="text-navy-900 font-semibold mb-2" />
                <x-text-input
                    id="website"
                    name="website"
                    type="url"
                    class="input-field"
                    :value="old('website', $creator->website)"
                    placeholder="https://example.com"
                />
                <x-input-error class="mt-2" :messages="$errors->get('website')" />
            </div>

            <!-- Collection URL Preview -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-navy-900 mb-2">Collection URL Slug</label>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <p class="text-sm text-navy-600 mb-1">Your testimonial collection page:</p>
                    <code class="text-brand-600 font-mono text-sm break-all">{{ $collectionUrl }}</code>
                </div>
            </div>


            <!-- Save Button -->
            <div class="flex items-center justify-end pt-6 border-t border-navy-200">
                <x-primary-button class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Settings
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function copyCollectionUrl() {
            const url = @json($collectionUrl);
            navigator.clipboard.writeText(url).then(() => {
                alert('Collection URL copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy URL:', err);
            });
        }

        function copyEmbedCode() {
            const code = @json($embedCode);

            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(() => {
                    alert('Embed code copied to clipboard!');
                }).catch(err => {
                    console.error('Clipboard API failed, trying fallback:', err);
                    fallbackCopyEmbed(code);
                });
            } else {
                // Fallback for HTTP or older browsers
                fallbackCopyEmbed(code);
            }
        }

        function fallbackCopyEmbed(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    alert('Embed code copied to clipboard!');
                } else {
                    alert('Failed to copy. Please copy manually.');
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                alert('Failed to copy. Please copy manually.');
            }

            document.body.removeChild(textArea);
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
