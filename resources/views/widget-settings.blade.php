<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-navy-900">Widget Settings</h1>
            <p class="mt-2 text-navy-600">Customize the appearance and behavior of your review widget.</p>
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

        <!-- Embed Code Section -->
        <div class="card-elevated p-6 mb-6">
            <h2 class="text-xl font-semibold text-navy-900 mb-4">Embed Code</h2>
            <p class="text-navy-600 mb-4">Copy this code and paste it on your website to display your reviews.</p>

            <div class="bg-navy-900 rounded-xl p-4 mb-4 border border-navy-700 overflow-x-auto">
                <code class="text-brand-400 text-sm font-mono whitespace-pre">{{ $embedCode }}</code>
            </div>

            <button onclick="copyEmbedCode()" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Copy Embed Code
            </button>
        </div>

        <!-- Theme & Colors Form -->
        <form method="POST" action="{{ route('widget.settings.update') }}" class="card-elevated p-6 mb-6">
            @csrf
            @method('PUT')

            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-navy-900">Theme & Colors</h2>
                    @unless($creator->hasPremiumSubscription())
                        <p class="text-sm text-amber-600 mt-1 font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Pro feature - Upgrade to customize
                        </p>
                    @endunless
                </div>
                @unless($creator->hasPremiumSubscription())
                    <a href="{{ route('pricing') }}" class="btn-primary text-sm px-4 py-2 whitespace-nowrap">
                        Upgrade to Pro
                    </a>
                @endunless
            </div>

            <!-- Theme Selection -->
            <div class="mb-6 relative">
                <x-input-label for="theme" value="Theme" class="text-navy-900 font-semibold mb-2" />
                <select id="theme" name="theme" class="input-field" onchange="updateThemeColors()" {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}>
                    @foreach(\App\Models\WidgetSetting::getThemeOptions() as $value => $label)
                        <option value="{{ $value }}" {{ $settings->theme === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @unless($creator->hasPremiumSubscription())
                    <div class="absolute inset-0 bg-navy-50/60 backdrop-blur-[1px] rounded-xl cursor-not-allowed"></div>
                @endunless
            </div>

            <!-- Color Pickers -->
            <div id="custom-colors" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative {{ $settings->theme !== 'custom' ? 'opacity-50 pointer-events-none' : '' }}">
                <!-- Primary Color -->
                <div>
                    <x-input-label for="primary_color" value="Accent Color" class="text-navy-900 font-semibold mb-2" />
                    <div class="flex items-center gap-3">
                        <input
                            type="color"
                            id="primary_color"
                            value="{{ $settings->primary_color }}"
                            class="h-12 w-12 rounded-lg border-2 border-navy-200 cursor-pointer"
                            oninput="updateColorPreview()"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                        <input
                            type="text"
                            name="primary_color"
                            id="primary_color_hex"
                            value="{{ $settings->primary_color }}"
                            class="input-field flex-1 font-mono text-sm"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                    </div>
                </div>

                <!-- Background Color -->
                <div>
                    <x-input-label for="background_color" value="Background Color" class="text-navy-900 font-semibold mb-2" />
                    <div class="flex items-center gap-3">
                        <input
                            type="color"
                            id="background_color"
                            value="{{ $settings->background_color }}"
                            class="h-12 w-12 rounded-lg border-2 border-navy-200 cursor-pointer"
                            oninput="updateColorPreview()"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                        <input
                            type="text"
                            name="background_color"
                            id="background_color_hex"
                            value="{{ $settings->background_color }}"
                            class="input-field flex-1 font-mono text-sm"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                    </div>
                </div>

                <!-- Text Color -->
                <div>
                    <x-input-label for="text_color" value="Text Color" class="text-navy-900 font-semibold mb-2" />
                    <div class="flex items-center gap-3">
                        <input
                            type="color"
                            id="text_color"
                            value="{{ $settings->text_color }}"
                            class="h-12 w-12 rounded-lg border-2 border-navy-200 cursor-pointer"
                            oninput="updateColorPreview()"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                        <input
                            type="text"
                            name="text_color"
                            id="text_color_hex"
                            value="{{ $settings->text_color }}"
                            class="input-field flex-1 font-mono text-sm"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            {{ $creator->hasPremiumSubscription() ? '' : 'disabled' }}
                        />
                    </div>
                </div>
                @unless($creator->hasPremiumSubscription())
                    <div class="absolute inset-0 bg-navy-50/60 backdrop-blur-[1px] rounded-xl cursor-not-allowed"></div>
                @endunless
            </div>

            <!-- Border Radius -->
            <div class="mb-8">
                <x-input-label for="border_radius" value="Border Radius" class="text-navy-900 font-semibold mb-2" />
                <select id="border_radius" name="border_radius" class="input-field">
                    <option value="0" {{ $settings->border_radius === 0 ? 'selected' : '' }}>None (0px)</option>
                    <option value="4" {{ $settings->border_radius === 4 ? 'selected' : '' }}>Small (4px)</option>
                    <option value="8" {{ $settings->border_radius === 8 ? 'selected' : '' }}>Medium (8px)</option>
                    <option value="12" {{ $settings->border_radius === 12 ? 'selected' : '' }}>Large (12px)</option>
                    <option value="16" {{ $settings->border_radius === 16 ? 'selected' : '' }}>Extra Large (16px)</option>
                </select>
            </div>

            <!-- Widget Appearance -->
            <div class="border-t border-navy-200 pt-8 mt-8">
                <h3 class="text-lg font-semibold text-navy-900 mb-6">Widget Appearance</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Layout -->
                    <div>
                        <x-input-label for="layout" value="Layout" class="text-navy-900 font-semibold mb-2" />
                        <select id="layout" name="layout" class="input-field">
                            <optgroup label="Basic Layouts">
                                @foreach(\App\Models\WidgetSetting::getBasicLayouts() as $value)
                                    <option value="{{ $value }}" {{ $settings->layout === $value ? 'selected' : '' }}>
                                        {{ \App\Models\WidgetSetting::getLayoutOptions()[$value] }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Advanced Layouts{{ $creator->hasPremiumSubscription() ? '' : ' (Pro)' }}">
                                @foreach(\App\Models\WidgetSetting::getAdvancedLayouts() as $value)
                                    <option value="{{ $value }}" {{ $settings->layout === $value ? 'selected' : '' }} {{ !$creator->hasPremiumSubscription() ? 'disabled' : '' }}>
                                        {{ \App\Models\WidgetSetting::getLayoutOptions()[$value] }}{{ !$creator->hasPremiumSubscription() ? ' 🔒' : '' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="mt-1 text-sm text-navy-500">
                            @if($settings->layout === 'cards')
                                Vertical stack of review cards with full content
                            @elseif($settings->layout === 'list')
                                Compact list view of reviews
                            @elseif($settings->layout === 'grid')
                                Responsive grid layout
                            @elseif($settings->layout === 'carousel')
                                <span class="text-brand-600 font-medium">Pro:</span> Auto-rotating slider with navigation dots
                            @elseif($settings->layout === 'masonry')
                                <span class="text-brand-600 font-medium">Pro:</span> Pinterest-style varied height grid
                            @elseif($settings->layout === 'wall')
                                <span class="text-brand-600 font-medium">Pro:</span> Compact grid with avatar focus
                            @elseif($settings->layout === 'single')
                                <span class="text-brand-600 font-medium">Pro:</span> One review with smooth crossfade transitions
                            @endif
                        </p>
                        <x-input-error class="mt-2" :messages="$errors->get('layout')" />
                    </div>

                    <!-- Limit -->
                    <div>
                        <x-input-label for="limit" value="Number of Reviews" class="text-navy-900 font-semibold mb-2" />
                        <input
                            type="number"
                            id="limit"
                            name="limit"
                            min="1"
                            max="50"
                            value="{{ $settings->limit }}"
                            class="input-field"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('limit')" />
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <x-input-label for="sort_order" value="Sort Order" class="text-navy-900 font-semibold mb-2" />
                        <select id="sort_order" name="sort_order" class="input-field">
                            @foreach(\App\Models\WidgetSetting::getSortOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $settings->sort_order === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <!-- Minimum Rating -->
                    <div>
                        <x-input-label for="minimum_rating" value="Minimum Rating" class="text-navy-900 font-semibold mb-2" />
                        <select id="minimum_rating" name="minimum_rating" class="input-field">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $settings->minimum_rating === $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'Star' : 'Stars' }}
                                </option>
                            @endfor
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('minimum_rating')" />
                    </div>
                </div>

                <!-- Display Options -->
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-3">Display Options</label>
                    <div class="space-y-3">
                        <label class="flex items-center group cursor-pointer">
                            <input
                                type="checkbox"
                                name="show_ratings"
                                value="1"
                                {{ $settings->show_ratings ? 'checked' : '' }}
                                class="w-4 h-4 text-brand-600 border-navy-300 rounded focus:ring-brand-500"
                            />
                            <span class="ml-3 text-sm text-navy-700 group-hover:text-navy-900">Show star ratings</span>
                        </label>

                        <label class="flex items-center group cursor-pointer">
                            <input
                                type="checkbox"
                                name="show_avatars"
                                value="1"
                                {{ $settings->show_avatars ? 'checked' : '' }}
                                class="w-4 h-4 text-brand-600 border-navy-300 rounded focus:ring-brand-500"
                            />
                            <span class="ml-3 text-sm text-navy-700 group-hover:text-navy-900">Show author avatars</span>
                        </label>

                        <label class="flex items-center group cursor-pointer">
                            <input
                                type="checkbox"
                                name="show_dates"
                                value="1"
                                {{ $settings->show_dates ? 'checked' : '' }}
                                class="w-4 h-4 text-brand-600 border-navy-300 rounded focus:ring-brand-500"
                            />
                            <span class="ml-3 text-sm text-navy-700 group-hover:text-navy-900">Show review dates</span>
                        </label>

                        <label class="flex items-center group {{ $creator->hasPremiumSubscription() ? 'cursor-pointer' : 'cursor-not-allowed' }} relative">
                            <input
                                type="checkbox"
                                name="show_branding"
                                value="1"
                                {{ $settings->show_branding ? 'checked' : '' }}
                                {{ $creator->hasPremiumSubscription() ? '' : 'disabled checked' }}
                                class="w-4 h-4 text-brand-600 border-navy-300 rounded focus:ring-brand-500"
                            />
                            <span class="ml-3 text-sm text-navy-700 {{ $creator->hasPremiumSubscription() ? 'group-hover:text-navy-900' : 'opacity-60' }}">
                                Show "Powered by ReviewBridge" branding
                                @unless($creator->hasPremiumSubscription())
                                    <svg class="w-3 h-3 inline ml-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span class="text-amber-600 font-medium">Pro only</span>
                                @endunless
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-end pt-6 border-t border-navy-200 mt-8">
                <x-primary-button class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Settings
                </x-primary-button>
            </div>
        </form>

        <!-- Preview Section -->
        <div class="card-elevated p-6">
            <h2 class="text-xl font-semibold text-navy-900 mb-4">Live Preview</h2>
            @if($previewReviews->count() > 0)
                <p class="text-navy-600 mb-6">Live preview using your actual widget with current settings.</p>
            @else
                <p class="text-navy-600 mb-6">Add approved reviews to see the preview.</p>
            @endif

            <div class="border-2 border-dashed border-navy-300 rounded-xl p-8 bg-navy-50/30">
                @if($previewReviews->count() > 0)
                    <!-- Actual Widget Embed -->
                    <div id="reviewbridge-widget" data-collection="{{ $creator->collection_url }}"></div>
                    <script src="{{ route('widget.script', $creator->collection_url) }}?v={{ $settings->updated_at->timestamp }}" defer></script>
                @else
                    <div class="text-center text-navy-500 py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <p class="text-sm font-medium">No approved reviews yet</p>
                        <p class="text-xs mt-2">Reviews will appear here once approved</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const lightDefaults = @json(\App\Models\WidgetSetting::getThemeDefaults('light'));
        const darkDefaults = @json(\App\Models\WidgetSetting::getThemeDefaults('dark'));

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

        function updateThemeColors() {
            const theme = document.getElementById('theme').value;
            const customColors = document.getElementById('custom-colors');

            if (theme === 'custom') {
                customColors.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                customColors.classList.add('opacity-50', 'pointer-events-none');

                const defaults = theme === 'dark' ? darkDefaults : lightDefaults;

                document.getElementById('primary_color').value = defaults.primary_color;
                document.getElementById('background_color').value = defaults.background_color;
                document.getElementById('text_color').value = defaults.text_color;
                document.getElementById('primary_color_hex').value = defaults.primary_color;
                document.getElementById('background_color_hex').value = defaults.background_color;
                document.getElementById('text_color_hex').value = defaults.text_color;
            }

            updatePreview();
        }

        function updateColorPreview() {
            const primary = document.getElementById('primary_color').value;
            const bg = document.getElementById('background_color').value;
            const text = document.getElementById('text_color').value;

            document.getElementById('primary_color_hex').value = primary;
            document.getElementById('background_color_hex').value = bg;
            document.getElementById('text_color_hex').value = text;

            updatePreview();
        }

        function updatePreview() {
            const preview = document.getElementById('widget-preview');
            const theme = document.getElementById('theme').value;

            // Update preview background based on theme
            if (theme === 'dark') {
                preview.style.backgroundColor = '#1f2937';
            } else {
                preview.style.backgroundColor = '#f9fafb';
            }
        }
    </script>
</x-app-layout>
