<x-app-layout>
    <div class="px-4 py-6">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <input type="text" name="city" value="{{ $user->city }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Jakarta">
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-semibold text-gray-800">Lokasi</h3>
                <p class="text-sm text-gray-500">Digunakan untuk jadwal solat dan arah kiblat</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="location_lat" value="{{ $user->location_lat }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="-6.2088">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="location_lng" value="{{ $user->location_lng }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="106.8456">
                    </div>
                </div>
                <button type="button" onclick="detectLocation()" class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition">
                    Deteksi Lokasi Otomatis
                </button>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Metode Perhitungan Waktu Solat</h3>
                <select name="calculation_method" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="1" {{ $user->calculation_method == 1 ? 'selected' : '' }}>University of Islamic Sciences, Karachi</option>
                    <option value="2" {{ $user->calculation_method == 2 ? 'selected' : '' }}>ISNA (Islamic Society of North America)</option>
                    <option value="3" {{ $user->calculation_method == 3 ? 'selected' : '' }}>Muslim World League</option>
                    <option value="4" {{ $user->calculation_method == 4 ? 'selected' : '' }}>Umm Al-Qura University, Makkah</option>
                    <option value="5" {{ $user->calculation_method == 5 ? 'selected' : '' }}>Egyptian General Authority of Survey</option>
                    <option value="11" {{ $user->calculation_method == 11 ? 'selected' : '' }}>Majlis Ugama Islam Singapura</option>
                    <option value="15" {{ $user->calculation_method == 15 ? 'selected' : '' }}>Kementerian Agama Indonesia</option>
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
        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.querySelector('[name="location_lat"]').value = position.coords.latitude;
                    document.querySelector('[name="location_lng"]').value = position.coords.longitude;
                }, function(error) {
                    alert('Gagal mendeteksi lokasi: ' + error.message);
                });
            } else {
                alert('Browser tidak mendukung geolokasi');
            }
        }
    </script>
</x-app-layout>
