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
            <p class="text-gray-600 mt-1" x-text="`${markedAyahs.length} ayat ditandai`"></p>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat...</p>
        </div>

        <div x-show="!loading && markedAyahs.length === 0" class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <p class="text-gray-500 mb-2">Belum ada ayat yang ditandai</p>
            <p class="text-sm text-gray-400">Klik ikon bookmark pada ayat untuk menandai</p>
            <a href="{{ route('quran.index') }}" class="inline-block mt-4 bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-teal-700">Baca Quran</a>
        </div>

        <div x-show="!loading && markedAyahs.length > 0" class="space-y-3">
            <template x-for="(item, index) in markedAyahs" :key="index">
                <a :href="`/quran/${item.surah}?ayah=${item.ayah}`" class="block bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-teal-600" x-text="item.surah_name"></span>
                        <span class="text-xs text-gray-500" x-text="`Ayat ${item.ayah}`"></span>
                    </div>
                    <p class="text-right text-lg leading-loose text-gray-800 mb-2" style="font-family: 'LPMQ IsepMisbah', serif" x-text="item.arabic || ''"></p>
                    <p class="text-sm text-gray-600 line-clamp-2" x-text="item.translation || ''"></p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs text-gray-400" x-text="item.timestamp"></span>
                        <button @click.prevent="removeMarked(index)" class="text-red-400 hover:text-red-600 p-1" title="Hapus">
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
                markedAyahs: [],
                loading: true,

                loadMarkedAyahs() {
                    const allMarked = [];
                    for (let i = 0; i < localStorage.length; i++) {
                        const key = localStorage.key(i);
                        if (key && key.startsWith('quran_bookmarks_')) {
                            const surahNum = parseInt(key.replace('quran_bookmarks_', ''));
                            const ayahIndexes = JSON.parse(localStorage.getItem(key) || '[]');
                            
                            ayahIndexes.forEach(idx => {
                                const surahData = localStorage.getItem(`quran_surah_${surahNum}`);
                                let arabic = '';
                                let translation = '';
                                let surahName = `Surat ${surahNum}`;
                                
                                if (surahData) {
                                    try {
                                        const data = JSON.parse(surahData);
                                        surahName = data.name_latin || surahName;
                                        if (data.ayahs && data.ayahs[idx]) {
                                            arabic = data.ayahs[idx].arab || '';
                                            translation = data.ayahs[idx].translation || '';
                                        }
                                    } catch (e) {}
                                }
                                
                                allMarked.push({
                                    surah: surahNum,
                                    ayah: idx + 1,
                                    surah_name: surahName,
                                    arabic: arabic,
                                    translation: translation,
                                    timestamp: new Date().toLocaleDateString('id-ID')
                                });
                            });
                        }
                    }
                    
                    allMarked.sort((a, b) => a.surah - b.surah || a.ayah - b.ayah);
                    this.markedAyahs = allMarked;
                    this.loading = false;
                },

                removeMarked(index) {
                    if (!confirm('Hapus ayat ini dari daftar?')) return;
                    
                    const item = this.markedAyahs[index];
                    const key = `quran_bookmarks_${item.surah}`;
                    const saved = JSON.parse(localStorage.getItem(key) || '[]');
                    const ayahIdx = item.ayah - 1;
                    const idx = saved.indexOf(ayahIdx);
                    
                    if (idx > -1) {
                        saved.splice(idx, 1);
                        if (saved.length === 0) {
                            localStorage.removeItem(key);
                        } else {
                            localStorage.setItem(key, JSON.stringify(saved));
                        }
                    }
                    
                    this.markedAyahs.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
