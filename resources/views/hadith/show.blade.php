<x-app-layout>
    <div class="px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Hadis</h2>
            <p class="text-gray-600 mt-1">{{ str_replace('-', ' ', $bookSlug) }} - No. {{ $hadithNumber }}</p>
        </div>

        @if($hadith)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <p class="text-gray-700 leading-relaxed text-lg">{{ $hadith['body'] ?? $hadith['text'] ?? '' }}</p>
            </div>
        @else
            <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
                <p class="text-gray-500">Hadis tidak ditemukan</p>
            </div>
        @endif
    </div>
</x-app-layout>
