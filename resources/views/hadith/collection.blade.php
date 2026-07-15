<x-app-layout>
    <div class="px-4 py-6" x-data="hadithCollection('{{ $key }}')" x-init="loadHadiths()">
        <div class="mb-4">
            <a href="{{ route('hadith.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-gradient-to-br from-{{ $mukharrij['color'] }}-600 to-{{ $mukharrij['color'] }}-700 rounded-2xl p-5 mb-6 text-white">
            <div class="flex items-center mb-3">
                <span class="text-3xl mr-3">{{ $mukharrij['icon'] }}</span>
                <div>
                    <h2 class="text-2xl font-bold">{{ $mukharrij['name'] }}</h2>
                    <p class="text-{{ $mukharrij['color'] }}-100 text-sm">{{ $mukharrij['description'] }}</p>
                </div>
            </div>
            @if(isset($mukharrij['scholar']))
                <div class="bg-white/10 rounded-lg p-3 mt-3">
                    <p class="text-sm"><span class="font-medium">Penyusun:</span> {{ $mukharrij['scholar'] }}</p>
                    @if(isset($mukharrij['death']))
                        <p class="text-sm"><span class="font-medium">Wafat:</span> {{ $mukharrij['death'] }}</p>
                    @endif
                </div>
            @endif
            @if(isset($mukharrij['scholars']))
                <div class="bg-white/10 rounded-lg p-3 mt-3">
                    <p class="text-sm"><span class="font-medium">Penyusun:</span> {{ $mukharrij['scholars'] }}</p>
                </div>
            @endif
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat hadis...</p>
        </div>

        <div x-show="!loading && hadiths.length === 0" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-gray-500">Belum ada hadis ditemukan</p>
        </div>

        <div x-show="!loading && hadiths.length > 0" class="space-y-3">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-gray-500" x-text="`Menampilkan ${hadiths.length} dari ${paging.total_data} hadis`"></p>
            </div>

            <template x-for="hadis in hadiths" :key="hadis.id">
                <a :href="`/hadith/${hadis.id}`" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-teal-600" x-text="`Hadis #${hadis.id}`"></span>
                        <span :class="[
                            hadis.grade?.includes('Sahih') || hadis.grade?.includes('sahih') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700',
                            'px-2 py-0.5 rounded-full text-xs'
                        ]" x-text="hadis.grade || ''"></span>
                    </div>
                    <p class="text-gray-700 text-sm line-clamp-3" x-text="hadis.text?.id || ''"></p>
                    <div class="flex items-center mt-2 text-xs text-gray-400">
                        <span x-text="hadis.takhrij || ''"></span>
                    </div>
                </a>
            </template>

            <div class="flex items-center justify-center space-x-2 mt-6">
                <button @click="prevPage()" :disabled="!paging.has_prev" class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700 disabled:opacity-50 hover:bg-gray-200">
                    Sebelumnya
                </button>
                <span class="px-4 py-2 text-gray-700" x-text="`Halaman ${paging.current} dari ${paging.total_pages}`"></span>
                <button @click="nextPage()" :disabled="!paging.has_next" class="px-4 py-2 bg-teal-600 rounded-lg text-white disabled:opacity-50 hover:bg-teal-700">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    <script>
        function hadithCollection(key) {
            return {
                hadiths: [],
                paging: {},
                loading: true,
                currentPage: 1,

                async loadHadiths() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/api/muslim/hadis/mukharrij/${key}?page=${this.currentPage}&limit=10`);
                        const data = await response.json();
                        this.hadiths = data.hadis || [];
                        this.paging = data.paging || {};
                    } catch (error) {
                        console.error('Error loading hadiths:', error);
                    }
                    this.loading = false;
                },

                nextPage() {
                    if (this.paging.has_next) {
                        this.currentPage++;
                        this.loadHadiths();
                        window.scrollTo(0, 0);
                    }
                },

                prevPage() {
                    if (this.paging.has_prev) {
                        this.currentPage--;
                        this.loadHadiths();
                        window.scrollTo(0, 0);
                    }
                }
            };
        }
    </script>
</x-app-layout>
