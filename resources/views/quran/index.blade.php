<x-app-layout>
    <div class="px-4 py-6" x-data="quranIndex()" x-init="loadSurahs()">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Al-Quran</h2>
            <p class="text-gray-600 mt-1">114 Surat</p>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <a href="{{ route('quran.index') }}" class="flex items-center p-3 bg-teal-600 text-white rounded-xl shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-sm font-medium">Daftar Surat</span>
            </a>
            <a href="{{ route('quran.marked') }}" class="flex items-center p-3 bg-white text-gray-700 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <svg class="w-5 h-5 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                <span class="text-sm font-medium">Ayat Ditandai</span>
            </a>
        </div>

        <div class="relative mb-6">
            <input type="text" x-model="search" placeholder="Cari surat..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat surat...</p>
        </div>

        <div x-show="!loading" class="space-y-2">
            <template x-for="surah in filteredSurahs" :key="surah.number">
                <a :href="`/quran/${surah.number}`" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-teal-700 font-bold text-sm" x-text="surah.number"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800" x-text="surah.name_latin"></h3>
                                <p class="text-xs text-gray-500" x-text="surah.translation"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg text-gray-700" style="font-family: 'LPMQ IsepMisbah', serif" x-text="surah.name"></p>
                                <p class="text-xs text-gray-500" x-text="`${surah.number_of_ayahs} Ayat`"></p>
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
                        const response = await fetch('/api/muslim/quran/list');
                        const data = await response.json();
                        this.surahs = Array.isArray(data) ? data : [];
                    } catch (error) {
                        console.error('Error loading surahs:', error);
                    }
                    this.loading = false;
                },

                get filteredSurahs() {
                    if (!this.search) return this.surahs;
                    const query = this.search.toLowerCase();
                    return this.surahs.filter(s =>
                        (s.name_latin || '').toLowerCase().includes(query) ||
                        (s.name || '').includes(query) ||
                        (s.number || '').toString().includes(query)
                    );
                }
            };
        }
    </script>
</x-app-layout>
