<x-app-layout>
    <div class="px-4 py-6" x-data="surahReader({{ $surahNumber }})" x-init="init()">
        <div class="mb-4">
            <a href="{{ route('quran.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Memuat surat...</p>
        </div>

        {{-- Surah Header --}}
        <div x-show="!loading && surah" class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-5 mb-4 text-white text-center">
            <p class="text-teal-100 text-sm" x-text="surah?.revelation"></p>
            <h2 class="text-2xl font-bold mt-1" x-text="surah?.name_latin"></h2>
            <p class="text-teal-100" x-text="surah?.translation"></p>
            <p class="text-3xl mt-3" style="font-family: 'LPMQ IsepMisbah', serif" x-text="surah?.name"></p>
            <p class="text-teal-100 text-sm mt-2" x-text="`${surah?.number_of_ayahs} Ayat`"></p>
        </div>

        {{-- Pengaturan Tampilan --}}
        <div x-show="!loading && surah" class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
            <button @click="showSettings = !showSettings" class="flex items-center justify-between w-full">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Pengaturan Tampilan</span>
                </div>
                <svg :class="['w-4 h-4 text-gray-400 transition', showSettings ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="showSettings" x-collapse class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Jenis Font Arab</label>
                    <select x-model="arabicFont" @change="saveSettings()" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="'LPMQ IsepMisbah', serif">LPMQ IsepMisbah</option>
                        <option value="'Amiri Quran', serif">Amiri Quran</option>
                        <option value="'Amiri', serif">Amiri</option>
                        <option value="'Scheherazade New', serif">Scheherazade New</option>
                        <option value="'Noto Naskh Arabic', serif">Noto Naskh Arabic</option>
                    </select>
                    <div class="mt-2 bg-gray-50 rounded-lg p-3 text-center overflow-hidden">
                        <p class="text-xs text-gray-500 mb-1">Preview:</p>
                        <p class="text-gray-800 leading-relaxed" :style="`font-family: ${arabicFont}; font-size: ${arabicFontSize}%`">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Ukuran Font Arab: <span x-text="arabicFontSize + '%'"></span></label>
                    <div class="flex items-center space-x-3">
                        <button @click="arabicFontSize = Math.max(80, arabicFontSize - 10); saveSettings()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-200 text-sm font-bold">-</button>
                        <input type="range" x-model="arabicFontSize" @input="saveSettings()" min="80" max="250" step="10" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-teal-600">
                        <button @click="arabicFontSize = Math.min(250, arabicFontSize + 10); saveSettings()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-200 text-sm font-bold">+</button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-500">Auto-Scroll Saat Audio</label>
                    <button @click="autoScroll = !autoScroll; saveSettings()" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition', autoScroll ? 'bg-teal-600' : 'bg-gray-200']">
                        <span :class="['inline-block h-4 w-4 transform rounded-full bg-white transition', autoScroll ? 'translate-x-6' : 'translate-x-1']"></span>
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-500">Tampilkan Huruf Latin</label>
                    <button @click="showLatin = !showLatin; saveSettings()" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition', showLatin ? 'bg-teal-600' : 'bg-gray-200']">
                        <span :class="['inline-block h-4 w-4 transform rounded-full bg-white transition', showLatin ? 'translate-x-6' : 'translate-x-1']"></span>
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-500">Tampilkan Terjemahan</label>
                    <button @click="showTranslation = !showTranslation; saveSettings()" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition', showTranslation ? 'bg-teal-600' : 'bg-gray-200']">
                        <span :class="['inline-block h-4 w-4 transform rounded-full bg-white transition', showTranslation ? 'translate-x-6' : 'translate-x-1']"></span>
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-500">Tampilkan Tafsir</label>
                    <button @click="showTafsir = !showTafsir; saveSettings()" :class="['relative inline-flex h-6 w-11 items-center rounded-full transition', showTafsir ? 'bg-teal-600' : 'bg-gray-200']">
                        <span :class="['inline-block h-4 w-4 transform rounded-full bg-white transition', showTafsir ? 'translate-x-6' : 'translate-x-1']"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Pengaturan Audio --}}
        <div x-show="!loading && surah" class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
            <button @click="showAudioSettings = !showAudioSettings" class="flex items-center justify-between w-full">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Pengaturan Audio</span>
                </div>
                <svg :class="['w-4 h-4 text-gray-400 transition', showAudioSettings ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="showAudioSettings" x-collapse class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-3">Mode Audio Murottal</label>
                    <div class="space-y-3">
                        <label class="flex items-start p-3 rounded-lg border cursor-pointer transition" :class="audioMode === 'normal' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" x-model="audioMode" value="normal" @change="saveSettings()" class="mt-0.5 text-teal-600 focus:ring-teal-500">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-800">Normal (Full Surat)</span>
                                <p class="text-xs text-gray-500 mt-0.5">Audio murottal biasa, highlight per ayat (card abu-abu)</p>
                            </div>
                        </label>
                        <label class="flex items-start p-3 rounded-lg border cursor-pointer transition" :class="audioMode === 'word-by-word' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" x-model="audioMode" value="word-by-word" @change="saveSettings()" class="mt-0.5 text-teal-600 focus:ring-teal-500">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-800">Per Kata (Word-by-Word)</span>
                                <p class="text-xs text-gray-500 mt-0.5">Audio per kata, highlight per kata sinkron dengan bacaan</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Audio Player --}}
        <div x-show="!loading && surah" class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleFullSurah()" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white mr-3">
                        <template x-if="!isPlayingFull">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </template>
                        <template x-if="isPlayingFull">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                        </template>
                    </button>
                    <div>
                        <p class="font-medium text-gray-800">Audio Murottal</p>
                        <p class="text-sm text-gray-500" x-text="audioMode === 'word-by-word' ? 'Word-by-Word Sync' : 'Normal'"></p>
                        <p x-show="currentPlayingAyah >= 0" class="text-xs text-amber-600 mt-0.5">
                            Sedang memutar: Ayat <span x-text="ayahs[currentPlayingAyah]?.ayah_number"></span>
                        </p>
                    </div>
                </div>
                <button @click="stopFullSurah()" x-show="isPlayingFull" class="text-red-500 hover:text-red-700 p-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
                </button>
            </div>
        </div>

        {{-- Ayat --}}
        <div x-show="!loading && surah" class="space-y-4">
            <template x-for="(ayah, index) in ayahs" :key="`${currentSurahNumber}-${index}`">
                <div :id="`ayah-${ayah.ayah_number}`" 
                     :class="[
                         'rounded-xl p-5 shadow-sm border transition-all duration-500 scroll-mt-24',
                         currentPlayingAyah === index 
                             ? 'bg-gray-100 border-gray-200 shadow-md' 
                             : 'bg-white border-gray-100'
                     ]">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center',
                                currentPlayingAyah === index ? 'bg-amber-200' : 'bg-teal-100'
                            ]">
                                <span :class="[
                                    'text-xs font-bold',
                                    currentPlayingAyah === index ? 'text-amber-700' : 'text-teal-700'
                                ]" x-text="ayah.ayah_number"></span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button @click="playSingleAyah(index)" class="w-9 h-9 rounded-full flex items-center justify-center transition" :class="currentPlayingAyah === index ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-teal-100 hover:text-teal-600'">
                                <template x-if="currentPlayingAyah !== index">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </template>
                                <template x-if="currentPlayingAyah === index">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                </template>
                            </button>
                            <button @click="toggleBookmark(index)" class="w-9 h-9 rounded-full flex items-center justify-center transition" :class="bookmarkedAyahs.includes(index) ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-400 hover:bg-amber-50 hover:text-amber-500'" title="Tandai Ayat">
                                <svg class="w-4 h-4" :fill="bookmarkedAyahs.includes(index) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-right leading-loose text-gray-800" :style="`font-family: ${arabicFont}; font-size: ${arabicFontSize}%`">
                        <template x-if="currentPlayingAyah !== index || audioMode === 'normal'">
                            <span x-text="ayah.arab"></span>
                        </template>
                        <template x-if="currentPlayingAyah === index && audioMode === 'word-by-word'">
                            <span>
                                <template x-for="(word, wIndex) in ayah.arab.split(' ')" :key="wIndex">
                                    <span :class="wIndex === highlightedWordIndex ? 'bg-yellow-200 rounded px-0.5 transition-colors duration-100' : 'transition-colors duration-100'"
                                          x-text="word + ' '"></span>
                                </template>
                            </span>
                        </template>
                    </p>
                    <p x-show="showLatin" class="text-sm text-teal-600 italic mt-2 pl-10" x-text="transliterate(ayah.arab)"></p>
                    <p x-show="showTranslation" class="text-gray-600 text-sm border-t border-gray-100 mt-3 pt-3" x-text="ayah.translation"></p>
                    <div x-show="showTafsir && ayah.tafsir?.kemenag?.short" class="mt-3 bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 mb-1">Tafsir Kemenag:</p>
                        <p class="text-sm text-gray-700" x-text="ayah.tafsir?.kemenag?.short || ''"></p>
                    </div>
                </div>
            </template>

            {{-- Surah Navigation --}}
            <div x-show="!loadingNext" class="py-4">
                <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Previous Surah --}}
                    <a x-show="currentSurahNumber > 1" 
                       :href="`/quran/${currentSurahNumber - 1}`"
                       class="flex items-center px-4 py-3 hover:bg-gray-50 transition flex-1">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-xs text-gray-400">Sebelumnya</p>
                            <p class="text-sm font-medium text-gray-700 truncate" x-text="prevSurahName"></p>
                        </div>
                    </a>
                    <div x-show="currentSurahNumber <= 1" class="flex-1"></div>

                    {{-- Center Info --}}
                    <div class="px-3 py-3 text-center border-x border-gray-100">
                        <p class="text-xs text-gray-400">Surat</p>
                        <p class="text-sm font-bold text-teal-600" x-text="currentSurahNumber"></p>
                    </div>

                    {{-- Next Surah --}}
                    <a x-show="hasNextSurah" 
                       :href="`/quran/${currentSurahNumber + 1}`"
                       class="flex items-center px-4 py-3 hover:bg-gray-50 transition flex-1 justify-end">
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Selanjutnya</p>
                            <p class="text-sm font-medium text-gray-700 truncate" x-text="nextSurahName"></p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <div x-show="!hasNextSurah" class="flex-1"></div>
                </div>
            </div>

            {{-- Loading Next Surah --}}
            <div x-show="loadingNext" class="text-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
                <p class="text-gray-500 mt-3">Memuat surat berikutnya...</p>
            </div>

            {{-- End of Quran --}}
            <div x-show="!hasNextSurah" class="bg-gradient-to-b from-teal-50 to-teal-100 rounded-2xl p-6 text-center border border-teal-200">
                <svg class="w-12 h-12 text-teal-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-teal-800 font-bold text-lg">Alhamdulillah</p>
                <p class="text-teal-600 mt-1">Anda telah menyelesaikan Al-Quran</p>
                <a href="{{ route('quran.index') }}" class="inline-block mt-4 bg-teal-600 text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-teal-700 transition">
                    Kembali ke Daftar Surat
                </a>
            </div>
        </div>
    </div>

    <style>[x-collapse]{overflow:hidden}</style>

    <script>
        const ARABIC_LATIN_MAP = {
            '\u0627': 'a', '\u0628': 'b', '\u062A': 't', '\u062B': 'ts',
            '\u062C': 'j', '\u062D': '\u1E25', '\u062E': 'kh', '\u062F': 'd',
            '\u0630': 'dz', '\u0631': 'r', '\u0632': 'z', '\u0633': 's',
            '\u0634': 'sy', '\u0635': '\u1E63', '\u0636': '\u1E0D', '\u0637': '\u1E6D',
            '\u0638': '\u1E93', '\u0639': '\u2018', '\u063A': 'gh', '\u0641': 'f',
            '\u0642': 'q', '\u0643': 'k', '\u0644': 'l', '\u0645': 'm',
            '\u0646': 'n', '\u0647': 'h', '\u0648': 'w', '\u064A': 'y',
            '\u0621': "'", '\u0622': '\u0101', '\u0623': 'a', '\u0625': 'i',
            '\u0624': 'u', '\u0626': "'",
            '\u064E': 'a', '\u064F': 'u', '\u0650': 'i', '\u0652': '',
            '\u064B': 'an', '\u064C': 'un', '\u064D': 'in',
            '\u0651': '', '\u0653': '', '\u0654': '', '\u0655': '',
            '\u0670': '\u0101', '\u0640': '',
            ' ': ' ', '\u060C': ',', '\u061B': ';', '\u061F': '?',
        };

        const SPECIAL_COMBOS = {
            '\u0627\u0644\u0644\u0651\u0670\u0647': 'All\u0101h',
            '\u0627\u0644\u0644\u0651\u0647': 'All\u0101h',
            '\u0628\u0650\u0633\u0652\u0645\u0650': 'Bismi',
        };

        function transliterateArabic(text) {
            if (!text) return '';
            let result = text;
            for (const [arabic, latin] of Object.entries(SPECIAL_COMBOS)) {
                result = result.split(arabic).join(latin);
            }
            let latin = '';
            for (let i = 0; i < result.length; i++) {
                const char = result[i];
                if (ARABIC_LATIN_MAP[char] !== undefined) {
                    latin += ARABIC_LATIN_MAP[char];
                } else if (char.charCodeAt(0) > 255) {
                    latin += '';
                } else {
                    latin += char;
                }
            }
            return latin.replace(/\s+/g, ' ').trim();
        }

        function surahReader(initialSurahNumber) {
            return {
                surah: null,
                ayahs: [],
                loading: true,
                showSettings: false,
                showAudioSettings: false,
                arabicFont: "'LPMQ IsepMisbah', serif",
                arabicFontSize: 100,
                showLatin: false,
                showTranslation: true,
                showTafsir: false,
                autoScroll: true,
                audioMode: 'normal',
                bookmarkedAyahs: [],
                isPlayingFull: false,
                currentPlayingAyah: -1,
                highlightedWordIndex: -1,
                currentAudio: null,
                stopRequested: false,
                wordDataCache: {},
                currentSurahNumber: initialSurahNumber,
                hasNextSurah: true,
                loadingNext: false,
                nextSurahName: '',
                prevSurahName: '',
                touchStartX: 0,
                touchStartY: 0,
                swipeThreshold: 50,
                isSwiping: false,

                init() {
                    this.loadSettings();
                    this.loadBookmarks();
                    this.loadSurah();
                    this.setupSwipeGesture();
                    this.setupMouseDrag();
                },

                setupSwipeGesture() {
                    const mainContent = document.querySelector('main');
                    if (!mainContent) return;

                    mainContent.addEventListener('touchstart', (e) => {
                        this.touchStartX = e.touches[0].clientX;
                        this.touchStartY = e.touches[0].clientY;
                        this.isSwiping = false;
                    }, { passive: true });

                    mainContent.addEventListener('touchmove', (e) => {
                        if (!this.touchStartX) return;
                        
                        const deltaX = e.touches[0].clientX - this.touchStartX;
                        const deltaY = Math.abs(e.touches[0].clientY - this.touchStartY);
                        
                        if (Math.abs(deltaX) > 15 && deltaY < Math.abs(deltaX)) {
                            this.isSwiping = true;
                        }
                    }, { passive: true });

                    mainContent.addEventListener('touchend', (e) => {
                        if (!this.touchStartX) return;
                        
                        const touchEndX = e.changedTouches[0].clientX;
                        const deltaX = touchEndX - this.touchStartX;
                        
                        if (this.isSwiping && Math.abs(deltaX) >= this.swipeThreshold) {
                            if (deltaX > 0 && this.currentSurahNumber > 1) {
                                window.location.href = `/quran/${this.currentSurahNumber - 1}`;
                            } else if (deltaX < 0 && this.hasNextSurah) {
                                window.location.href = `/quran/${this.currentSurahNumber + 1}`;
                            }
                        }
                        
                        this.touchStartX = 0;
                        this.touchStartY = 0;
                        this.isSwiping = false;
                    }, { passive: true });
                },

                setupMouseDrag() {
                    const mainContent = document.querySelector('main');
                    if (!mainContent) return;

                    let mouseDown = false;
                    let startX = 0;
                    let startY = 0;

                    mainContent.addEventListener('mousedown', (e) => {
                        mouseDown = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        mainContent.style.cursor = 'grabbing';
                    });

                    mainContent.addEventListener('mousemove', (e) => {
                        if (!mouseDown) return;
                        e.preventDefault();
                    });

                    mainContent.addEventListener('mouseup', (e) => {
                        if (!mouseDown) return;
                        mouseDown = false;
                        mainContent.style.cursor = '';

                        const deltaX = e.clientX - startX;
                        const deltaY = Math.abs(e.clientY - startY);

                        if (Math.abs(deltaX) >= 50 && deltaY < Math.abs(deltaX)) {
                            if (deltaX > 0 && this.currentSurahNumber > 1) {
                                window.location.href = `/quran/${this.currentSurahNumber - 1}`;
                            } else if (deltaX < 0 && this.hasNextSurah) {
                                window.location.href = `/quran/${this.currentSurahNumber + 1}`;
                            }
                        }
                    });

                    mainContent.addEventListener('mouseleave', () => {
                        mouseDown = false;
                        mainContent.style.cursor = '';
                    });
                },

                loadSettings() {
                    const saved = localStorage.getItem('quran_settings');
                    if (saved) {
                        const s = JSON.parse(saved);
                        this.arabicFont = s.arabicFont || "'LPMQ IsepMisbah', serif";
                        this.arabicFontSize = s.arabicFontSize || 100;
                        this.showLatin = s.showLatin || false;
                        this.showTranslation = s.showTranslation !== false;
                        this.showTafsir = s.showTafsir || false;
                        this.autoScroll = s.autoScroll !== false;
                        this.audioMode = s.audioMode || 'normal';
                    }
                },

                saveSettings() {
                    localStorage.setItem('quran_settings', JSON.stringify({
                        arabicFont: this.arabicFont,
                        arabicFontSize: this.arabicFontSize,
                        showLatin: this.showLatin,
                        showTranslation: this.showTranslation,
                        showTafsir: this.showTafsir,
                        autoScroll: this.autoScroll,
                        audioMode: this.audioMode,
                    }));
                },

                loadBookmarks() {
                    const saved = localStorage.getItem(`quran_bookmarks_${this.currentSurahNumber}`);
                    if (saved) {
                        this.bookmarkedAyahs = JSON.parse(saved);
                    }
                },

                saveBookmarks() {
                    localStorage.setItem(`quran_bookmarks_${this.currentSurahNumber}`, JSON.stringify(this.bookmarkedAyahs));
                    let timestamps = JSON.parse(localStorage.getItem(`quran_bookmarks_ts_${this.currentSurahNumber}`) || '{}');
                    this.bookmarkedAyahs.forEach(idx => {
                        if (!timestamps[idx]) {
                            timestamps[idx] = new Date().toISOString();
                        }
                    });
                    Object.keys(timestamps).forEach(key => {
                        if (!this.bookmarkedAyahs.includes(parseInt(key))) {
                            delete timestamps[key];
                        }
                    });
                    localStorage.setItem(`quran_bookmarks_ts_${this.currentSurahNumber}`, JSON.stringify(timestamps));
                },

                toggleBookmark(index) {
                    const idx = this.bookmarkedAyahs.indexOf(index);
                    if (idx > -1) {
                        this.bookmarkedAyahs.splice(idx, 1);
                    } else {
                        this.bookmarkedAyahs.push(index);
                    }
                    this.saveBookmarks();
                },

                transliterate(text) {
                    return transliterateArabic(text);
                },

                async loadSurah() {
                    try {
                        const response = await fetch(`/api/muslim/quran/surah/${this.currentSurahNumber}`);
                        const data = await response.json();
                        if (data) {
                            this.surah = data;
                            this.ayahs = data.ayahs || [];
                            this.hasNextSurah = this.currentSurahNumber < 114;
                            
                            if (this.hasNextSurah) {
                                this.loadSurahName(this.currentSurahNumber + 1, 'next');
                            }
                            if (this.currentSurahNumber > 1) {
                                this.loadSurahName(this.currentSurahNumber - 1, 'prev');
                            }

                            localStorage.setItem(`quran_surah_${this.currentSurahNumber}`, JSON.stringify({
                                name_latin: data.name_latin,
                                name: data.name,
                                translation: data.translation,
                                number_of_ayahs: data.number_of_ayahs,
                                ayahs: data.ayahs
                            }));

                            const urlParams = new URLSearchParams(window.location.search);
                            const scrollToAyah = urlParams.get('ayah');
                            if (scrollToAyah) {
                                setTimeout(() => {
                                    const el = document.getElementById(`ayah-${scrollToAyah}`);
                                    if (el) {
                                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        el.classList.add('ring-2', 'ring-teal-400', 'ring-offset-2');
                                        setTimeout(() => el.classList.remove('ring-2', 'ring-teal-400', 'ring-offset-2'), 3000);
                                    }
                                }, 500);
                            }
                        }
                    } catch (error) {
                        console.error('Error loading surah:', error);
                    }
                    this.loading = false;
                },

                async loadSurahName(number, type) {
                    try {
                        const cached = localStorage.getItem(`quran_surah_${number}`);
                        if (cached) {
                            const data = JSON.parse(cached);
                            if (type === 'next') this.nextSurahName = data.name_latin;
                            if (type === 'prev') this.prevSurahName = data.name_latin;
                            return;
                        }

                        const response = await fetch(`/api/muslim/quran/surah/${number}`);
                        const data = await response.json();
                        if (data) {
                            if (type === 'next') this.nextSurahName = data.name_latin;
                            if (type === 'prev') this.prevSurahName = data.name_latin;
                        }
                    } catch (error) {
                        console.error('Error loading surah name:', error);
                    }
                },

                async loadNextSurah() {
                    if (this.loadingNext || !this.hasNextSurah) return;
                    
                    this.loadingNext = true;
                    
                    const nextNumber = this.currentSurahNumber + 1;
                    
                    if (nextNumber > 114) {
                        this.hasNextSurah = false;
                        this.loadingNext = false;
                        return;
                    }

                    try {
                        const response = await fetch(`/api/muslim/quran/surah/${nextNumber}`);
                        const data = await response.json();
                        
                        if (data && data.ayahs) {
                            this.ayahs = [...this.ayahs, ...data.ayahs];
                            this.currentSurahNumber = nextNumber;
                            this.surah = data;
                            this.hasNextSurah = nextNumber < 114;
                            
                            if (this.hasNextSurah) {
                                this.loadNextSurahName();
                            }

                            history.replaceState(null, '', `/quran/${nextNumber}`);
                        }
                    } catch (error) {
                        console.error('Error loading next surah:', error);
                    }
                    
                    this.loadingNext = false;
                },

                scrollToAyah(ayahNumber) {
                    const el = document.getElementById(`ayah-${ayahNumber}`);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                },

                sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                },

                async fetchWordData(surah, ayah) {
                    const cacheKey = `quran_words_${surah}_${ayah}`;
                    
                    if (this.wordDataCache[cacheKey]) {
                        return this.wordDataCache[cacheKey];
                    }

                    const cached = localStorage.getItem(cacheKey);
                    if (cached) {
                        try {
                            const data = JSON.parse(cached);
                            this.wordDataCache[cacheKey] = data;
                            return data;
                        } catch (e) {}
                    }

                    try {
                        const response = await fetch(`https://api.quran.com/api/v4/verses/by_chapter/${surah}?words=true&fields=text_uthmani`);
                        const data = await response.json();
                        
                        const verse = data.verses?.find(v => v.verse_number === ayah);
                        if (verse?.words) {
                            const words = verse.words.filter(w => w.char_type_name === 'word');
                            this.wordDataCache[cacheKey] = words;
                            localStorage.setItem(cacheKey, JSON.stringify(words));
                            return words;
                        }
                    } catch (error) {
                        console.error('Error fetching word data:', error);
                    }

                    return null;
                },

                async playSingleAyah(index) {
                    if (this.currentPlayingAyah === index && this.isPlayingFull === false) {
                        this.stopFullSurah();
                        return;
                    }

                    this.stopFullSurah();
                    await this.sleep(200);

                    this.isPlayingFull = false;
                    this.currentPlayingAyah = index;
                    this.highlightedWordIndex = -1;
                    this.stopRequested = false;

                    const ayah = this.ayahs[index];
                    if (!ayah) return;

                    if (this.autoScroll) {
                        this.scrollToAyah(ayah.ayah_number);
                    }

                    await this.playAyahAudio(index);

                    this.currentPlayingAyah = -1;
                    this.highlightedWordIndex = -1;
                },

                async playAyahAudio(index) {
                    if (this.audioMode === 'word-by-word') {
                        await this.playAyahWithWordSync(index);
                    } else {
                        await this.playAyahNormal(index);
                    }
                },

                async playAyahNormal(index) {
                    const ayah = this.ayahs[index];
                    if (!ayah?.audio_url) {
                        console.error('No audio URL for ayah:', index);
                        return;
                    }

                    return new Promise((resolve) => {
                        const audio = new Audio();
                        this.currentAudio = audio;

                        audio.addEventListener('canplaythrough', () => {
                            audio.play().catch(err => {
                                console.error('Audio play error:', err);
                                resolve();
                            });
                        }, { once: true });

                        audio.addEventListener('ended', () => {
                            resolve();
                        }, { once: true });

                        audio.addEventListener('error', (e) => {
                            console.error('Audio error:', e);
                            resolve();
                        }, { once: true });

                        audio.src = ayah.audio_url;
                        audio.load();
                    });
                },

                async playAyahWithWordSync(index) {
                    const ayah = this.ayahs[index];
                    if (!ayah) return;

                    const wordData = await this.fetchWordData(this.currentSurahNumber, ayah.ayah_number);
                    
                    if (wordData && wordData.length > 0) {
                        await this.playWithWordAudio(index, wordData);
                    } else {
                        await this.playWithEstimate(index);
                    }
                },

                async playWithWordAudio(index, wordData) {
                    return new Promise(async (resolve) => {
                        for (let i = 0; i < wordData.length; i++) {
                            if (this.stopRequested) break;

                            this.highlightedWordIndex = i;

                            const word = wordData[i];
                            if (word.audio_url) {
                                try {
                                    await this.playWordAudio(`https://verses.quran.com/${word.audio_url}`);
                                } catch (e) {
                                    await this.sleep(300);
                                }
                            } else {
                                await this.sleep(300);
                            }
                        }

                        this.highlightedWordIndex = -1;
                        resolve();
                    });
                },

                playWordAudio(url) {
                    return new Promise((resolve) => {
                        const audio = new Audio();
                        this.currentAudio = audio;

                        audio.addEventListener('canplaythrough', () => {
                            audio.play().catch(err => {
                                console.error('Word audio play error:', err);
                                resolve();
                            });
                        }, { once: true });

                        audio.addEventListener('ended', () => {
                            resolve();
                        }, { once: true });

                        audio.addEventListener('error', (e) => {
                            console.error('Word audio error:', e);
                            resolve();
                        }, { once: true });

                        audio.src = url;
                        audio.load();
                    });
                },

                async playWithEstimate(index) {
                    const ayah = this.ayahs[index];
                    if (!ayah?.audio_url) return;

                    return new Promise((resolve) => {
                        const audio = new Audio(ayah.audio_url);
                        this.currentAudio = audio;
                        let highlightInterval = null;
                        let startTime = 0;

                        const startHighlight = () => {
                            const words = ayah.arab.split(' ').filter(w => w.trim());
                            const duration = audio.duration * 1000;
                            const timePerWord = duration / words.length;
                            
                            startTime = Date.now();
                            let wordIndex = 0;
                            this.highlightedWordIndex = 0;

                            highlightInterval = setInterval(() => {
                                if (!this.stopRequested && !audio.paused) {
                                    const elapsed = Date.now() - startTime;
                                    const newIndex = Math.floor(elapsed / timePerWord);
                                    
                                    if (newIndex < words.length && newIndex !== wordIndex) {
                                        wordIndex = newIndex;
                                        this.highlightedWordIndex = wordIndex;
                                    }
                                    
                                    if (newIndex >= words.length) {
                                        clearInterval(highlightInterval);
                                        this.highlightedWordIndex = -1;
                                    }
                                }
                            }, 50);
                        };

                        audio.addEventListener('playing', startHighlight);

                        audio.addEventListener('ended', () => {
                            if (highlightInterval) clearInterval(highlightInterval);
                            this.highlightedWordIndex = -1;
                            resolve();
                        });

                        audio.addEventListener('error', () => {
                            if (highlightInterval) clearInterval(highlightInterval);
                            this.highlightedWordIndex = -1;
                            resolve();
                        });

                        audio.play().catch(() => {
                            this.highlightedWordIndex = -1;
                            resolve();
                        });
                    });
                },

                async toggleFullSurah() {
                    if (this.isPlayingFull) {
                        this.stopFullSurah();
                    } else {
                        this.playFullSurah();
                    }
                },

                async playFullSurah() {
                    this.isPlayingFull = true;
                    this.stopRequested = false;

                    // Preload first ayah
                    let nextAudioPromise = this.preloadAudio(this.ayahs[0]?.audio_url);

                    for (let i = 0; i < this.ayahs.length; i++) {
                        if (this.stopRequested) break;

                        this.currentPlayingAyah = i;
                        this.highlightedWordIndex = -1;

                        if (this.autoScroll) {
                            this.scrollToAyah(this.ayahs[i].ayah_number);
                        }

                        // Wait for current audio to be ready
                        const currentAudio = await nextAudioPromise;

                        // Preload next ayah while current is playing
                        if (i < this.ayahs.length - 1) {
                            nextAudioPromise = this.preloadAudio(this.ayahs[i + 1]?.audio_url);
                        }

                        // Play current ayah
                        if (currentAudio) {
                            this.currentAudio = currentAudio;
                            await this.playPreloadedAudio(currentAudio);
                        }

                        if (this.stopRequested) break;
                    }

                    this.currentPlayingAyah = -1;
                    this.highlightedWordIndex = -1;
                    this.isPlayingFull = false;
                    this.stopRequested = false;
                },

                preloadAudio(url) {
                    return new Promise((resolve) => {
                        if (!url) {
                            resolve(null);
                            return;
                        }

                        const audio = new Audio();
                        audio.preload = 'auto';

                        audio.addEventListener('canplaythrough', () => {
                            resolve(audio);
                        }, { once: true });

                        audio.addEventListener('error', () => {
                            resolve(null);
                        }, { once: true });

                        audio.src = url;
                        audio.load();
                    });
                },

                playPreloadedAudio(audio) {
                    return new Promise((resolve) => {
                        if (!audio) {
                            resolve();
                            return;
                        }

                        audio.addEventListener('ended', () => {
                            resolve();
                        }, { once: true });

                        audio.addEventListener('error', () => {
                            resolve();
                        }, { once: true });

                        audio.play().catch(() => resolve());
                    });
                },

                stopFullSurah() {
                    this.stopRequested = true;
                    if (this.currentAudio) {
                        this.currentAudio.pause();
                        this.currentAudio = null;
                    }
                    this.currentPlayingAyah = -1;
                    this.highlightedWordIndex = -1;
                    this.isPlayingFull = false;
                }
            };
        }
    </script>
</x-app-layout>
