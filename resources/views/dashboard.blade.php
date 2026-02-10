<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Welcome back, {{ $creator->display_name }}</h1>
                <p class="mt-1 text-navy-500 text-sm">Here's what's happening with your testimonials</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('testimonial-requests.create') }}" class="btn-primary text-sm px-4 py-2">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Request Testimonial
                </a>
                <a href="{{ route('collection.show', $creator->collection_url) }}" target="_blank" class="btn-ghost text-sm px-4 py-2">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    Collection Page
                </a>
                <a href="{{ route('creator.settings') }}" class="btn-ghost text-sm px-4 py-2">Settings</a>
            </div>
        </div>

        <!-- Review Limit Alert (free plan users approaching limit) -->
        <x-review-limit-alert :creator="$creator" />

        <!-- Collection URL Banner -->
        <div class="card-elevated p-5 mb-8 flex flex-col sm:flex-row sm:items-center gap-4 bg-gradient-to-r from-brand-50 to-white">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-brand-700 uppercase tracking-wider mb-1">Your Testimonial Collection Link</p>
                <code class="text-sm text-navy-800 font-medium break-all">{{ route('collection.show', $creator->collection_url) }}</code>
            </div>
            <button
                onclick="copyCollectionLink()"
                class="shrink-0 btn-primary text-sm px-4 py-2"
                id="copy-link-btn"
            >
                Copy Link
            </button>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="stat-card">
                <div class="text-3xl font-bold text-navy-900">{{ $stats['total'] }}</div>
                <div class="text-sm text-navy-500 mt-1">Total Testimonials</div>
            </div>
            <div class="stat-card border-l-4 border-l-amber-400">
                <div class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                <div class="text-sm text-navy-500 mt-1">Pending</div>
            </div>
            <div class="stat-card border-l-4 border-l-brand-500">
                <div class="text-3xl font-bold text-brand-600">{{ $stats['approved'] }}</div>
                <div class="text-sm text-navy-500 mt-1">Approved</div>
            </div>
            <div class="stat-card border-l-4 border-l-red-400">
                <div class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-sm text-navy-500 mt-1">Rejected</div>
            </div>
            <div class="stat-card border-l-4 border-l-navy-300 col-span-2 lg:col-span-1">
                <div class="text-3xl font-bold text-navy-600">{{ $stats['private_feedback'] }}</div>
                <div class="text-sm text-navy-500 mt-1">Private Feedback</div>
            </div>
        </div>

        <!-- Reviews Management -->
        @if($reviews->count() > 0)
        <div class="card-elevated overflow-hidden">
            <!-- Filter Tabs -->
            <div class="border-b border-navy-100 px-6 pt-5 pb-0">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-navy-900">Testimonials</h2>
                </div>
                <div class="flex gap-1 -mb-px overflow-x-auto">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ !request('status') ? 'border-navy-900 text-navy-900' : 'border-transparent text-navy-500 hover:text-navy-700 hover:border-navy-300' }}">
                        All <span class="ml-1 text-xs bg-navy-100 text-navy-600 px-1.5 py-0.5 rounded-full">{{ $stats['total'] }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}?status=pending"
                       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'pending' ? 'border-amber-500 text-amber-700' : 'border-transparent text-navy-500 hover:text-navy-700 hover:border-navy-300' }}">
                        Pending <span class="ml-1 text-xs bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}?status=approved"
                       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'approved' ? 'border-brand-500 text-brand-700' : 'border-transparent text-navy-500 hover:text-navy-700 hover:border-navy-300' }}">
                        Approved <span class="ml-1 text-xs bg-brand-50 text-brand-600 px-1.5 py-0.5 rounded-full">{{ $stats['approved'] }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}?status=rejected"
                       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'rejected' ? 'border-red-500 text-red-700' : 'border-transparent text-navy-500 hover:text-navy-700 hover:border-navy-300' }}">
                        Rejected <span class="ml-1 text-xs bg-red-50 text-red-600 px-1.5 py-0.5 rounded-full">{{ $stats['rejected'] }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}?status=private_feedback"
                       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'private_feedback' ? 'border-navy-500 text-navy-700' : 'border-transparent text-navy-500 hover:text-navy-700 hover:border-navy-300' }}">
                        Private <span class="ml-1 text-xs bg-navy-50 text-navy-600 px-1.5 py-0.5 rounded-full">{{ $stats['private_feedback'] }}</span>
                    </a>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="divide-y divide-navy-100">
                @foreach($reviews as $review)
                <div class="px-6 py-5 hover:bg-navy-50/50 transition-colors duration-150">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <!-- Author Avatar -->
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-200 to-navy-300 flex items-center justify-center text-navy-700 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($review->author_name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-navy-900 text-sm">{{ $review->author_name }}</span>
                                    @if($review->author_title)
                                        <span class="text-navy-400 text-sm ml-1">{{ $review->author_title }}</span>
                                    @endif
                                </div>

                                @if($review->rating)
                                <div class="flex items-center gap-0.5 ml-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-navy-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @endif

                                @if($review->status === 'private_feedback')
                                    <span class="badge-private">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Private
                                    </span>
                                @else
                                    <span class="{{ $review->status === 'pending' ? 'badge-pending' : ($review->status === 'approved' ? 'badge-approved' : 'badge-rejected') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-navy-700 text-sm leading-relaxed">{{ $review->content }}</p>

                            @if($review->source)
                                <p class="text-navy-400 text-xs mt-2">Source: {{ $review->source }}</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 shrink-0">
                            @if($review->status === 'private_feedback')
                                <form action="{{ route('testimonials.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this feedback?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @else
                                @if($review->status === 'pending')
                                    <form action="{{ route('testimonials.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('testimonials.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Reject">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                @elseif($review->status === 'rejected')
                                    <form action="{{ route('testimonials.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Approve">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('testimonials.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Undo approval">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('testimonials.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-navy-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="card-elevated p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-navy-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-navy-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-navy-900 mb-1">No testimonials yet</h3>
            <p class="text-navy-500 text-sm mb-6">Share your collection link to start receiving testimonials</p>
            <a href="{{ route('collection.show', $creator->collection_url) }}" target="_blank" class="btn-primary text-sm">
                View Collection Page
            </a>
        </div>
        @endif
    </div>

    <script>
        function copyCollectionLink() {
            const url = @json(route('collection.show', $creator->collection_url));
            const btn = document.getElementById('copy-link-btn');
            const originalText = btn.textContent;

            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Clipboard API failed, trying fallback:', err);
                    fallbackCopy(url, btn, originalText);
                });
            } else {
                // Fallback for HTTP or older browsers
                fallbackCopy(url, btn, originalText);
            }
        }

        function fallbackCopy(text, btn, originalText) {
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
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                    }, 2000);
                } else {
                    alert('Failed to copy. Please copy manually: ' + text);
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                alert('Failed to copy. Please copy manually: ' + text);
            }

            document.body.removeChild(textArea);
        }
    </script>
</x-app-layout>
