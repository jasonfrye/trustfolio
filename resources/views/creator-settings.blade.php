<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Creator Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Collection URL Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Collection URL') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('Share this URL with your customers to collect testimonials.') }}</p>
                    <div class="bg-gray-100 rounded-lg p-4 overflow-x-auto">
                        <code class="text-indigo-600 text-lg">{{ $collectionUrl }}</code>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button onclick="copyCollectionUrl()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                            {{ __('Copy URL') }}
                        </button>
                        <a href="{{ $collectionUrl }}" target="_blank" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                            {{ __('Open Page') }} ↗
                        </a>
                    </div>
                </div>
            </div>

            <!-- Embed Code Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Embed Widget') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('Copy this code and paste it on your website to display your testimonials.') }}</p>
                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm"><code>{{ e($embedCode) }}</code></pre>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button onclick="copyEmbedCode()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                            {{ __('Copy Embed Code') }}
                        </button>
                        <a href="{{ route('widget.settings') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                            {{ __('Customize Widget') }} →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Profile Settings') }}</h3>

                    <form action="{{ route('creator.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Display Name -->
                        <div class="mb-6">
                            <x-input-label for="display_name" :value="__('Display Name')" />
                            <x-text-input id="display_name" type="text" name="display_name" :value="old('display_name', $creator->display_name)" class="mt-1 block w-full" required />
                            @error('display_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">{{ __('This name appears on your collection page and widget.') }}</p>
                        </div>

                        <!-- Website -->
                        <div class="mb-6">
                            <x-input-label for="website" :value="__('Website (optional)')" />
                            <x-text-input id="website" type="url" name="website" :value="old('website', $creator->website)" class="mt-1 block w-full" placeholder="https://yourwebsite.com" />
                            @error('website')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Collection URL Preview -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('Your collection URL will be:') }}</p>
                            <p class="font-mono text-indigo-600 mt-1">{{ route('collection.show', $creator->collection_url) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Note: Changing your display name will regenerate your collection URL.') }}</p>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyCollectionUrl() {
            const url = {{ Js::from($collectionUrl) }};
            navigator.clipboard.writeText(url).then(function() {
                alert('Collection URL copied to clipboard!');
            }, function() {
                alert('Failed to copy. Please select and copy manually.');
            });
        }

        function copyEmbedCode() {
            const code = {{ Js::from($embedCode) }};
            navigator.clipboard.writeText(code).then(function() {
                alert('Embed code copied to clipboard!');
            }, function() {
                alert('Failed to copy. Please select and copy manually.');
            });
        }
    </script>
</x-app-layout>
