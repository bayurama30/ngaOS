<x-app-layout>
    <div class="px-4 py-6" x-data="profileSettings()">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Profil</h2>
            <p class="text-gray-600 mt-1">Pengaturan akun Anda</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mr-4 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-teal-600 font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-semibold text-gray-800">Informasi Dasar</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">Lokasi</h3>
                        <p class="text-sm text-gray-500">Untuk jadwal sholat yang akurat</p>
                    </div>
                    <button type="button" @click="detectLocation()" :disabled="detecting" class="flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-teal-700 transition disabled:opacity-50">
                        <svg x-show="!detecting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="detecting" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="detecting ? 'Mendeteksi...' : 'Deteksi Lokasi'"></span>
                    </button>
                </div>

                <div x-show="detectStatus" :class="detectStatusType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'" class="border rounded-lg p-3 text-sm" x-text="detectStatus"></div>

                <input type="hidden" name="location_lat" :value="lat">
                <input type="hidden" name="location_lng" :value="lng">
                <input type="hidden" name="city_id" :value="selectedCityId">
                <input type="hidden" name="city" :value="selectedCityName">
                <input type="hidden" name="timezone" :value="detectedTimezone">

                <div x-show="selectedCityName" class="bg-teal-50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-teal-800" x-text="selectedCityName"></p>
                            <p class="text-sm text-teal-600" x-text="'Zona Waktu: ' + detectedTimezone"></p>
                        </div>
                        <button type="button" @click="clearLocation()" class="text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="!selectedCityName && !detecting" class="bg-gray-50 rounded-xl p-4 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">Klik "Deteksi Lokasi" untuk mengatur lokasi otomatis</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">Atau cari kota manual:</p>
                    <div class="relative mt-1">
                        <input type="text" x-model="citySearch" @input.debounce.300ms="searchCity()" placeholder="Cari kota..." class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                        
                        <div x-show="searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="city in searchResults" :key="city.id">
                                <button type="button" @click="selectCity(city)" class="w-full text-left px-4 py-3 hover:bg-teal-50 border-b border-gray-100 last:border-0">
                                    <p class="font-medium text-gray-800 text-sm" x-text="city.lokasi"></p>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition">
                Simpan Perubahan
            </button>
        </form>

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-50 text-red-600 py-3 rounded-xl font-medium hover:bg-red-100 transition">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <script>
        function profileSettings() {
            return {
                citySearch: '',
                selectedCityId: '{{ $user->city_id ?? "" }}',
                selectedCityName: '{{ $user->city ?? "" }}',
                detectedTimezone: '{{ $user->timezone ?? "Asia/Jakarta" }}',
                lat: '{{ $user->location_lat ?? "" }}',
                lng: '{{ $user->location_lng ?? "" }}',
                searchResults: [],
                searching: false,
                detecting: false,
                detectStatus: '',
                detectStatusType: '',

                async detectLocation() {
                    this.detecting = true;
                    this.detectStatus = '';

                    if (!navigator.geolocation) {
                        this.detectStatus = 'Browser tidak mendukung deteksi lokasi';
                        this.detectStatusType = 'error';
                        this.detecting = false;
                        return;
                    }

                    try {
                        const position = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, {
                                enableHighAccuracy: true,
                                timeout: 15000
                            });
                        });

                        this.lat = position.coords.latitude.toFixed(7);
                        this.lng = position.coords.longitude.toFixed(7);
                        this.detectedTimezone = this.getTimezoneFromCoords(position.coords.latitude, position.coords.longitude);

                        await this.findNearestCity(position.coords.latitude, position.coords.longitude);

                        if (this.selectedCityName) {
                            this.detectStatus = `Lokasi terdeteksi: ${this.selectedCityName}`;
                            this.detectStatusType = 'success';
                        } else {
                            this.detectStatus = 'Lokasi terdeteksi, namun kota tidak ditemukan di database. Coba cari manual.';
                            this.detectStatusType = 'error';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (error.code === 1) {
                            this.detectStatus = 'Izin lokasi ditolak. Mohon aktifkan izin lokasi di browser.';
                        } else if (error.code === 2) {
                            this.detectStatus = 'Lokasi tidak tersedia. Pastikan GPS aktif.';
                        } else {
                            this.detectStatus = 'Gagal mendeteksi lokasi. Coba lagi.';
                        }
                        this.detectStatusType = 'error';
                    }

                    this.detecting = false;
                },

                getTimezoneFromCoords(lat, lng) {
                    if (lng >= 140) return 'Asia/Jayapura';
                    if (lng >= 120) return 'Asia/Makassar';
                    return 'Asia/Jakarta';
                },

                async findNearestCity(lat, lng) {
                    try {
                        const cities = await this.loadAllCities();
                        
                        if (!cities || cities.length === 0) return;

                        let nearestCity = null;
                        let minDistance = Infinity;

                        for (const city of cities) {
                            const cityLat = parseFloat(city.lat || 0);
                            const cityLng = parseFloat(city.lng || 0);
                            
                            if (cityLat === 0 && cityLng === 0) continue;

                            const distance = this.calculateDistance(lat, lng, cityLat, cityLng);
                            
                            if (distance < minDistance) {
                                minDistance = distance;
                                nearestCity = city;
                            }
                        }

                        if (nearestCity) {
                            this.selectedCityId = nearestCity.id;
                            this.selectedCityName = nearestCity.lokasi;
                        }
                    } catch (error) {
                        console.error('Error finding nearest city:', error);
                        await this.findCityByKeyword(lat);
                    }
                },

                async loadAllCities() {
                    try {
                        const cacheKey = 'all_cities_cache';
                        const cached = sessionStorage.getItem(cacheKey);
                        
                        if (cached) {
                            return JSON.parse(cached);
                        }

                        const response = await fetch('/api/muslim/city/all');
                        const data = await response.json();
                        
                        if (Array.isArray(data)) {
                            sessionStorage.setItem(cacheKey, JSON.stringify(data));
                            return data;
                        }
                        
                        return [];
                    } catch (error) {
                        console.error('Error loading cities:', error);
                        return [];
                    }
                },

                calculateDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371;
                    const dLat = this.deg2rad(lat2 - lat1);
                    const dLon = this.deg2rad(lon2 - lon1);
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                              Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
                              Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    return R * c;
                },

                deg2rad(deg) {
                    return deg * (Math.PI/180);
                },

                async findCityByKeyword(lat) {
                    try {
                        const keyword = lat < -2 ? 'selatan' : 'utara';
                        const response = await fetch(`/api/muslim/city/search?q=${encodeURIComponent(keyword)}`);
                        const data = await response.json();
                        
                        if (Array.isArray(data) && data.length > 0) {
                            this.selectedCityId = data[0].id;
                            this.selectedCityName = data[0].lokasi;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                },

                async searchCity() {
                    if (this.citySearch.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    this.searching = true;
                    try {
                        const response = await fetch(`/api/muslim/city/search?q=${encodeURIComponent(this.citySearch)}`);
                        const data = await response.json();
                        this.searchResults = Array.isArray(data) ? data : [];
                    } catch (error) {
                        this.searchResults = [];
                    }
                    this.searching = false;
                },

                selectCity(city) {
                    this.selectedCityId = city.id;
                    this.selectedCityName = city.lokasi;
                    this.citySearch = '';
                    this.searchResults = [];
                },

                clearLocation() {
                    this.selectedCityId = '';
                    this.selectedCityName = '';
                    this.lat = '';
                    this.lng = '';
                    this.detectedTimezone = 'Asia/Jakarta';
                }
            };
        }
    </script>
</x-app-layout>
