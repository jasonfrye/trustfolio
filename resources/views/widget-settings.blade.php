<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Widget Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Embed Code Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Embed Code') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('Copy this code and paste it on your website to display your testimonials.') }}</p>
                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm"><code>{{ e($embedCode) }}</code></pre>
                    </div>
                    <button onclick="copyEmbedCode()" class="mt-3 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                        {{ __('Copy to Clipboard') }}
                    </button>
                </div>
            </div>

            <!-- Widget Theme & Colors -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Theme & Colors') }}</h3>

                    <form action="{{ route('widget.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Theme Selection -->
                        <div class="mb-6">
                            <x-input-label for="theme" :value="__('Theme')" />
                            <select name="theme" id="theme" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" onchange="updateThemeColors()">
                                @foreach(\App\Models\WidgetSetting::getThemeOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ $settings->theme === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Color Pickers -->
                        <div id="custom-colors" class="grid grid-cols-1 md:grid-cols-3 gap-6 {{ $settings->theme !== 'custom' ? 'opacity-50 pointer-events-none' : '' }}">
                            <div>
                                <x-input-label for="primary_color" :value="__('Accent Color')" />
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="color" name="primary_color" id="primary_color" value="{{ $settings->primary_color }}" class="h-10 w-10 rounded border cursor-pointer" oninput="updateColorPreview()">
                                    <x-text-input name="primary_color_hex" :value="$settings->primary_color" class="flex-1 font-mono text-sm" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="background_color" :value="__('Background Color')" />
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="color" name="background_color" id="background_color" value="{{ $settings->background_color }}" class="h-10 w-10 rounded border cursor-pointer" oninput="updateColorPreview()">
                                    <x-text-input name="background_color_hex" :value="$settings->background_color" class="flex-1 font-mono text-sm" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="text_color" :value="__('Text Color')" />
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="color" name="text_color" id="text_color" value="{{ $settings->text_color }}" class="h-10 w-10 rounded border cursor-pointer" oninput="updateColorPreview()">
                                    <x-text-input name="text_color_hex" :value="$settings->text_color" class="flex-1 font-mono text-sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Border Radius -->
                        <div class="mt-6">
                            <x-input-label for="border_radius" :value="__('Border Radius')" />
                            <select name="border_radius" id="border_radius" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                                <option value="0" {{ $settings->border_radius === '0' ? 'selected' : '' }}>{{ __('None (0px)') }}</option>
                                <option value="4" {{ $settings->border_radius === '4' ? 'selected' : '' }}>{{ __('Small (4px)') }}</option>
                                <option value="8" {{ $settings->border_radius === '8' ? 'selected' : '' }}>{{ __('Medium (8px)') }}</option>
                                <option value="12" {{ $settings->border_radius === '12' ? 'selected' : '' }}>{{ __('Large (12px)') }}</option>
                                <option value="16" {{ $settings->border_radius === '16' ? 'selected' : '' }}>{{ __('Extra Large (16px)') }}</option>
                            </select>
                        </div>

                        <!-- Widget Appearance Settings -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Widget Appearance') }}</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Layout -->
                                <div>
                                    <x-input-label for="layout" :value="__('Layout')" />
                                    <select name="layout" id="layout" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                                        @foreach(\App\Models\WidgetSetting::getLayoutOptions() as $value => $label)
                                            <option value="{{ $value }}" {{ $settings->layout === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('layout')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Limit -->
                                <div>
                                    <x-input-label for="limit" :value="__('Number of Testimonials')" />
                                    <x-text-input id="limit" type="number" name="limit" :value="$settings->limit" min="1" max="50" class="mt-1 block w-full" />
                                    @error('limit')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <x-input-label for="sort_order" :value="__('Sort Order')" />
                                    <select name="sort_order" id="sort_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                                        @foreach(\App\Models\WidgetSetting::getSortOptions() as $value => $label)
                                            <option value="{{ $value }}" {{ $settings->sort_order === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('sort_order')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Minimum Rating -->
                                <div>
                                    <x-input-label for="minimum_rating" :value="__('Minimum Rating')" />
                                    <select name="minimum_rating" id="minimum_rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ $settings->minimum_rating === $i ? 'selected' : '' }}>{{ $i }} {{ __('star(s)') }}</option>
                                        @endfor
                                    </select>
                                    @error('minimum_rating')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Toggle Options -->
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('Display Options') }}</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_ratings" value="1" {{ $settings->show_ratings ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Show ratings') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_avatars" value="1" {{ $settings->show_avatars ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Show author avatars') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_dates" value="1" {{ $settings->show_dates ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Show dates') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_branding" value="1" {{ $settings->show_branding ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Show "Powered by TrustFolio"') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Preview') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('Your widget will look something like this:') }}</p>
                    <div id="widget-preview" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center" style="background-color: {{ $settings->theme === 'dark' ? '#1f2937' : '#f9fafb' }}">
                        <div class="trustfolio-widget trustfolio-{{ $settings->theme }} trustfolio-{{ $settings->layout }}" 
                             style="--tf-primary: {{ $settings->primary_color }}; --tf-bg: {{ $settings->background_color }}; --tf-text: {{ $settings->text_color }}; --tf-radius: {{ $settings->border_radius }}px;">
                            <div class="trustfolio-card">
                                <div class="trustfolio-rating">★★★★☆</div>
                                <blockquote class="trustfolio-content">"This is how your testimonials will look. Great product and amazing support!"</blockquote>
                                <div class="trustfolio-author">
                                    <div class="trustfolio-meta">
                                        <strong>Jane Doe</strong>
                                        <span class="trustfolio-title">CEO, Example Corp</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyEmbedCode() {
            const code = {{ Js::from($embedCode) }};
            navigator.clipboard.writeText(code).then(function() {
                alert('Embed code copied to clipboard!');
            }, function() {
                alert('Failed to copy. Please select and copy manually.');
            });
        }

        function updateThemeColors() {
            const theme = document.getElementById('theme').value;
            const customColors = document.getElementById('custom-colors');
            
            if (theme === 'custom') {
                customColors.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                customColors.classList.add('opacity-50', 'pointer-events-none');
                const defaults = @json(\App\Models\WidgetSetting::getThemeDefaults('light'));
                if (theme === 'dark') {
                    defaults = @json(\App\Models\WidgetSetting::getThemeDefaults('dark'));
                }
                document.getElementById('primary_color').value = defaults.primary_color;
                document.getElementById('background_color').value = defaults.background_color;
                document.getElementById('text_color').value = defaults.text_color;
                document.querySelector('input[name="primary_color_hex"]').value = defaults.primary_color;
                document.querySelector('input[name="background_color_hex"]').value = defaults.background_color;
                document.querySelector('input[name="text_color_hex"]').value = defaults.text_color;
            }
            updatePreview();
        }

        function updateColorPreview() {
            const primary = document.getElementById('primary_color').value;
            const bg = document.getElementById('background_color').value;
            const text = document.getElementById('text_color').value;
            
            document.querySelector('input[name="primary_color_hex"]').value = primary;
            document.querySelector('input[name="background_color_hex"]').value = bg;
            document.querySelector('input[name="text_color_hex"]').value = text;
            
            updatePreview();
        }

        function updatePreview() {
            const preview = document.getElementById('widget-preview');
            const theme = document.getElementById('theme').value;
            const primary = document.getElementById('primary_color').value;
            const bg = document.getElementById('background_color').value;
            const text = document.getElementById('text_color').value;
            const radius = document.getElementById('border_radius').value;
            
            preview.style.backgroundColor = theme === 'dark' ? '#1f2937' : '#f9fafb';
        }
    </script>
</x-app-layout>
