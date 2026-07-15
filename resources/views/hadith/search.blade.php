<x-app-layout>
    <div class="px-4 py-6" x-data="hadithSearch()" x-init="if(query) search()">
        <div class="mb-4">
            <a href="{{ route('hadith.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cari Hadis</h2>
            <p class="text-gray-600 mt-1">Temukan hadis berdasarkan kata kunci</p>
        </div>

        <div class="relative mb-6">
            <input type="text" x-model="query" @keyup.enter="search()" placeholder="Ketik kata kunci (misal: sholat, puasa, wudhu)..." class="w-full pl-10 pr-20 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button @click="search()" class="absolute right-2 top-2 bg-teal-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-teal-700 transition">Cari</button>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Mencari hadis...</p>
        </div>

        <div x-show="!loading && results.length > 0">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500" x-text="`Ditemukan ${results.length} hasil`"></p>
                <span class="text-xs text-gray-400" x-text="`Kata kunci: ${query}`"></span>
            </div>
            <div class="space-y-3">
                <template x-for="hadis in results" :key="hadis.id">
                    <a :href="`/hadith/${hadis.id}`" class="block bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-teal-600" x-text="`Hadis #${hadis.id}`"></span>
                                    <span :class="[
                                        hadis.grade?.includes('Sahih') || hadis.grade?.includes('sahih') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700',
                                        'ml-2 px-2 py-0.5 rounded-full text-xs'
                                    ]" x-text="hadis.grade || ''"></span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed line-clamp-3" x-text="hadis.text?.id || ''"></p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span x-text="hadis.takhrij || 'Sumber tidak diketahui'"></span>
                            </div>
                            <span class="text-xs text-teal-600 font-medium">Baca Selengkapnya</span>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <div x-show="!loading && searched && results.length === 0" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-500 mb-1">Tidak ditemukan hadis yang sesuai</p>
            <p class="text-sm text-gray-400">Coba gunakan kata kunci yang berbeda</p>
        </div>

        <div x-show="!loading && !searched" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-500 mb-2">Cari hadis berdasarkan kata kunci</p>
            <p class="text-sm text-gray-400">Masukkan minimal 3 karakter untuk mulai mencari</p>
            <div class="flex flex-wrap justify-center gap-2 mt-4">
                <button @click="query = 'sholat'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Sholat</button>
                <button @click="query = 'puasa'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Puasa</button>
                <button @click="query = 'wudhu'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Wudhu</button>
                <button @click="query = 'zakat'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Zakat</button>
                <button @click="query = 'doa'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Doa</button>
                <button @click="query = 'ilmu'; search()" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm hover:bg-gray-200">Ilmu</button>
            </div>
        </div>
    </div>

    <script>
        function hadithSearch() {
            return {
                query: '{{ $query }}',
                results: [],
                loading: false,
                searched: false,

                async search() {
                    if (this.query.length < 3) return;

                    this.loading = true;
                    this.searched = true;
                    try {
                        const response = await fetch(`/api/muslim/hadis/search?q=${encodeURIComponent(this.query)}`);
                        const data = await response.json();
                        this.results = data?.hadis || [];
                    } catch (error) {
                        console.error('Error searching:', error);
                        this.results = [];
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
