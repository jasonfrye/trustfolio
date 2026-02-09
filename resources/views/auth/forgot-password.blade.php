<x-guest-layout>
    <h2 class="text-xl font-bold text-navy-900 mb-1">Reset your password</h2>
    <p class="text-sm text-navy-500 mb-6">Enter your email and we'll send you a reset link.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-navy-100 text-center">
        <a href="{{ route('login') }}" class="text-sm text-navy-500 hover:text-brand-600 transition-colors">Back to sign in</a>
    </div>
</x-guest-layout>
