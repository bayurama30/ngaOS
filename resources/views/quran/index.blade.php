<x-app-layout>
    <div class="px-4 py-6" x-data="quranIndex()" x-init="loadSurahs()">
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Al-Quran</h2>
            <p class="text-gray-600 mt-1">114 Surat</p>
        </div>

        {{-- Search --}}
        <div class="relative mb-6">
            <input type="text" x-model="search" placeholder="Cari surat..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat surat...</p>
        </div>

        {{-- Surah List --}}
        <div x-show="!loading" class="space-y-2">
            <template x-for="surah in filteredSurahs" :key="surah.number">
                <a :href="`/quran/${surah.number}`" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-teal-700 font-bold text-sm" x-text="surah.number"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800" x-text="surah.englishName"></h3>
                                <p class="text-xs text-gray-500" x-text="surah.englishNameTranslation"></p>
                            </div>
                            <div class="text-right">
                                <p class="font-arabic text-lg text-gray-700" x-text="surah.name"></p>
                                <p class="text-xs text-gray-500" x-text="`${surah.numberOfAyahs} Ayat`"></p>
                            </div>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>

    <script>
        function quranIndex() {
            return {
                surahs: [],
                search: '',
                loading: true,

                async loadSurahs() {
                    try {
                        const response = await fetch('https://api.alquran.cloud/v1/surah');
                        const data = await response.json();

                        if (data.code === 200) {
                            this.surahs = data.data;
                        }
                    } catch (error) {
                        console.error('Error loading surahs:', error);
                    }
                    this.loading = false;
                },

                get filteredSurahs() {
                    if (!this.search) return this.surahs;
                    const query = this.search.toLowerCase();
                    return this.surahs.filter(s =>
                        s.englishName.toLowerCase().includes(query) ||
                        s.name.includes(query) ||
                        s.number.toString().includes(query)
                    );
                }
            };
        }
    </script>
</x-app-layout>
