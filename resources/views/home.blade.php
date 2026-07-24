<x-app-layout>
    <div class="max-w-[620px] mx-auto px-4 py-6">
        {{-- Welcome Section --}}
        <div class="mb-6 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Assalamu'alaikum</h2>
                    @auth
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ auth()->user()->name }}</p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Selamat datang di NgaOS</p>
                    @endauth
                </div>
                <div x-data="hijriDate()" x-init="loadHijriDate()" class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="hijri.date || ''"></p>
                    <p class="text-xs text-teal-600 dark:text-teal-400 font-medium" x-text="hijri.dayName || ''"></p>
                </div>
            </div>
        </div>

        {{-- Prayer Time Widget --}}
        <div x-data="prayerWidget()" x-init="loadPrayerTimes()" class="glass-card bg-gradient-to-br from-teal-600 to-teal-700 dark:from-teal-700 dark:to-teal-800 p-5 mb-6 text-white shadow-lg shadow-teal-500/25 animate-slide-up">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-teal-100 text-sm">Solat Berikutnya</p>
                    <h3 class="text-2xl font-bold" x-text="nextPrayer.name || 'Memuat...'"></h3>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold" x-text="nextPrayer.time || '--:--'"></p>
                    <p class="text-teal-100 text-sm" x-text="nextPrayer.remaining || ''"></p>
                </div>
            </div>
            <div class="flex justify-between text-xs text-teal-100">
                <span x-text="location">Mendeteksi lokasi...</span>
                <span x-text="date"></span>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div x-data="{ currentPage: 0, totalPages: 2 }" class="mb-6">
            <div class="overflow-x-auto snap-x snap-mandatory flex scrollbar-hide scroll-smooth touch-pan-x" x-ref="quickActions"
                 @scroll="currentPage = Math.round($refs.quickActions.scrollLeft / $refs.quickActions.offsetWidth)">
                {{-- Page 1 --}}
                <div class="snap-start min-w-full grid grid-cols-3 gap-3 px-0.5">
                    <a href="{{ route('quran.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Al-Quran</span>
                    </a>

                    <a href="{{ route('hadith.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hadis</span>
                    </a>

                    <a href="{{ route('qibla.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kiblat</span>
                    </a>

                    <a href="{{ route('prayer.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Jadwal</span>
                    </a>

                    <a href="{{ route('chatbot.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">AI Chat</span>
                    </a>
                </div>

                {{-- Page 2 --}}
                <div class="snap-start min-w-full grid grid-cols-3 gap-3 px-0.5">
                    <a href="{{ route('hijri.index') }}" class="card-hover flex flex-col items-center p-4 group h-32 sm:h-28">
                        <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kalender</span>
                    </a>
                </div>
            </div>

            {{-- Dot Indicator --}}
            <div class="flex justify-center space-x-2 mt-3">
                <template x-for="i in totalPages" :key="i">
                    <div :class="['w-2 h-2 rounded-full transition-all duration-300', currentPage === i - 1 ? 'bg-teal-600 dark:bg-teal-400 w-4' : 'bg-gray-300 dark:bg-gray-600']"></div>
                </template>
            </div>
        </div>

        {{-- Random Hadis --}}
        <div class="card mb-6 animate-slide-up" x-data="randomHadis()" x-init="loadHadis()">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hadis Hari Ini</h3>
                <button @click="loadHadis()" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition p-1 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div x-show="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 dark:border-teal-400 mx-auto"></div>
            </div>
            <div x-show="!loading && hadis" x-cloak class="space-y-3">
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed" x-text="hadis?.text?.id || ''"></p>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400" x-text="hadis?.takhrij || ''"></span>
                    <span :class="[hadis?.grade === 'Shahih' ? 'badge-green' : 'badge-amber']" class="badge" x-text="hadis?.grade || ''"></span>
                </div>
            </div>
        </div>

        {{-- Daily Verse --}}
        <div class="card mb-6 animate-slide-up" x-data="dailyVerse()" x-init="loadVerse()">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ayat Hari Ini</h3>
                <button @click="loadVerse()" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition p-1 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div x-show="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 dark:border-teal-400 mx-auto"></div>
            </div>
            <div x-show="!loading && verse" x-cloak class="space-y-3">
                <p class="font-arabic text-2xl text-right leading-loose text-gray-900 dark:text-white" x-text="verse?.arab"></p>
                <p class="text-gray-600 dark:text-gray-400 text-sm italic" x-text="verse?.translation"></p>
                <p class="text-teal-600 dark:text-teal-400 text-sm font-medium" x-text="verse?.reference"></p>
            </div>
        </div>
    </div>

    <script>
        function hijriDate() {
            return {
                hijri: {},
                async loadHijriDate() {
                    try {
                        const response = await fetch('/api/muslim/hijri/today');
                        const data = await response.json();
                        if (data) {
                            this.hijri = {
                                date: data.hijr?.today || '',
                                dayName: data.hijr?.dayName || ''
                            };
                        }
                    } catch (error) {
                        console.error('Error loading hijri date:', error);
                    }
                }
            };
        }

        function prayerWidget() {
            return {
                nextPrayer: {},
                location: 'Mendeteksi lokasi...',
                date: '',
                loading: true,
                cityId: '{{ auth()->user()->city_id ?? "" }}',
                timezone: '{{ auth()->user()->timezone ?? "" }}',

                async loadPrayerTimes() {
                    if (!this.cityId) {
                        await this.detectAndLoad();
                        return;
                    }

                    await this.fetchPrayerTimes(this.cityId, this.timezone || 'Asia/Jakarta');
                },

                async detectAndLoad() {
                    try {
                        const position = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, {
                                enableHighAccuracy: true,
                                timeout: 10000
                            });
                        });

                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const tz = this.getTimezoneFromCoords(lat, lng);

                        const cityName = await this.reverseGeocode(lat, lng);
                        
                        if (cityName) {
                            const keywords = [
                                cityName,
                                cityName.replace('KOTA ', '').replace('KAB. ', '').replace('KABUPATEN ', ''),
                                cityName.split(' ').pop()
                            ];

                            for (const keyword of keywords) {
                                if (!keyword || keyword.length < 3) continue;
                                
                                const cityResponse = await fetch(`/api/muslim/city/search?q=${encodeURIComponent(keyword)}`);
                                const cityData = await cityResponse.json();

                                if (Array.isArray(cityData) && cityData.length > 0) {
                                    const match = cityData.find(c => 
                                        c.lokasi.toUpperCase().includes(cityName.toUpperCase()) ||
                                        cityName.toUpperCase().includes(c.lokasi.toUpperCase())
                                    ) || cityData[0];

                                    this.cityId = match.id;
                                    await this.fetchPrayerTimes(this.cityId, tz);
                                    return;
                                }
                            }
                        }

                        this.location = 'Atur lokasi di Profil';
                        this.nextPrayer = { name: 'Dzuhur', time: '12:00', remaining: '--' };
                    } catch (error) {
                        console.error('Error detecting location:', error);
                        this.location = 'Atur lokasi di Profil';
                        this.nextPrayer = { name: 'Dzuhur', time: '12:00', remaining: '--' };
                    }
                    this.loading = false;
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&addressdetails=1`, {
                            headers: { 'Accept-Language': 'id' }
                        });
                        const data = await response.json();
                        return data.address?.city || data.address?.regency || data.address?.county || data.address?.state || '';
                    } catch (error) {
                        return '';
                    }
                },

                getTimezoneFromCoords(lat, lng) {
                    if (lng >= 140) return 'Asia/Jayapura';
                    if (lng >= 120) return 'Asia/Makassar';
                    return 'Asia/Jakarta';
                },

                async fetchPrayerTimes(cityId, tz) {
                    try {
                        const response = await fetch(`/api/muslim/prayer?city_id=${cityId}&tz=${tz}`);
                        const data = await response.json();
                        if (data?.jadwal) {
                            this.location = data.kabko || 'Indonesia';
                            this.date = data.jadwal.tanggal || '';
                            const schedule = data.jadwal;
                            const now = new Date();
                            const prayers = [
                                { name: 'Subuh', time: schedule.subuh },
                                { name: 'Dzuhur', time: schedule.dzuhur },
                                { name: 'Ashar', time: schedule.ashar },
                                { name: 'Maghrib', time: schedule.maghrib },
                                { name: 'Isya', time: schedule.isya },
                            ];
                            for (const prayer of prayers) {
                                const [h, m] = prayer.time.split(':');
                                const prayerTime = new Date();
                                prayerTime.setHours(parseInt(h), parseInt(m), 0);
                                if (prayerTime > now) {
                                    const diff = prayerTime - now;
                                    const hours = Math.floor(diff / 3600000);
                                    const minutes = Math.floor((diff % 3600000) / 60000);
                                    this.nextPrayer = {
                                        name: prayer.name,
                                        time: prayer.time,
                                        remaining: `${hours} jam ${minutes} menit`
                                    };
                                    break;
                                }
                            }
                            if (!this.nextPrayer.name) {
                                this.nextPrayer = { name: 'Subuh', time: schedule.subuh, remaining: 'Besok' };
                            }
                        }
                    } catch (error) {
                        console.error('Error loading prayer times:', error);
                        this.nextPrayer = { name: 'Dzuhur', time: '12:00', remaining: '--' };
                    }
                    this.loading = false;
                }
            };
        }

        function randomHadis() {
            return {
                hadis: null,
                loading: true,
                async loadHadis() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/api/muslim/hadis/random?t=${Date.now()}`);
                        if (!response.ok) throw new Error('API error');
                        const data = await response.json();
                        if (data && (data.text?.id || data.id)) {
                            this.hadis = data;
                        } else {
                            throw new Error('Invalid data');
                        }
                    } catch (error) {
                        console.error('Error loading hadis:', error);
                        this.hadis = {
                            text: { id: 'Sesungguhnya amal-amal itu tergantung pada niatnya.' },
                            takhrij: 'HR. Bukhari & Muslim',
                            grade: 'Shahih'
                        };
                    }
                    this.loading = false;
                }
            };
        }

        function dailyVerse() {
            return {
                verse: null,
                loading: true,
                async loadVerse() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/api/muslim/quran/random?t=${Date.now()}`);
                        if (!response.ok) throw new Error('API error');
                        const data = await response.json();
                        if (data && (data.arab || data.teks_arab)) {
                            this.verse = {
                                arab: data.arab || data.teks_arab || '',
                                translation: data.translation || data.teks_indonesia || '',
                                reference: `QS. ${data.surah?.name_latin || data.surah || ''}: ${data.ayah_number || data.ayat || ''}`
                            };
                        } else {
                            throw new Error('Invalid data');
                        }
                    } catch (error) {
                        console.error('Error loading verse:', error);
                        this.verse = {
                            arab: 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                            translation: 'Dengan nama Allah Yang Maha Pengasih, Maha Penyayang',
                            reference: 'QS. Al-Fatihah: 1'
                        };
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
