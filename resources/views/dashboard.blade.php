<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome & Collection URL -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Welcome, {{ $creator->display_name }}!</h3>
                    <p class="mt-2 text-gray-600">Your testimonial collection URL:</p>
                    <div class="mt-2 flex items-center gap-2">
                        <code class="bg-gray-100 px-3 py-1 rounded text-sm">{{ route('collection.show', $creator->collection_url) }}</code>
                        <a href="{{ route('collection.show', $creator->collection_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            {{ __('View') }} ↗
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('Total Testimonials') }}</div>
                </div>
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('Pending') }}</div>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('Approved') }}</div>
                </div>
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('Rejected') }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Quick Actions') }}</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('collection.show', $creator->collection_url) }}" target="_blank" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            {{ __('View Collection Page') }}
                        </a>
                        <a href="{{ route('creator.settings') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            {{ __('Creator Settings') }}
                        </a>
                        <a href="{{ route('widget.settings') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            {{ __('Widget Settings') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Testimonials Management -->
            @if($testimonials->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Testimonials') }}</h3>
                    
                    <!-- Filter Tabs -->
                    <div class="flex gap-2 mb-4">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1 rounded text-sm {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            All ({{ $stats['total'] }})
                        </a>
                        <a href="{{ route('dashboard') }}?status=pending" class="px-3 py-1 rounded text-sm {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Pending ({{ $stats['pending'] }})
                        </a>
                        <a href="{{ route('dashboard') }}?status=approved" class="px-3 py-1 rounded text-sm {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Approved ({{ $stats['approved'] }})
                        </a>
                        <a href="{{ route('dashboard') }}?status=rejected" class="px-3 py-1 rounded text-sm {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Rejected ({{ $stats['rejected'] }})
                        </a>
                    </div>

                    <!-- Testimonials List -->
                    <div class="space-y-4">
                        @foreach($testimonials as $testimonial)
                        <div class="border rounded-lg p-4 {{ $testimonial->status === 'pending' ? 'border-yellow-300 bg-yellow-50' : ($testimonial->status === 'approved' ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50') }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-800">{{ $testimonial->author_name }}</span>
                                        @if($testimonial->author_title)
                                        <span class="text-gray-500 text-sm">{{ $testimonial->author_title }}</span>
                                        @endif
                                        @if($testimonial->rating)
                                        <span class="text-yellow-500">
                                            @for($i = 0; $i < $testimonial->rating; $i++)★@endfor
                                            @for($i = $testimonial->rating; $i < 5; $i++)☆@endfor
                                        </span>
                                        @endif
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $testimonial->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : ($testimonial->status === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                            {{ ucfirst($testimonial->status) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700">{{ $testimonial->content }}</p>
                                    @if($testimonial->source)
                                    <p class="text-gray-500 text-sm mt-1">Source: {{ $testimonial->source }}</p>
                                    @endif
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-2 ml-4">
                                    @if($testimonial->status === 'pending')
                                    <form action="{{ route('testimonials.approve', $testimonial) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700" title="Approve">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('testimonials.reject', $testimonial) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700" title="Reject">
                                            ✗ Reject
                                        </button>
                                    </form>
                                    @elseif($testimonial->status === 'rejected')
                                    <form action="{{ route('testimonials.approve', $testimonial) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700" title="Re-approve">
                                            ↺ Approve
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('testimonials.reject', $testimonial) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-yellow-600 text-white px-3 py-1 rounded text-sm hover:bg-yellow-700" title="Reject">
                                            ↺ Undo
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700" title="Delete">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    <p class="text-lg mb-2">No testimonials yet</p>
                    <p class="text-sm">Share your collection URL to start receiving testimonials!</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
