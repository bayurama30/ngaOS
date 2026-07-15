<x-app-layout>
    <div class="px-4 py-6" x-data="hadithBook('{{ $bookSlug }}')" x-init="loadHadiths()">
        <div class="mb-4">
            <a href="{{ route('hadith.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 capitalize">{{ str_replace('-', ' ', $bookSlug) }}</h2>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat hadis...</p>
        </div>

        <div x-show="!loading" class="space-y-3">
            <template x-for="hadith in hadiths" :key="hadith.id">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-teal-600">No. <span x-text="hadith.id"></span></span>
                    </div>
                    <p class="text-gray-700 leading-relaxed" x-text="hadith.body || hadith.text"></p>
                </div>
            </template>
        </div>

        <div x-show="!loading && hadiths.length === 0" class="text-center py-8">
            <p class="text-gray-500">Tidak ada hadis ditemukan</p>
        </div>
    </div>

    <script>
        function hadithBook(bookSlug) {
            return {
                hadiths: [],
                loading: true,

                async loadHadiths() {
                    try {
                        const response = await fetch(`https://cdn.jsdelivr.net/gh/fawazahmed0/hadith-api@1/editions/${bookSlug}.json`);
                        const data = await response.json();

                        if (data.hadiths) {
                            this.hadiths = data.hadiths.slice(0, 50);
                        }
                    } catch (error) {
                        console.error('Error loading hadiths:', error);
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
