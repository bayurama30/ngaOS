<x-app-layout>
    <div class="px-4 py-6" x-data="hadithIndex()" x-init="loadHadiths()">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ensiklopedia Hadis</h2>
            <p class="text-gray-600 mt-1">Koleksi Hadis Nabi Muhammad SAW</p>
        </div>

        <div class="relative mb-6">
            <input type="text" x-model="searchQuery" @keyup.enter="searchHadith()" placeholder="Cari hadis..." class="w-full pl-10 pr-20 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button @click="searchHadith()" class="absolute right-2 top-2 bg-teal-600 text-white px-4 py-1.5 rounded-lg text-sm">Cari</button>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat hadis...</p>
        </div>

        <div x-show="!loading && searchResults.length > 0" class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">Hasil Pencarian</h3>
            <div class="space-y-3">
                <template x-for="hadis in searchResults" :key="hadis.id">
                    <a :href="`/hadith/${hadis.id}`" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <p class="text-gray-700 text-sm line-clamp-3" x-text="hadis.text"></p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <span x-text="`Hadis #${hadis.id}`"></span>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <div x-show="!loading && searchResults.length === 0">
            <h3 class="font-semibold text-gray-800 mb-3">Hadis Acak</h3>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6" x-show="randomHadis">
                <p class="text-gray-700 leading-relaxed" x-text="randomHadis?.text?.id || ''"></p>
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center text-xs text-gray-500">
                        <span x-text="randomHadis?.takhrij || ''"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span :class="[randomHadis?.grade === 'Shahih' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700', 'px-2 py-0.5 rounded-full text-xs']" x-text="randomHadis?.grade || ''"></span>
                        <button @click="loadRandomHadis()" class="text-teal-600 hover:text-teal-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <h3 class="font-semibold text-gray-800 mb-3">Jelajahi Hadis</h3>
            <div class="space-y-3">
                <template x-for="hadis in hadiths" :key="hadis.id">
                    <a :href="`/hadith/${hadis.id}`" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-teal-600">Hadis #<span x-text="hadis.id"></span></span>
                            <span :class="[hadis.grade === 'Shahih' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700', 'px-2 py-0.5 rounded-full text-xs']" x-text="hadis.grade || ''"></span>
                        </div>
                        <p class="text-gray-700 text-sm line-clamp-2" x-text="hadis.text?.id || ''"></p>
                    </a>
                </template>
            </div>

            <div class="flex justify-center mt-6 space-x-2">
                <button @click="prevPage()" :disabled="currentPage <= 1" class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700 disabled:opacity-50">Sebelumnya</button>
                <span class="px-4 py-2 text-gray-700" x-text="`Halaman ${currentPage}`"></span>
                <button @click="nextPage()" class="px-4 py-2 bg-teal-600 rounded-lg text-white">Selanjutnya</button>
            </div>
        </div>
    </div>

    <script>
        function hadithIndex() {
            return {
                hadiths: [],
                randomHadis: null,
                searchResults: [],
                searchQuery: '',
                loading: true,
                currentPage: 1,

                async loadHadiths() {
                    this.loading = true;
                    try {
                        const [exploreRes, randomRes] = await Promise.all([
                            fetch(`/api/muslim/hadis/explore?page=${this.currentPage}&limit=10`),
                            fetch('/api/muslim/hadis/random')
                        ]);

                        const exploreData = await exploreRes.json();
                        const randomData = await randomRes.json();

                        this.hadiths = exploreData?.hadis || [];
                        this.randomHadis = randomData;
                    } catch (error) {
                        console.error('Error loading hadiths:', error);
                    }
                    this.loading = false;
                },

                async loadRandomHadis() {
                    try {
                        const response = await fetch('/api/muslim/hadis/random');
                        this.randomHadis = await response.json();
                    } catch (error) {
                        console.error('Error loading random hadis:', error);
                    }
                },

                async searchHadith() {
                    if (this.searchQuery.length < 3) return;

                    this.loading = true;
                    try {
                        const response = await fetch(`/api/muslim/hadis/search?q=${encodeURIComponent(this.searchQuery)}`);
                        const data = await response.json();
                        this.searchResults = data?.hadis || [];
                    } catch (error) {
                        console.error('Error searching hadith:', error);
                        this.searchResults = [];
                    }
                    this.loading = false;
                },

                nextPage() {
                    this.currentPage++;
                    this.loadHadiths();
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.loadHadiths();
                    }
                }
            };
        }
    </script>
</x-app-layout>
