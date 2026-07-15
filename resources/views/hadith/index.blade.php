<x-app-layout>
    <div class="px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kitab Hadis</h2>
            <p class="text-gray-600 mt-1">6 Kitab Hadis Utama</p>
        </div>

        <div class="space-y-3">
            @foreach($books as $slug => $book)
                <a href="{{ route('hadith.book', $slug) }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $book['name'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $book['author'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($book['total_hadith']) }} Hadis</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
