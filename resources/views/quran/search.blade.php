<x-app-layout>
    <div class="px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cari di Al-Quran</h2>
            <p class="text-gray-600 mt-1">Hasil pencarian: "{{ $query }}"</p>
        </div>

        <form action="{{ route('quran.search') }}" method="GET" class="mb-6">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari ayat..." class="w-full pl-10 pr-20 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="submit" class="absolute right-2 top-2 bg-teal-600 text-white px-4 py-1.5 rounded-lg text-sm">Cari</button>
            </div>
        </form>

        <div class="space-y-3">
            @forelse($results as $match)
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <p class="font-arabic text-xl text-right leading-loose text-gray-800 mb-2">{{ $match['text'] ?? '' }}</p>
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
                    <p class="text-gray-500">Tidak ditemukan hasil untuk pencarian ini</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
