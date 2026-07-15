<x-app-layout>
    <div class="px-4 py-6" x-data="surahReader({{ $surahNumber }})" x-init="loadSurah()">
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

        <div x-show="!loading && surah" class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-5 mb-4 text-white text-center">
            <p class="text-teal-100 text-sm" x-text="surah?.revelation"></p>
            <h2 class="text-2xl font-bold mt-1" x-text="surah?.name_latin"></h2>
            <p class="text-teal-100" x-text="surah?.translation"></p>
            <p class="text-3xl mt-3" style="font-family: 'LPMQ IsepMisbah', serif" x-text="surah?.name"></p>
            <p class="text-teal-100 text-sm mt-2" x-text="`${surah?.number_of_ayahs} Ayat`"></p>
        </div>

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
                        <p x-show="showLatin" class="text-xs text-teal-600 italic mt-1">Bismillāhir-raḥmānir-raḥīm</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Ukuran Font Arab: <span x-text="arabicFontSize + '%'"></span></label>
                    <div class="flex items-center space-x-3">
                        <button @click="arabicFontSize = Math.max(80, arabicFontSize - 10); saveSettings()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-200 text-sm font-bold">-</button>
                        <input type="range" x-model="arabicFontSize" @input="saveSettings()" min="80" max="250" step="10" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-teal-600">
                        <button @click="arabicFontSize = Math.min(250, arabicFontSize + 10); saveSettings()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-200 text-sm font-bold">+</button>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>80%</span>
                        <span>250%</span>
                    </div>
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

        <div x-show="!loading && surah" class="bg-white rounded-xl p-4 mb-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleAudio()" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white mr-3">
                        <template x-if="!playing">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </template>
                        <template x-if="playing">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                        </template>
                    </button>
                    <div>
                        <p class="font-medium text-gray-800">Audio Murottal</p>
                        <p class="text-sm text-gray-500">Muhammad Thaha</p>
                    </div>
                </div>
                <button @click="stopAudio()" x-show="playing" class="text-red-500 hover:text-red-700 p-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
                </button>
            </div>
        </div>

        <div x-show="!loading && surah" class="space-y-4">
            <template x-for="(ayah, index) in ayahs" :key="index">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <span class="text-teal-700 text-xs font-bold" x-text="ayah.ayah_number"></span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button @click="playAyah(index)" class="w-9 h-9 rounded-full flex items-center justify-center transition" :class="playingAyah === index ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-teal-100 hover:text-teal-600'">
                                <template x-if="playingAyah !== index">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </template>
                                <template x-if="playingAyah === index">
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
                    <p class="text-right leading-loose text-gray-800" :style="`font-family: ${arabicFont}; font-size: ${arabicFontSize}%`" x-text="ayah.arab"></p>
                    <p x-show="showLatin" class="text-sm text-teal-600 italic mt-2 pl-10" x-text="transliterate(ayah.arab)"></p>
                    <p x-show="showTranslation" class="text-gray-600 text-sm border-t border-gray-100 mt-3 pt-3" x-text="ayah.translation"></p>
                    <div x-show="showTafsir && ayah.tafsir?.kemenag?.short" class="mt-3 bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-500 mb-1">Tafsir Kemenag:</p>
                        <p class="text-sm text-gray-700" x-text="ayah.tafsir?.kemenag?.short || ''"></p>
                    </div>
                </div>
            </template>
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
            '\u0670': '\u0101',
            '\u0640': '',
            ' ': ' ', '\u060C': ',', '\u061B': ';', '\u061F': '?',
            '\u0660': '0', '\u0661': '1', '\u0662': '2', '\u0663': '3',
            '\u0664': '4', '\u0665': '5', '\u0666': '6', '\u0667': '7',
            '\u0668': '8', '\u0669': '9',
        };

        const SPECIAL_COMBOS = {
            '\u0627\u0644\u0644\u0651\u0670\u0647': 'All\u0101h',
            '\u0627\u0644\u0644\u0651\u0647': 'All\u0101h',
            '\u0628\u0650\u0633\u0652\u0645\u0650': 'Bismi',
            '\u0627\u0644\u0631\u0651\u064E\u062D\u0652\u0645\u0670\u0646\u0650': 'ar-Ra\u1E25m\u0101n',
            '\u0627\u0644\u0631\u0651\u064E\u062D\u0650\u064A\u0652\u0645\u0650': 'ar-Ra\u1E25\u012Bm',
            '\u0645\u064F\u062D\u064E\u0645\u0651\u064E\u062F\u064C': 'Mu\u1E25ammad',
            '\u0645\u064F\u062D\u064E\u0645\u0651\u064E\u062F': 'Mu\u1E25ammad',
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

        function surahReader(surahNumber) {
            return {
                surah: null,
                ayahs: [],
                loading: true,
                playing: false,
                playingAyah: -1,
                currentAudio: null,
                showSettings: false,
                arabicFont: "'LPMQ IsepMisbah', serif",
                arabicFontSize: 100,
                showLatin: false,
                showTranslation: true,
                showTafsir: false,
                bookmarkedAyahs: [],

                init() {
                    this.loadSettings();
                    this.loadBookmarks();
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
                    }
                },

                saveSettings() {
                    localStorage.setItem('quran_settings', JSON.stringify({
                        arabicFont: this.arabicFont,
                        arabicFontSize: this.arabicFontSize,
                        showLatin: this.showLatin,
                        showTranslation: this.showTranslation,
                        showTafsir: this.showTafsir,
                    }));
                },

                loadBookmarks() {
                    const saved = localStorage.getItem(`quran_bookmarks_${surahNumber}`);
                    if (saved) {
                        this.bookmarkedAyahs = JSON.parse(saved);
                    }
                },

                saveBookmarks() {
                    localStorage.setItem(`quran_bookmarks_${surahNumber}`, JSON.stringify(this.bookmarkedAyahs));
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
                        const response = await fetch(`/api/muslim/quran/surah/${surahNumber}`);
                        const data = await response.json();
                        if (data) {
                            this.surah = data;
                            this.ayahs = data.ayahs || [];
                            localStorage.setItem(`quran_surah_${surahNumber}`, JSON.stringify({
                                name_latin: data.name_latin,
                                name: data.name,
                                translation: data.translation,
                                number_of_ayahs: data.number_of_ayahs,
                                ayahs: data.ayahs
                            }));
                        }
                    } catch (error) {
                        console.error('Error loading surah:', error);
                    }
                    this.loading = false;
                },

                toggleAudio() {
                    if (this.playing) {
                        this.stopAudio();
                    } else {
                        this.playingAyah = -1;
                        this.currentAudio = new Audio(`https://cdn.myquran.com/audio/surah/${surahNumber}.mp3`);
                        this.currentAudio.play();
                        this.playing = true;
                        this.currentAudio.onended = () => this.playing = false;
                    }
                },

                stopAudio() {
                    this.currentAudio?.pause();
                    this.currentAudio = null;
                    this.playing = false;
                    this.playingAyah = -1;
                },

                playAyah(index) {
                    if (this.playingAyah === index) {
                        this.stopAudio();
                        return;
                    }

                    this.currentAudio?.pause();
                    this.playing = false;

                    const ayah = this.ayahs[index];
                    if (ayah?.audio_url) {
                        this.currentAudio = new Audio(ayah.audio_url);
                        this.currentAudio.play();
                        this.playingAyah = index;
                        this.currentAudio.onended = () => this.playingAyah = -1;
                    }
                }
            };
        }
    </script>
</x-app-layout>
