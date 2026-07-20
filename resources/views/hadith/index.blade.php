<x-app-layout>
    @php
        $colorMap = [
            'emerald' => '#d1fae5',
            'blue' => '#dbeafe',
            'purple' => '#ede9fe',
            'amber' => '#fef3c7',
            'cyan' => '#cffafe',
            'red' => '#fecaca',
        ];
    @endphp

    <div class="max-w-[620px] mx-auto px-4 py-6">
        <div class="mb-6 animate-fade-in">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Ensiklopedia Hadis</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Koleksi Hadis Nabi Muhammad SAW</p>
        </div>

        <div class="relative mb-6">
            <input type="text" id="searchInput" placeholder="Cari hadis..." class="input pl-10" onkeyup="if(event.key==='Enter') window.location='/hadith/search?q='+this.value">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="mb-6" x-data="randomHadis()" x-init="loadHadis()">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-900 dark:text-white">Hadis Hari Ini</h3>
                <button @click="loadHadis()" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition p-1 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div x-show="loading" class="card text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 dark:border-teal-400 mx-auto"></div>
            </div>
            <div x-show="!loading && hadis" x-cloak class="glass-card bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 p-5 border border-teal-200 dark:border-teal-800">
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed mb-3" x-text="hadis?.text?.id || ''"></p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="hadis?.takhrij || ''"></span>
                    <a :href="`/hadith/${hadis?.id}`" class="text-teal-600 dark:text-teal-400 text-sm font-medium hover:text-teal-700 dark:hover:text-teal-300">Baca Selengkapnya →</a>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Koleksi Kitab Hadis</h3>
            <div class="space-y-3">
                @foreach($mukharrijList as $key => $mukharrij)
                    <a href="{{ route('hadith.collection', $key) }}" class="card-hover block group animate-slide-up">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 text-2xl" style="background-color: {{ $colorMap[$mukharrij['color']] ?? '#f3f4f6' }};">
                                {{ $mukharrij['icon'] }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $mukharrij['name'] }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $mukharrij['description'] }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        const response = await fetch(`/api/muslim/hadis/random?t=${Date.now()}`);
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
