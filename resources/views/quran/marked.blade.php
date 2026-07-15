<x-app-layout>
    <div class="px-4 py-6" x-data="markedAyahs()" x-init="loadMarkedAyahs()">
        <div class="mb-4">
            <a href="{{ route('quran.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ayat yang Ditandai</h2>
            <p class="text-gray-600 mt-1" x-text="`${items.length} ayat ditandai`"></p>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat ayat yang ditandai...</p>
        </div>

        <div x-show="!loading && items.length === 0" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <p class="text-gray-500 mb-2">Belum ada ayat yang ditandai</p>
            <p class="text-sm text-gray-400">Klik ikon pada ayat untuk menandai</p>
            <a href="{{ route('quran.index') }}" class="inline-block mt-4 bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-teal-700">Baca Quran</a>
        </div>

        <div x-show="!loading && items.length > 0" class="space-y-3">
            <template x-for="(item, index) in items" :key="index">
                <a :href="`/quran/${item.surah_number}?ayah=${item.ayah_number}`" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-teal-600" x-text="`${item.surah_name_latin} (${item.surah_name}`"></span>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full" x-text="`Ayat ${item.ayah_number}`"></span>
                    </div>
                    <p class="text-right text-xl leading-loose text-gray-800 mb-2" style="font-family: 'LPMQ IsepMisbah', serif" x-text="item.arabic"></p>
                    <p class="text-sm text-gray-600 line-clamp-2" x-text="item.translation"></p>
                    <div class="flex items-center justify-end mt-3">
                        <button @click.prevent="removeMarked(index)" class="text-red-400 hover:text-red-600 p-1" title="Hapus dari daftar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </a>
            </template>
        </div>
    </div>

    <script>
        function markedAyahs() {
            return {
                items: [],
                loading: true,

                async loadMarkedAyahs() {
                    const allMarked = [];
                    const surahCache = {};

                    for (let i = 0; i < localStorage.length; i++) {
                        const key = localStorage.key(i);
                        if (key && key.startsWith('quran_bookmarks_')) {
                            const surahNum = parseInt(key.replace('quran_bookmarks_', ''));
                            const ayahIndexes = JSON.parse(localStorage.getItem(key) || '[]');
                            
                            if (ayahIndexes.length === 0) continue;

                            if (!surahCache[surahNum]) {
                                const cached = localStorage.getItem(`quran_surah_${surahNum}`);
                                if (cached) {
                                    try {
                                        surahCache[surahNum] = JSON.parse(cached);
                                    } catch (e) {
                                        surahCache[surahNum] = null;
                                    }
                                } else {
                                    try {
                                        const resp = await fetch(`/api/muslim/quran/surah/${surahNum}`);
                                        const data = await resp.json();
                                        if (data) {
                                            surahCache[surahNum] = {
                                                name_latin: data.name_latin,
                                                name: data.name,
                                                translation: data.translation,
                                                ayahs: data.ayahs || []
                                            };
                                            localStorage.setItem(`quran_surah_${surahNum}`, JSON.stringify(surahCache[surahNum]));
                                        }
                                    } catch (e) {
                                        surahCache[surahNum] = null;
                                    }
                                }
                            }

                            const surahInfo = surahCache[surahNum];
                            
                            ayahIndexes.sort((a, b) => a - b).forEach(idx => {
                                const ayah = surahInfo?.ayahs?.[idx];
                                allMarked.push({
                                    surah_number: surahNum,
                                    ayah_number: idx + 1,
                                    surah_name_latin: surahInfo?.name_latin || `Surat ${surahNum}`,
                                    surah_name: surahInfo?.name || '',
                                    arabic: ayah?.arab || '',
                                    translation: ayah?.translation || ''
                                });
                            });
                        }
                    }
                    
                    allMarked.sort((a, b) => a.surah_number - b.surah_number || a.ayah_number - b.ayah_number);
                    this.items = allMarked;
                    this.loading = false;
                },

                removeMarked(index) {
                    if (!confirm('Hapus ayat ini dari daftar?')) return;
                    
                    const item = this.items[index];
                    const key = `quran_bookmarks_${item.surah_number}`;
                    const saved = JSON.parse(localStorage.getItem(key) || '[]');
                    const ayahIdx = item.ayah_number - 1;
                    const idx = saved.indexOf(ayahIdx);
                    
                    if (idx > -1) {
                        saved.splice(idx, 1);
                        if (saved.length === 0) {
                            localStorage.removeItem(key);
                        } else {
                            localStorage.setItem(key, JSON.stringify(saved));
                        }
                    }
                    
                    this.items.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
