<x-app-layout>
    <div class="px-4 py-6">
        {{-- Welcome Section --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Assalamu'alaikum</h2>
            @auth
                <p class="text-gray-600 mt-1">{{ auth()->user()->name }}</p>
            @else
                <p class="text-gray-600 mt-1">Selamat datang di NgaOS</p>
            @endauth
        </div>

        {{-- Prayer Time Widget --}}
        <div x-data="prayerWidget()" x-init="loadPrayerTimes()" class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-5 mb-6 text-white shadow-lg">
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
        <div class="grid grid-cols-3 gap-3 mb-6">
            <a href="{{ route('quran.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Al-Quran</span>
            </a>

            <a href="{{ route('hadith.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Hadis</span>
            </a>

            <a href="{{ route('qibla.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Kiblat</span>
            </a>

            <a href="{{ route('prayer.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Jadwal</span>
            </a>

            <a href="{{ route('forum.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Forum</span>
            </a>

            <a href="{{ route('chatbot.index') }}" class="flex flex-col items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">AI Chat</span>
            </a>
        </div>

        {{-- Daily Verse --}}
        <div class="bg-white rounded-xl p-5 mb-6 shadow-sm border border-gray-100" x-data="dailyVerse()" x-init="loadVerse()">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Ayat Hari Ini</h3>
            <div x-show="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
            </div>
            <div x-show="!loading && verse" class="space-y-3">
                <p class="font-arabic text-2xl text-right leading-loose text-gray-800" x-text="verse?.arabic"></p>
                <p class="text-gray-600 text-sm italic" x-text="verse?.translation"></p>
                <p class="text-teal-600 text-sm font-medium" x-text="verse?.reference"></p>
            </div>
        </div>

        {{-- Latest Forum Posts --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Forum Terbaru</h3>
                <a href="{{ route('forum.index') }}" class="text-teal-600 text-sm font-medium">Lihat Semua</a>
            </div>
            @php
                $latestPosts = \App\Models\Post::with('user')->latest()->take(3)->get();
            @endphp
            @forelse($latestPosts as $post)
                <a href="{{ route('forum.show', $post) }}" class="block bg-white rounded-xl p-4 mb-3 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center mr-2">
                            <span class="text-teal-600 text-sm font-bold">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $post->user->name }}</span>
                        <span class="text-xs text-gray-400 ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1">{{ $post->title }}</h4>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($post->body, 100) }}</p>
                    <div class="flex items-center mt-3 text-xs text-gray-500 space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ $post->likes_count }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->comments_count }}
                        </span>
                        <span class="bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full">{{ $post->category }}</span>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <p class="text-gray-500">Belum ada postingan</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function prayerWidget() {
            return {
                nextPrayer: {},
                location: 'Mendeteksi lokasi...',
                date: '',
                loading: true,

                async loadPrayerTimes() {
                    try {
                        const position = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, {
                                enableHighAccuracy: true,
                                timeout: 10000
                            });
                        });

                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        this.location = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;

                        const response = await fetch(`/api/prayer/next?lat=${lat}&lng=${lng}`);
                        const data = await response.json();

                        if (data.name) {
                            this.nextPrayer = data;
                            const tz = data.timezone || 'Asia/Jakarta';
                            const now = new Date();
                            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: tz };
                            this.date = now.toLocaleDateString('id-ID', options);
                        } else {
                            this.nextPrayer = { name: 'Dhuhr', time: '12:00', remaining: '--' };
                            this.date = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        }
                    } catch (error) {
                        console.error('Error loading prayer times:', error);
                        this.location = 'Lokasi tidak tersedia';
                        this.nextPrayer = { name: 'Dhuhr', time: '12:00', remaining: '--' };
                        this.date = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
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
                    try {
                        const surah = Math.floor(Math.random() * 114) + 1;
                        const response = await fetch(`https://api.alquran.cloud/v1/surah/${surah}/editions`);
                        const data = await response.json();

                        if (data.code === 200 && data.data) {
                            const arabic = data.data[0];
                            const translation = data.data[1];
                            const ayahNum = Math.floor(Math.random() * arabic.numberOfAyahs);

                            this.verse = {
                                arabic: arabic.ayahs[ayahNum].text,
                                translation: translation.ayahs[ayahNum].text,
                                reference: `${arabic.englishName} (${arabic.number}:${ayahNum + 1})`
                            };
                        }
                    } catch (error) {
                        console.error('Error loading verse:', error);
                        this.verse = {
                            arabic: 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                            translation: 'Dengan nama Allah Yang Maha Pengasih, Maha Penyayang',
                            reference: 'Al-Fatihah (1:1)'
                        };
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</x-app-layout>
