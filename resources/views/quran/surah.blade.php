<x-app-layout>
    <div class="px-4 py-6" x-data="surahReader({{ $surahNumber }})" x-init="loadSurah()">
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('quran.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat surat...</p>
        </div>

        {{-- Surah Header --}}
        <div x-show="!loading && surah" class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-5 mb-6 text-white text-center">
            <p class="text-teal-100 text-sm" x-text="surah?.revelationType"></p>
            <h2 class="text-2xl font-bold mt-1" x-text="surah?.englishName"></h2>
            <p class="text-teal-100" x-text="surah?.englishNameTranslation"></p>
            <p class="font-arabic text-3xl mt-3" x-text="surah?.name"></p>
            <p class="text-teal-100 text-sm mt-2" x-text="`${surah?.numberOfAyahs} Ayat`"></p>
        </div>

        {{-- Audio Player --}}
        <div x-show="!loading && surah" class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleAudio()" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white mr-3">
                        <svg x-show="!playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg x-show="playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                        </svg>
                    </button>
                    <div>
                        <p class="font-medium text-gray-800">Audio Murottal</p>
                        <p class="text-sm text-gray-500">Mishary Rashid Alafasy</p>
                    </div>
                </div>
                <select x-model="reciter" @change="changeReciter()" class="text-sm border border-gray-200 rounded-lg px-2 py-1">
                    <option value="7">Alafasy</option>
                    <option value="1">Basfar</option>
                    <option value="2">Sudais</option>
                    <option value="5">Husary</option>
                </select>
            </div>
        </div>

        {{-- Ayahs --}}
        <div x-show="!loading && surah" class="space-y-4">
            <template x-for="(ayah, index) in ayahs" :key="index">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                            <span class="text-teal-700 text-xs font-bold" x-text="ayah.numberInSurah"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="playAyah(index)" class="p-1 text-gray-400 hover:text-teal-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <button @click="bookmarkAyah(index)" class="p-1 text-gray-400 hover:text-amber-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="font-arabic text-2xl text-right leading-loose text-gray-800 mb-3" x-text="ayah.text"></p>
                    <p class="text-gray-600 text-sm" x-text="translations[index]?.text || ''"></p>
                </div>
            </template>
        </div>
    </div>

    <script>
        function surahReader(surahNumber) {
            return {
                surah: null,
                ayahs: [],
                translations: [],
                loading: true,
                playing: false,
                reciter: 7,
                currentAudio: null,

                async loadSurah() {
                    try {
                        const [surahRes, transRes] = await Promise.all([
                            fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}`),
                            fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/id.indonesian`)
                        ]);

                        const surahData = await surahRes.json();
                        const transData = await transRes.json();

                        if (surahData.code === 200) {
                            this.surah = surahData.data;
                            this.ayahs = surahData.data.ayahs;
                        }

                        if (transData.code === 200) {
                            this.translations = transData.data.ayahs;
                        }
                    } catch (error) {
                        console.error('Error loading surah:', error);
                    }
                    this.loading = false;
                },

                toggleAudio() {
                    if (this.playing) {
                        this.currentAudio?.pause();
                        this.playing = false;
                    } else {
                        this.currentAudio = new Audio(`https://cdn.islamic.network/quran/audio-surah/128/${this.reciter}/${surahNumber}.mp3`);
                        this.currentAudio.play();
                        this.playing = true;
                        this.currentAudio.onended = () => this.playing = false;
                    }
                },

                playAyah(index) {
                    this.currentAudio?.pause();
                    this.currentAudio = new Audio(`https://cdn.islamic.network/quran/audio/${this.reciter}/${surahNumber}:${index + 1}.mp3`);
                    this.currentAudio.play();
                    this.playing = true;
                    this.currentAudio.onended = () => this.playing = false;
                },

                changeReciter() {
                    if (this.playing) {
                        this.currentAudio?.pause();
                        this.playing = false;
                    }
                },

                bookmarkAyah(index) {
                    // Implement bookmark functionality
                    alert('Bookmark fitur akan segera tersedia');
                }
            };
        }
    </script>
</x-app-layout>
