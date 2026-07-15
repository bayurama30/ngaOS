<x-app-layout>
    <div class="px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cari Hadis</h2>
        </div>

        <form action="{{ route('hadith.search') }}" method="GET" class="mb-6">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari hadis..." class="w-full pl-10 pr-20 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                <button type="submit" class="absolute right-2 top-2 bg-teal-600 text-white px-4 py-1.5 rounded-lg text-sm">Cari</button>
            </div>
        </form>

        <div class="space-y-3">
            @forelse($results as $result)
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <span class="text-sm font-medium text-blue-600">{{ $result['book'] }}</span>
                    <p class="text-gray-700 mt-2">{{ $result['hadith']['body'] ?? $result['hadith']['text'] ?? '' }}</p>
                </div>
            @empty
                @if(strlen($query) >= 3)
                    <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
                        <p class="text-gray-500">Tidak ditemukan hasil</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</x-app-layout>
