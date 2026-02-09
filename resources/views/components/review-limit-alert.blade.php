@props(['creator'])

@php
    $reviewCount = $creator->reviews()->count();
    $limit = $creator->max_reviews;
    $remaining = $limit - $reviewCount;
    $percentage = ($reviewCount / $limit) * 100;

    // Only show for free plan users approaching their limit
    if ($creator->plan !== 'free' || $reviewCount < 7) {
        return;
    }

    // Determine alert style based on proximity to limit
    if ($reviewCount >= $limit) {
        $alertClass = 'bg-gradient-to-r from-red-50 to-red-100/50 border-red-200';
        $iconColor = 'text-red-600';
        $textColor = 'text-red-900';
        $progressColor = 'bg-red-500';
        $buttonClass = 'bg-red-600 hover:bg-red-700 text-white';
    } elseif ($reviewCount >= 9) {
        $alertClass = 'bg-gradient-to-r from-amber-50 to-amber-100/50 border-amber-200';
        $iconColor = 'text-amber-600';
        $textColor = 'text-amber-900';
        $progressColor = 'bg-amber-500';
        $buttonClass = 'bg-amber-600 hover:bg-amber-700 text-white';
    } else {
        $alertClass = 'bg-gradient-to-r from-blue-50 to-blue-100/50 border-blue-200';
        $iconColor = 'text-blue-600';
        $textColor = 'text-blue-900';
        $progressColor = 'bg-blue-500';
        $buttonClass = 'bg-blue-600 hover:bg-blue-700 text-white';
    }
@endphp

<div class="card-elevated p-5 mb-6 border-2 {{ $alertClass }}">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <!-- Icon -->
        <div class="shrink-0">
            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center {{ $iconColor }}">
                @if($reviewCount >= $limit)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            @if($reviewCount >= $limit)
                <h3 class="font-semibold {{ $textColor }} text-sm mb-1">Review Limit Reached</h3>
                <p class="text-sm {{ $textColor }}/80">
                    You've reached your {{ $limit }} review limit on the Starter plan.
                    <strong>Upgrade to Pro</strong> to continue collecting unlimited reviews and unlock custom branding.
                </p>
            @elseif($reviewCount >= 9)
                <h3 class="font-semibold {{ $textColor }} text-sm mb-1">{{ $remaining }} Review{{ $remaining === 1 ? '' : 's' }} Remaining</h3>
                <p class="text-sm {{ $textColor }}/80">
                    You're almost at your limit! Upgrade to Pro before you reach {{ $limit }} reviews to avoid any interruption.
                </p>
            @else
                <h3 class="font-semibold {{ $textColor }} text-sm mb-1">{{ $remaining }} Reviews Remaining</h3>
                <p class="text-sm {{ $textColor }}/80">
                    You have {{ $remaining }} review{{ $remaining === 1 ? '' : 's' }} left on your Starter plan. Upgrade to Pro for unlimited reviews.
                </p>
            @endif

            <!-- Progress Bar -->
            <div class="mt-3 w-full bg-white/60 rounded-full h-2 overflow-hidden shadow-inner">
                <div class="{{ $progressColor }} h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
            </div>
            <p class="text-xs {{ $textColor }}/70 mt-1">{{ $reviewCount }} of {{ $limit }} reviews used</p>
        </div>

        <!-- CTA Button -->
        <div class="shrink-0 w-full sm:w-auto">
            <a href="{{ route('pricing') }}?utm_source=dashboard&utm_medium=alert&utm_campaign=limit_{{ $reviewCount }}"
               class="{{ $buttonClass }} px-5 py-2.5 rounded-lg font-semibold text-sm shadow-sm hover:shadow transition-all duration-200 text-center block whitespace-nowrap"
               onclick="trackUpgradeClick(event, 'dashboard_alert', {{ $reviewCount }})">
                @if($reviewCount >= $limit)
                    Upgrade Now
                @else
                    View Pro Plan
                @endif
            </a>
        </div>
    </div>
</div>
