<x-guest-layout>
    <h2 class="text-xl font-bold text-navy-900 mb-1">Verify your email</h2>
    <p class="text-sm text-navy-500 mb-6">Click the verification link we sent to your email address. If you didn't receive it, we'll send another.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-brand-50 text-sm font-medium text-brand-700">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-navy-500 hover:text-navy-700 transition-colors">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
