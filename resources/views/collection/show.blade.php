<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Testimonial for {{ $creator->display_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto py-12 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Leave a Testimonial</h1>
            <p class="mt-2 text-gray-600">Share your experience working with {{ $creator->display_name }}</p>
            @if($creator->website)
                <a href="{{ $creator->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm mt-1 inline-block">
                    {{ parse_url($creator->website, PHP_URL_HOST) }} ↗
                </a>
            @endif
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Testimonial Form -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <form action="{{ route('collection.submit', $creator->collection_url) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Author Name -->
                    <div>
                        <label for="author_name" class="block text-sm font-medium text-gray-700">Your Name *</label>
                        <input type="text" name="author_name" id="author_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                            value="{{ old('author_name') }}">
 @error('author_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Author Email -->
                    <div>
                        <label for="author_email" class="block text-sm font-medium text-gray-700">Email (optional)</label>
                        <input type="email" name="author_email" id="author_email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                            value="{{ old('author_email') }}">
                        @error('author_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Author Title -->
                    <div>
                        <label for="author_title" class="block text-sm font-medium text-gray-700">Title/Company (optional)</label>
                        <input type="text" name="author_title" id="author_title" placeholder="e.g., CEO at Company"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                            value="{{ old('author_title') }}">
                        @error('author_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <div class="flex gap-2" id="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none rating-btn"
                                    data-rating="{{ $i }}"
                                    onclick="selectRating({{ $i }})">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating_value" value="{{ old('rating', 5) }}">
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Your Testimonial *</label>
                        <textarea name="content" id="content" rows="5" required minlength="10" maxlength="2000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                            placeholder="Share your experience...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200">
                        Submit Testimonial
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500">
            Powered by <a href="#" class="text-indigo-600 hover:text-indigo-800">TrustFolio</a>
        </div>
    </div>

    <script>
        function selectRating(rating) {
            document.getElementById('rating_value').value = rating;
            document.querySelectorAll('.rating-btn').forEach((btn, index) => {
                btn.classList.toggle('text-yellow-400', index < rating);
                btn.classList.toggle('text-gray-300', index >= rating);
            });
        }

        // Initialize rating on page load
        document.addEventListener('DOMContentLoaded', function() {
            selectRating({{ old('rating', 5) }});
        });
    </script>
</body>
</html>
