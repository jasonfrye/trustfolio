<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-navy-900">Testimonial Requests</h1>
                <p class="mt-2 text-navy-600">Proactively request feedback from your customers via email</p>
            </div>
            <a href="{{ route('testimonial-requests.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Send New Request
            </a>
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

        <!-- Requests Table -->
        @if($requests->count() > 0)
        <div class="card-elevated overflow-hidden">
            <div class="px-6 py-5 border-b border-navy-100">
                <h2 class="text-lg font-semibold text-navy-900">Request History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-navy-200">
                    <thead class="bg-navy-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-navy-500 uppercase tracking-wider">Recipient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-navy-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-navy-500 uppercase tracking-wider">Sent Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-navy-500 uppercase tracking-wider">Responded</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-navy-200">
                        @foreach($requests as $request)
                        <tr class="hover:bg-navy-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        @if($request->recipient_name)
                                            <div class="text-sm font-medium text-navy-900">{{ $request->recipient_name }}</div>
                                        @endif
                                        <div class="text-sm text-navy-500">{{ $request->recipient_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->responded_at)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-brand-100 text-brand-800">
                                        Responded
                                    </span>
                                @elseif($request->sent_at)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Sent
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-navy-100 text-navy-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-500">
                                {{ $request->sent_at ? $request->sent_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($request->responded_at)
                                    <span class="text-brand-600">{{ $request->responded_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-navy-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-navy-200">
                {{ $requests->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="card-elevated p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-navy-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-navy-900 mb-2">No requests sent yet</h3>
            <p class="text-navy-500 mb-6">Start requesting testimonials from your customers via email</p>
            <a href="{{ route('testimonial-requests.create') }}" class="btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Send Your First Request
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
