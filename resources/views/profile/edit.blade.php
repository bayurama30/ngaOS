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
                <h3 class="font-semibold text-gray-800">Kota / Kabupaten</h3>
                <p class="text-sm text-gray-500">Digunakan untuk jadwal sholat yang akurat</p>
                
                <div class="relative">
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
                <h3 class="font-semibold text-gray-800 mb-4">Zona Waktu</h3>
                <select name="timezone" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(config('muslim.timezones') as $tz => $label)
                        <option value="{{ $tz }}" {{ $user->timezone === $tz ? 'selected' : '' }}>{{ $label }}</option>
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
                searchResults: [],
                searching: false,

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
