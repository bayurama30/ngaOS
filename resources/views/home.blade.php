<x-app-layout>
    <div class="px-4 py-6">
        {{-- Welcome Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Assalamu'alaikum</h2>
                    @auth
                        <p class="text-gray-600 mt-1">{{ auth()->user()->name }}</p>
                    @else
                        <p class="text-gray-600 mt-1">Selamat datang di NgaOS</p>
                    @endauth
                </div>
                <div x-data="hijriDate()" x-init="loadHijriDate()" class="text-right">
                    <p class="text-xs text-gray-500" x-text="hijri.date || ''"></p>
                    <p class="text-xs text-teal-600 font-medium" x-text="hijri.dayName || ''"></p>
                </div>
            </div>
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

        {{-- Random Hadis --}}
        <div class="bg-white rounded-xl p-5 mb-6 shadow-sm border border-gray-100" x-data="randomHadis()" x-init="loadHadis()">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold text-gray-800">Hadis Hari Ini</h3>
                <button @click="loadHadis()" class="text-teal-600 hover:text-teal-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div x-show="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
            </div>
            <div x-show="!loading && hadis" class="space-y-3">
                <p class="text-gray-700 text-sm leading-relaxed" x-text="hadis?.text?.id || ''"></p>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500" x-text="hadis?.takhrij || ''"></span>
                    <span :class="[hadis?.grade === 'Shahih' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700', 'px-2 py-0.5 rounded-full']" x-text="hadis?.grade || ''"></span>
                </div>
            </div>
        </div>

        {{-- Daily Verse --}}
        <div class="bg-white rounded-xl p-5 mb-6 shadow-sm border border-gray-100" x-data="dailyVerse()" x-init="loadVerse()">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Ayat Hari Ini</h3>
            <div x-show="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
            </div>
            <div x-show="!loading && verse" class="space-y-3">
                <p class="text-2xl text-right leading-loose text-gray-800" style="font-family: 'LPMQ IsepMisbah', serif" x-text="verse?.arab"></p>
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
                async loadPrayerTimes() {
                    try {
                        const response = await fetch('/api/muslim/prayer?city_id={{ auth()->user()->city_id ?? "" }}&tz={{ auth()->user()->timezone ?? "Asia/Jakarta" }}');
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
                        const response = await fetch('/api/muslim/hadis/random');
                        const data = await response.json();
                        this.hadis = data;
                    } catch (error) {
                        console.error('Error loading hadis:', error);
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
                        const response = await fetch('/api/muslim/quran/random');
                        const data = await response.json();
                        if (data) {
                            this.verse = {
                                arab: data.arab || '',
                                translation: data.translation || '',
                                reference: `${data.surah?.name_latin || ''} (${data.surah_number || ''}:${data.ayah_number || ''})`
                            };
                        }
                    } catch (error) {
                        console.error('Error loading verse:', error);
                        this.verse = {
                            arab: 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
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
