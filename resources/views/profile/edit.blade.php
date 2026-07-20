<x-app-layout>
    <div class="max-w-[620px] mx-auto px-4 py-6" x-data="profileSettings()">
        <div class="mb-6 animate-fade-in">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Profil</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Pengaturan akun Anda</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="glass-card p-5 animate-slide-up">
                <div class="flex items-center mb-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center mr-4 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="input text-sm">
                </div>
            </div>

            <div class="glass-card p-5 space-y-4 animate-slide-up">
                <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Dasar</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="input">
                </div>
            </div>

            <div class="glass-card p-5 space-y-4 animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Lokasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Untuk jadwal sholat yang akurat</p>
                    </div>
                    <button type="button" @click="detectLocation()" :disabled="detecting" class="btn-primary flex items-center gap-2 text-sm disabled:opacity-50">
                        <svg x-show="!detecting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="detecting" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="detecting ? 'Mendeteksi...' : 'Deteksi Lokasi'"></span>
                    </button>
                </div>

                <div x-show="detectStatus" x-cloak :class="detectStatusType === 'success' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300'" class="border rounded-xl p-3 text-sm" x-text="detectStatus"></div>

                <input type="hidden" name="location_lat" :value="lat">
                <input type="hidden" name="location_lng" :value="lng">
                <input type="hidden" name="city_id" :value="selectedCityId">
                <input type="hidden" name="city" :value="selectedCityName">
                <input type="hidden" name="timezone" :value="detectedTimezone">

                <div x-show="selectedCityName" x-cloak class="glass-card bg-teal-50 dark:bg-teal-900/20 p-4 border border-teal-200 dark:border-teal-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-teal-800 dark:text-teal-200" x-text="selectedCityName"></p>
                            <p class="text-sm text-teal-600 dark:text-teal-400" x-text="'Zona Waktu: ' + detectedTimezone"></p>
                        </div>
                        <button type="button" @click="clearLocation()" class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="!selectedCityName && !detecting" x-cloak class="glass-card p-4 text-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Klik "Deteksi Lokasi" untuk mengatur lokasi otomatis</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Atau cari kota manual:</p>
                    <div class="relative mt-1">
                        <input type="text" x-model="citySearch" @input.debounce.500ms="searchCity()" placeholder="Cari kota/kabupaten..." class="input text-sm">
                        
                        <div x-show="searchResults.length > 0" x-cloak class="absolute z-10 w-full mt-1 glass-modal max-h-60 overflow-y-auto">
                            <template x-for="city in searchResults" :key="city.id">
                                <button type="button" @click="selectCity(city)" class="w-full text-left px-4 py-3 hover:bg-teal-50 dark:hover:bg-teal-900/20 border-b border-gray-100 dark:border-gray-800 last:border-0 transition">
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm" x-text="city.lokasi"></p>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full btn-primary py-3">
                Simpan Perubahan
            </button>
        </form>

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full btn-danger py-3">
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

                        const cityName = await this.reverseGeocode(position.coords.latitude, position.coords.longitude);
                        
                        if (cityName) {
                            await this.findCityInDatabase(cityName);
                        }

                        if (this.selectedCityName) {
                            this.detectStatus = `Lokasi terdeteksi: ${this.selectedCityName}`;
                            this.detectStatusType = 'success';
                        } else {
                            this.detectStatus = 'Kota terdeteksi: ' + (cityName || 'tidak diketahui') + '. Pilih manual dari daftar di bawah.';
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

                async reverseGeocode(lat, lng) {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&addressdetails=1`, {
                            headers: {
                                'Accept-Language': 'id'
                            }
                        });
                        const data = await response.json();
                        
                        const city = data.address?.city || 
                                     data.address?.regency || 
                                     data.address?.county ||
                                     data.address?.state || 
                                     '';
                        
                        return city;
                    } catch (error) {
                        console.error('Reverse geocode error:', error);
                        return '';
                    }
                },

                async findCityInDatabase(cityName) {
                    try {
                        const keywords = [
                            cityName,
                            cityName.replace('KOTA ', '').replace('KAB. ', '').replace('KABUPATEN ', ''),
                            cityName.split(' ').pop()
                        ];

                        for (const keyword of keywords) {
                            if (!keyword || keyword.length < 3) continue;

                            const response = await fetch(`/api/muslim/city/search?q=${encodeURIComponent(keyword)}`);
                            const data = await response.json();

                            if (Array.isArray(data) && data.length > 0) {
                                const match = data.find(c => 
                                    c.lokasi.toUpperCase().includes(cityName.toUpperCase()) ||
                                    cityName.toUpperCase().includes(c.lokasi.toUpperCase())
                                ) || data[0];

                                this.selectedCityId = match.id;
                                this.selectedCityName = match.lokasi;
                                return;
                            }
                        }
                    } catch (error) {
                        console.error('Error finding city:', error);
                    }
                },

                getTimezoneFromCoords(lat, lng) {
                    if (lng >= 140) return 'Asia/Jayapura';
                    if (lng >= 120) return 'Asia/Makassar';
                    return 'Asia/Jakarta';
                },

                async searchCity() {
                    if (this.citySearch.length < 3) {
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
                    this.detectStatus = '';
                },

                clearLocation() {
                    this.selectedCityId = '';
                    this.selectedCityName = '';
                    this.lat = '';
                    this.lng = '';
                    this.detectedTimezone = 'Asia/Jakarta';
                    this.detectStatus = '';
                }
            };
        }
    </script>
</x-app-layout>
