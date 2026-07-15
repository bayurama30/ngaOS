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
                        <h3 class="font-semibold text-gray-800">Lokasi & Kota</h3>
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
                        <span x-text="detecting ? 'Mendeteksi...' : 'Deteksi Otomatis'"></span>
                    </button>
                </div>

                <div x-show="detectStatus" :class="detectStatusType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'" class="border rounded-lg p-3 text-sm" x-text="detectStatus"></div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="location_lat" x-model="lat" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="-6.2088">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="location_lng" x-model="lng" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="106.8456">
                    </div>
                </div>

                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten</label>
                    <input type="text" x-model="citySearch" @input.debounce.300ms="searchCity()" placeholder="Cari kota..." class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <input type="hidden" name="city_id" :value="selectedCityId">
                    <input type="hidden" name="city" :value="selectedCityName">
                    
                    <div x-show="searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="city in searchResults" :key="city.id">
                            <button type="button" @click="selectCity(city)" class="w-full text-left px-4 py-3 hover:bg-teal-50 border-b border-gray-100 last:border-0">
                                <p class="font-medium text-gray-800" x-text="city.lokasi"></p>
                            </button>
                        </template>
                    </div>
                </div>
                
                <div x-show="selectedCityName" class="bg-teal-50 rounded-lg p-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-teal-800" x-text="selectedCityName"></p>
                        <p class="text-xs text-teal-600">Kota terpilih</p>
                    </div>
                    <button type="button" @click="clearCity()" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Zona Waktu</h3>
                    <span x-show="detectedTimezone" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full" x-text="'Terdeteksi: ' + detectedTimezone"></span>
                </div>
                <select name="timezone" x-model="selectedTimezone" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(config('muslim.timezones') as $tz => $label)
                        <option value="{{ $tz }}">{{ $label }}</option>
                    @endforeach
                </select>
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
                citySearch: '{{ $user->city ?? "" }}',
                selectedCityId: '{{ $user->city_id ?? "" }}',
                selectedCityName: '{{ $user->city ?? "" }}',
                selectedTimezone: '{{ $user->timezone ?? "Asia/Jakarta" }}',
                lat: '{{ $user->location_lat ?? "" }}',
                lng: '{{ $user->location_lng ?? "" }}',
                searchResults: [],
                searching: false,
                detecting: false,
                detectStatus: '',
                detectStatusType: '',
                detectedTimezone: '',

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
                        this.selectedTimezone = this.detectedTimezone;

                        await this.findNearestCity(position.coords.latitude, position.coords.longitude);

                        this.detectStatus = 'Lokasi berhasil dideteksi!';
                        this.detectStatusType = 'success';
                    } catch (error) {
                        console.error('Error detecting location:', error);
                        this.detectStatus = 'Gagal mendeteksi lokasi: ' + error.message;
                        this.detectStatusType = 'error';
                    }

                    this.detecting = false;
                },

                getTimezoneFromCoords(lat, lng) {
                    if (lng >= 95 && lng < 105) return 'Asia/Jakarta';
                    if (lng >= 105 && lng < 120) return 'Asia/Jakarta';
                    if (lng >= 120 && lng < 135) return 'Asia/Makassar';
                    if (lng >= 135) return 'Asia/Jayapura';
                    
                    if (lat >= -11 && lat <= 6 && lng >= 95 && lng <= 141) {
                        if (lng < 108) return 'Asia/Jakarta';
                        if (lng < 125) return 'Asia/Makassar';
                        return 'Asia/Jayapura';
                    }

                    return 'Asia/Jakarta';
                },

                async findNearestCity(lat, lng) {
                    try {
                        const latStr = lat.toFixed(0);
                        const searchTerms = [];
                        
                        if (lat < -5) searchTerms.push('selatan');
                        else if (lat > 0) searchTerms.push('utara');
                        
                        const response = await fetch(`/api/muslim/city/search?q=${encodeURIComponent(latStr)}`);
                        const data = await response.json();
                        
                        if (Array.isArray(data) && data.length > 0) {
                            const city = data[0];
                            this.selectedCityId = city.id;
                            this.selectedCityName = city.lokasi;
                            this.citySearch = city.lokasi;
                        }
                    } catch (error) {
                        console.error('Error finding nearest city:', error);
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
                        console.error('Error searching city:', error);
                        this.searchResults = [];
                    }
                    this.searching = false;
                },

                selectCity(city) {
                    this.selectedCityId = city.id;
                    this.selectedCityName = city.lokasi;
                    this.citySearch = city.lokasi;
                    this.searchResults = [];
                },

                clearCity() {
                    this.selectedCityId = '';
                    this.selectedCityName = '';
                    this.citySearch = '';
                }
            };
        }
    </script>
</x-app-layout>
