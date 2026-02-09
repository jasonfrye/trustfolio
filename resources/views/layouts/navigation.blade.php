<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-xl border-b border-navy-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-brand-700 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white group-hover:text-amber-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-navy-900 tracking-tight">ReviewBridge</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-navy-900 text-white' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('creator.settings') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('creator.settings') ? 'bg-navy-900 text-white' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                        Settings
                    </a>
                    <a href="{{ route('widget.settings') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('widget.settings') ? 'bg-navy-900 text-white' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                        Widget
                    </a>
                </div>
            </div>

            <!-- User Menu -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-navy-600 hover:text-navy-900 hover:bg-navy-50 transition-colors duration-150">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-navy-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('creator.settings')">
                            {{ __('Creator Settings') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('widget.settings')">
                            {{ __('Widget Settings') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-navy-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-navy-500 hover:text-navy-700 hover:bg-navy-50 transition-colors">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-navy-100">
        <div class="p-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-navy-900 text-white' : 'text-navy-700 hover:bg-navy-50' }}">Dashboard</a>
            <a href="{{ route('creator.settings') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('creator.settings') ? 'bg-navy-900 text-white' : 'text-navy-700 hover:bg-navy-50' }}">Settings</a>
            <a href="{{ route('widget.settings') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('widget.settings') ? 'bg-navy-900 text-white' : 'text-navy-700 hover:bg-navy-50' }}">Widget</a>
        </div>
        <div class="p-3 border-t border-navy-100">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-medium text-navy-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-navy-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 rounded-lg text-sm text-navy-700 hover:bg-navy-50">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg text-sm text-navy-700 hover:bg-navy-50">Log Out</button>
            </form>
        </div>
    </div>
</nav>
