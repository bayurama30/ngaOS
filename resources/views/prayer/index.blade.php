<x-app-layout>
    <div class="max-w-[620px] mx-auto px-4 py-6" x-data="prayerTimes()" x-init="loadPrayerTimes()">
        <div class="mb-6 animate-fade-in">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Jadwal Sholat</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1" x-text="date"></p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="timezone"></p>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 dark:border-teal-400 mx-auto"></div>
            <p class="text-gray-500 dark:text-gray-400 mt-3">Memuat jadwal...</p>
        </div>

        <div x-show="!loading && !cityId" x-cloak class="glass-card p-4 text-center">
            <p class="text-amber-600 dark:text-amber-400 mb-2">Kota belum diatur</p>
            <a href="{{ route('profile.edit') }}" class="text-teal-600 dark:text-teal-400 font-medium text-sm hover:underline">Atur di Profil</a>
        </div>

        <div x-show="!loading && cityId && error" x-cloak class="glass-card p-4 text-center">
            <p class="text-red-600 dark:text-red-400" x-text="error"></p>
            <button @click="loadPrayerTimes()" class="mt-2 text-teal-600 dark:text-teal-400 font-medium hover:underline">Coba Lagi</button>
        </div>

        <div x-show="!loading && cityId && !error" x-cloak>
            <div class="glass-card bg-gradient-to-br from-teal-600 to-teal-700 dark:from-teal-700 dark:to-teal-800 p-5 mb-6 text-white text-center shadow-lg shadow-teal-500/25 animate-slide-up">
                <p class="text-teal-100 text-sm">Solat Berikutnya</p>
                <h3 class="text-3xl font-bold mt-1" x-text="nextPrayer.name"></h3>
                <p class="text-4xl font-bold mt-2" x-text="nextPrayer.time"></p>
                <p class="text-teal-100 mt-1" x-text="nextPrayer.remaining"></p>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Imsak</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.imsak || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800 bg-teal-50 dark:bg-teal-900/20">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-teal-700 dark:text-teal-300">Subuh</span>
                    </div>
                    <span class="text-lg text-teal-700 dark:text-teal-300 font-bold" x-text="schedule.subuh || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Terbit</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.terbit || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Dhuha</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.dhuha || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Dzuhur</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.dzuhur || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Ashar</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.ashar || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Maghrib</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.maghrib || '--:--'"></span>
                </div>
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Isya</span>
                    </div>
                    <span class="text-lg text-gray-600 dark:text-gray-400" x-text="schedule.isya || '--:--'"></span>
                </div>
            </div>

            <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400" x-text="location"></div>
        </div>
    </div>

    <script>
        function prayerTimes() {
            return {
                schedule: {},
                nextPrayer: {},
                loading: true,
                error: null,
                date: '',
                timezone: '',
                location: '',
                cityId: '{{ auth()->user()->city_id ?? "" }}',

                async loadPrayerTimes() {
                    if (!this.cityId) {
                        this.loading = false;
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`/api/muslim/prayer?city_id=${this.cityId}&tz={{ auth()->user()->timezone ?? "Asia/Jakarta" }}`);
                        const data = await response.json();

                        if (data?.jadwal) {
                            this.schedule = data.jadwal;
                            this.location = `${data.kabko}, ${data.prov}`;
                            this.date = data.jadwal.tanggal;
                            this.timezone = 'Zona Waktu: {{ auth()->user()->timezone ?? "Asia/Jakarta" }}';

                            this.calculateNextPrayer();
                        } else {
                            this.error = 'Gagal memuat jadwal sholat';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error = 'Terjadi kesalahan';
                    }
                    this.loading = false;
                },

                calculateNextPrayer() {
                    const now = new Date();
                    const prayers = [
                        { name: 'Subuh', time: this.schedule.subuh },
                        { name: 'Terbit', time: this.schedule.terbit },
                        { name: 'Dhuha', time: this.schedule.dhuha },
                        { name: 'Dzuhur', time: this.schedule.dzuhur },
                        { name: 'Ashar', time: this.schedule.ashar },
                        { name: 'Maghrib', time: this.schedule.maghrib },
                        { name: 'Isya', time: this.schedule.isya },
                    ];

                    for (const prayer of prayers) {
                        if (!prayer.time) continue;

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
                            return;
                        }
                    }

                    this.nextPrayer = {
                        name: 'Subuh',
                        time: this.schedule.subuh,
                        remaining: 'Besok'
                    };
                }
            };
        }
    </script>
</x-app-layout>
