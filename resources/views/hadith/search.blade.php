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
        </div>

        <div class="relative mb-6">
            <input type="text" x-model="query" @keyup.enter="search()" placeholder="Cari hadis..." class="w-full pl-10 pr-20 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button @click="search()" class="absolute right-2 top-2 bg-teal-600 text-white px-4 py-1.5 rounded-lg text-sm">Cari</button>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Mencari...</p>
        </div>

        <div x-show="!loading && results.length > 0">
            <p class="text-sm text-gray-500 mb-4" x-text="`Ditemukan ${results.length} hasil`"></p>
            <div class="space-y-3">
                <template x-for="hadis in results" :key="hadis.id">
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
            </div>
        </div>

        <div x-show="!loading && searched && results.length === 0" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-500">Tidak ditemukan hadis yang sesuai</p>
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
