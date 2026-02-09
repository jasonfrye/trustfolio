<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-navy-900">Creator Settings</h1>
            <p class="mt-2 text-navy-600">Manage your review collection page, embed widget, and review funnel settings.</p>
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
            <h2 class="text-xl font-semibold text-navy-900 mb-4">Your Review Collection Link</h2>
            <p class="text-navy-600 mb-4">Share this link to collect reviews from your customers.</p>

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
                    <p class="text-sm text-navy-600 mb-1">Your review collection page:</p>
                    <code class="text-brand-600 font-mono text-sm break-all">{{ $collectionUrl }}</code>
                </div>
            </div>

            <!-- Review Funnel Settings -->
            <div class="border-t border-navy-200 pt-8 mt-8">
                <h3 class="text-lg font-semibold text-navy-900 mb-6">Review Funnel Settings</h3>

                <!-- Review Threshold -->
                <div class="mb-6">
                    <x-input-label for="review_threshold" value="Review Threshold" class="text-navy-900 font-semibold mb-2" />
                    <p class="text-sm text-navy-600 mb-3">Reviews below this rating will be directed to private feedback instead of public platforms.</p>
                    <select id="review_threshold" name="review_threshold" class="input-field">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('review_threshold', $creator->review_threshold ?? 4) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i === 1 ? 'Star' : 'Stars' }} or Higher
                            </option>
                        @endfor
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('review_threshold')" />
                </div>

                <!-- Google Review URL -->
                <div class="mb-6">
                    <x-input-label for="google_review_url" value="Google Review URL" class="text-navy-900 font-semibold mb-2" />
                    <p class="text-sm text-navy-600 mb-3">Your Google Business Profile review link (for high-rated reviews).</p>
                    <x-text-input
                        id="google_review_url"
                        name="google_review_url"
                        type="url"
                        class="input-field"
                        :value="old('google_review_url', $creator->google_review_url)"
                        placeholder="https://g.page/r/..."
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('google_review_url')" />
                </div>

                <!-- Additional Platforms -->
                <div class="mb-6" x-data="{ platforms: {{ Js::from(old('redirect_platforms', $creator->redirect_platforms ?? [])) }} }">
                    <x-input-label value="Additional Review Platforms" class="text-navy-900 font-semibold mb-2" />
                    <p class="text-sm text-navy-600 mb-3">Add other platforms where customers can leave reviews (e.g., Yelp, Facebook, Trustpilot).</p>

                    <template x-for="(platform, index) in platforms" :key="index">
                        <div class="flex gap-3 mb-3">
                            <input
                                type="text"
                                x-model="platform.name"
                                :name="'redirect_platforms[' + index + '][name]'"
                                placeholder="Platform name (e.g., Yelp)"
                                class="input-field flex-1"
                            />
                            <input
                                type="url"
                                x-model="platform.url"
                                :name="'redirect_platforms[' + index + '][url]'"
                                placeholder="Review URL"
                                class="input-field flex-1"
                            />
                            <button
                                type="button"
                                @click="platforms.splice(index, 1)"
                                class="px-4 py-3 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-xl border border-navy-200 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <button
                        type="button"
                        @click="platforms.push({ name: '', url: '' })"
                        class="btn-ghost w-full justify-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Platform
                    </button>
                </div>

                <!-- Prefill Customer Info -->
                <div class="mb-6" x-data="{ prefillEnabled: {{ old('prefill_enabled', $creator->prefill_enabled ?? false) ? 'true' : 'false' }} }">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="prefill_enabled"
                                name="prefill_enabled"
                                type="checkbox"
                                value="1"
                                x-model="prefillEnabled"
                                class="w-4 h-4 text-brand-600 border-navy-300 rounded focus:ring-brand-500"
                            />
                        </div>
                        <div class="ml-3">
                            <label for="prefill_enabled" class="font-semibold text-navy-900">Enable URL Prefill Parameters</label>
                            <p class="text-sm text-navy-600 mt-1">Allow customer name and email to be prefilled via URL parameters.</p>
                            <div x-show="prefillEnabled" x-cloak class="mt-3 p-4 bg-brand-50 rounded-lg border border-brand-200">
                                <p class="text-sm text-brand-900 font-medium mb-1">Example URL:</p>
                                <code class="text-xs text-brand-700 font-mono break-all">{{ $collectionUrl }}?name=John+Doe&email=john@example.com</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Messages -->
                <div class="mb-6">
                    <x-input-label for="review_prompt_text" value="Review Request Message" class="text-navy-900 font-semibold mb-2" />
                    <p class="text-sm text-navy-600 mb-3">Customize the message shown on your review collection page.</p>
                    <textarea
                        id="review_prompt_text"
                        name="review_prompt_text"
                        rows="3"
                        class="input-field"
                        placeholder="We'd love to hear about your experience!"
                    >{{ old('review_prompt_text', $creator->review_prompt_text) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('review_prompt_text')" />
                </div>

                <div class="mb-6">
                    <x-input-label for="private_feedback_text" value="Private Feedback Message" class="text-navy-900 font-semibold mb-2" />
                    <p class="text-sm text-navy-600 mb-3">Message shown to customers when they submit private feedback.</p>
                    <textarea
                        id="private_feedback_text"
                        name="private_feedback_text"
                        rows="3"
                        class="input-field"
                        placeholder="Thank you for your feedback. We'll use it to improve our service."
                    >{{ old('private_feedback_text', $creator->private_feedback_text) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('private_feedback_text')" />
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
            navigator.clipboard.writeText(code).then(() => {
                alert('Embed code copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy embed code:', err);
            });
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
