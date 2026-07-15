<x-app-layout>
    <div class="px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ensiklopedia Hadis</h2>
            <p class="text-gray-600 mt-1">Koleksi Hadis Nabi Muhammad SAW</p>
        </div>

        <div class="relative mb-6">
            <input type="text" id="searchInput" placeholder="Cari hadis..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500" onkeyup="if(event.key==='Enter') window.location='/hadith/search?q='+this.value">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="mb-6" x-data="randomHadis()" x-init="loadHadis()">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-800">Hadis Hari Ini</h3>
                <button @click="loadHadis()" class="text-teal-600 hover:text-teal-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div x-show="loading" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
            </div>
            <div x-show="!loading && hadis" class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-5 border border-teal-200">
                <p class="text-gray-700 text-sm leading-relaxed mb-3" x-text="hadis?.text?.id || ''"></p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500" x-text="hadis?.takhrij || ''"></span>
                    <a :href="`/hadith/${hadis?.id}`" class="text-teal-600 text-sm font-medium hover:text-teal-700">Baca Selengkapnya →</a>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4">Koleksi Kitab Hadis</h3>
            <div class="space-y-3">
                @foreach($mukharrijList as $key => $mukharrij)
                    <a href="{{ route('hadith.collection', $key) }}" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-{{ $mukharrij['color'] }}-100 rounded-xl flex items-center justify-center mr-4 text-2xl">
                                {{ $mukharrij['icon'] }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $mukharrij['name'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $mukharrij['description'] }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function randomHadis() {
            return {
                hadis: null,
                loading: true,
                async loadHadis() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/muslim/hadis/random');
                        this.hadis = await response.json();
                    } catch (error) {
                        console.error('Error loading hadis:', error);
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
