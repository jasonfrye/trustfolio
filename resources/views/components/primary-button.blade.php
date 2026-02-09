<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-700 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-brand-700 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:from-brand-800 active:to-brand-900 transition-all duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>
