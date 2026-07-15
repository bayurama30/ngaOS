<x-app-layout>
    <div class="px-4 py-6" x-data="qiblaDirection()" x-init="loadQibla()">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Arah Kiblat</h2>
            <p class="text-gray-600 mt-1">Arah menuju Ka'bah</p>
        </div>

        <div x-show="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-teal-600 mx-auto"></div>
            <p class="text-gray-500 mt-3">Mendeteksi lokasi...</p>
        </div>

        <div x-show="!loading && error" class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-red-600" x-text="error"></p>
            <button @click="loadQibla()" class="mt-2 text-teal-600 font-medium">Coba Lagi</button>
        </div>

        <div x-show="!loading && !error" class="flex flex-col items-center">
            <div class="relative w-64 h-64 mb-8">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-teal-100 rounded-full border-4 border-teal-200"></div>
                <div class="absolute inset-4 bg-white rounded-full shadow-inner"></div>
                
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-teal-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        <p class="text-3xl font-bold text-teal-700" x-text="`${direction}°`"></p>
                        <p class="text-sm text-gray-500 mt-1">dari Utara</p>
                    </div>
                </div>

                <div class="absolute top-2 left-1/2 transform -translate-x-1/2 text-xs font-bold text-gray-500">U</div>
                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-xs font-bold text-gray-500">S</div>
                <div class="absolute left-2 top-1/2 transform -translate-y-1/2 text-xs font-bold text-gray-500">B</div>
                <div class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs font-bold text-gray-500">T</div>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div :style="`transform: rotate(${direction}deg)`" class="w-1 h-24 bg-gradient-to-b from-teal-600 to-transparent rounded-full origin-bottom"></div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-sm border border-gray-100 text-center">
                <p class="text-gray-600 mb-1">Jarak ke Ka'bah</p>
                <p class="text-2xl font-bold text-teal-700" x-text="`${distance} km`"></p>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Arahkan ponsel ke arah yang ditunjuk</p>
                <p class="mt-1" x-text="location"></p>
            </div>
        </div>
    </div>

    <script>
        function qiblaDirection() {
            return {
                direction: 0,
                distance: 0,
                loading: true,
                error: null,
                location: '',

                async loadQibla() {
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

                        const response = await fetch(`/api/qibla/direction?lat=${lat}&lng=${lng}`);
                        const data = await response.json();

                        if (data.direction) {
                            this.direction = Math.round(data.direction);
                            this.distance = Math.round(data.distance);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error = 'Gagal mendeteksi lokasi. Pastikan GPS aktif.';
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
