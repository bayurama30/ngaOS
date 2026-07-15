<x-app-layout>
    <div class="px-4 py-6" x-data="prayerTimes()" x-init="loadPrayerTimes()">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jadwal Solat</h2>
            <p class="text-gray-600 mt-1" x-text="date"></p>
            <p class="text-xs text-gray-400 mt-1" x-text="timezone"></p>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Mendeteksi lokasi...</p>
        </div>

        <div x-show="!loading && error" class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-red-600" x-text="error"></p>
            <button @click="loadPrayerTimes()" class="mt-2 text-teal-600 font-medium">Coba Lagi</button>
        </div>

        <div x-show="!loading && !error">
            <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-5 mb-6 text-white text-center">
                <p class="text-teal-100 text-sm">Solat Berikutnya</p>
                <h3 class="text-3xl font-bold mt-1" x-text="nextPrayer.name"></h3>
                <p class="text-4xl font-bold mt-2" x-text="nextPrayer.time"></p>
                <p class="text-teal-100 mt-1" x-text="nextPrayer.remaining"></p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <template x-for="(prayer, index) in prayers" :key="index">
                    <div :class="['flex items-center justify-between p-4', index !== prayers.length - 1 ? 'border-b border-gray-100' : '']">
                        <div class="flex items-center">
                            <div :class="['w-10 h-10 rounded-full flex items-center justify-center mr-3', prayer.name === nextPrayer.name ? 'bg-teal-100' : 'bg-gray-100']">
                                <svg :class="['w-5 h-5', prayer.name === nextPrayer.name ? 'text-teal-600' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span :class="['font-medium', prayer.name === nextPrayer.name ? 'text-teal-700' : 'text-gray-700']" x-text="prayer.name"></span>
                        </div>
                        <span :class="['text-lg', prayer.name === nextPrayer.name ? 'text-teal-700 font-bold' : 'text-gray-600']" x-text="prayer.time"></span>
                    </div>
                </template>
            </div>

            <div class="mt-4 text-center text-sm text-gray-500" x-text="location"></div>
        </div>
    </div>

    <script>
        function prayerTimes() {
            return {
                prayers: [],
                nextPrayer: {},
                loading: true,
                error: null,
                date: '',
                timezone: '',
                location: '',

                async loadPrayerTimes() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const position = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, {
                                enableHighAccuracy: true,
                                timeout: 10000
                            });
                        });

                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        this.location = `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;

                        const [timingsRes, nextRes] = await Promise.all([
                            fetch(`/api/prayer/timings?lat=${lat}&lng=${lng}`),
                            fetch(`/api/prayer/next?lat=${lat}&lng=${lng}`)
                        ]);

                        const timingsData = await timingsRes.json();
                        const nextData = await nextRes.json();

                        if (timingsData.timings) {
                            const tz = timingsData.meta?.timezone || 'UTC';
                            this.timezone = `Zona Waktu: ${tz}`;

                            const now = new Date();
                            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: tz };
                            this.date = now.toLocaleDateString('id-ID', options);

                            this.prayers = [
                                { name: 'Subuh', time: this.formatTime(timingsData.timings.Fajr) },
                                { name: 'Terbit', time: this.formatTime(timingsData.timings.Sunrise) },
                                { name: 'Dzuhur', time: this.formatTime(timingsData.timings.Dhuhr) },
                                { name: 'Ashar', time: this.formatTime(timingsData.timings.Asr) },
                                { name: 'Maghrib', time: this.formatTime(timingsData.timings.Maghrib) },
                                { name: 'Isya', time: this.formatTime(timingsData.timings.Isha) },
                            ];
                        }

                        this.nextPrayer = nextData;
                    } catch (error) {
                        console.error('Error:', error);
                        this.error = 'Gagal mendeteksi lokasi. Pastikan GPS aktif.';
                    }
                    this.loading = false;
                },

                formatTime(time) {
                    if (!time) return '--:--';
                    return time.substring(0, 5);
                }
            };
        }
    </script>
</x-app-layout>
