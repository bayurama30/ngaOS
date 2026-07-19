# Changelog

All notable changes to NgaOS - Islamic Web App will be documented in this file.

## [1.0.3] - 2026-07-19

### 🐛 Bug Fixes

#### AI Chatbot
- Fix chat history not persisting after page refresh
- Fix quota (10/day) now properly per-user
- Add cache-busting to history API requests
- Add proper error handling for API responses
- Fix remaining count synchronization

#### Auth System
- Fix login error messages not displaying
- Fix registration form submission
- Add proper Livewire validation with `rules()` method
- Switch to Blade + Controller approach for stability

#### Forgot Password
- Implement OTP-based password reset
- OTP sent to email with 10-minute expiry
- Multi-step flow: Email → OTP → New Password

#### Service Worker
- Fix `addAll` error in service worker
- Improve caching strategy (network-first for HTML)

#### cPanel Deployment Fixes
- Delete `public/create-user.php` (security risk + wrong paths)
- Fix PrayerTimeController - remove broken methods not used in routes
- Fix deploy.sh - create required directories before artisan commands
- Fix deploy.sh - set permissions before running artisan commands
- Fix deploy.sh - use `ln -sf` instead of `php artisan storage:link` (exec disabled)
- Add placeholder PWA icons (72px-512px)
- Add placeholder screenshot
- Update .env.example with API keys
- Update .env.production with database credentials

### ✨ New Features

#### AI Chatbot
- **Rate Limiting** - 10 chats per day per user
- **Character Limit** - 500 characters max per message
- **Remaining Counter** - Shows remaining chats in header

#### Location Detection
- **Auto GPS Detection** - Detect city via reverse geocoding
- **Auto Timezone** - WIB/WITA/WIT based on coordinates
- **City Search** - Search 517+ Indonesian cities

#### PWA
- **Placeholder Icons** - PWA icons for all sizes (72px-512px)
- **Screenshot** - PWA screenshot placeholder

---

## [1.0.2] - 2026-07-16

### ✨ New Features

#### Al-Quran
- **Pengaturan Audio** - Pilihan mode Normal atau Word-by-Word
- **Font Settings** - Pilihan font Arab (LPMQ IsepMisbah, Amiri Quran, dll)
- **Ukuran Font** - Slider 80% - 250% dengan preview dinamis
- **Huruf Latin** - Toggle transliterasi Arab ke Latin
- **Tandai Ayat** - Bookmark ayat dengan timestamp
- **Halaman Ayat Ditandai** - Navigasi langsung ke ayat
- **Navigasi Surah** - Tombol prev/next + swipe/drag gesture
- **Pull-to-Refresh** - Scroll paksa untuk pindah surah
- **Audio Seamless** - Lookahead buffering tanpa jeda antar ayat

#### Home Page
- **Ayat Hari Ini** - Tombol refresh manual
- **Hadis Hari Ini** - Tombol refresh manual
- **Kalender Hijriah** - Tanggal Hijriah otomatis

### 🐛 Bug Fixes

#### Al-Quran
- Fix surah pagination - fetch semua ayat (tidak hanya 10)
- Fix audio playback - perbaikan loading dan error handling
- Fix daily verse refresh - bypass cache untuk random ayah
- Fix route order - search routes sebelum parameterized routes

#### Hadis
- Fix hadith search - fetch detail lengkap (grade, takhrij)
- Fix random hadis refresh - cache buster untuk hasil baru
- Fix missing Http import in HadithService

#### UI/UX
- Fix hadith search results showing "Sumber tidak diketahui"
- Fix navigation buttons tidak bisa di-swipe
- Fix color visibility pada halaman koleksi hadis

---

## [1.0.1] - 2026-07-16

### 🐛 Bug Fixes

#### Al-Quran
- Fix surah pagination - fetch all ayahs for each surah (not just 10)
- Fix audio playback - improve audio loading and error handling
- Fix single ayah play to support both audio modes

#### Hadis
- Fix route order - search routes before parameterized routes
- Fix hadith search - fetch full details including grade and takhrij
- Fix random hadis refresh - add cache buster to API calls
- Fix missing Http import in HadithService

#### UI/UX
- Fix hadith search results showing "Sumber tidak diketahui"
- Improve hadith search UI with better styling and quick search buttons

---

## [1.0.0] - 2026-07-16

### 🎉 Initial Release

#### ✨ Al-Quran
- 114 Surat lengkap dengan terjemahan Bahasa Indonesia
- Tafsir Kemenag per ayat
- Audio Murottal per surat (Muhammad Thaha)
- **Audio Word-by-Word** - Sinkronisasi highlight per kata dengan audio
- **Audio Normal** - Mode murottal biasa
- Pengaturan tampilan:
  - Pilihan font Arab (LPMQ IsepMisbah, Amiri Quran, dll)
  - Ukuran font 80% - 250%
  - Toggle huruf Latin (transliterasi)
  - Toggle terjemahan
  - Toggle tafsir
  - Auto-scroll saat audio
- **Tandai Ayat** - Bookmark ayat favorit dengan timestamp
- Halaman "Ayat Ditandai" dengan navigasi langsung ke ayat
- Pencarian surat
- Play/Pause per ayat dan full surah

#### 📚 Ensiklopedia Hadis
- Koleksi dari API Muslim (Kemenag)
- Pengelompokan berdasarkan Mukharrij:
  - Muttafaq 'alaih (Bukhari & Muslim)
  - Sahih Bukhari
  - Sahih Muslim
  - Jami' at-Tirmidzi
  - Sunan Abu Daud
  - Musnad Ahmad
- Detail hadis: teks Arab, terjemahan, grade, sumber takhrij
- Pencarian hadis berdasarkan kata kunci
- Navigasi sebelumnya/selanjutnya
- Hadis acak di halaman utama

#### 🕐 Jadwal Sholat
- Data dari Kementerian Agama Indonesia
- Deteksi lokasi otomatis via GPS
- Jadwal lengkap: Imsak, Subuh, Terbit, Dhuha, Dzuhur, Ashar, Maghrib, Isya
- Zona waktu otomatis (WIB/WITA/WIT)
- Pencarian kota/kabupaten di Indonesia

#### 🧭 Arah Kiblat
- Kompas kiblat berdasarkan koordinat GPS
- Jarak ke Ka'bah
- Tampilan kompas interaktif

#### 💬 Forum Komunitas
- Posting dengan kategori (Umum, Tafsir, Hadis, Doa, Fiqih)
- Sistem komentar
- Sistem like/unlike
- Upload gambar

#### 🤖 AI Chatbot (NgaOS AI)
- Powered by DeepSeek V4 Flash (OpenCode Zen)
- Jawaban berdasarkan dalil Al-Quran dan Hadis
- Quick prompts untuk pertanyaan umum
- History percakapan
- Reset sesi
- Format markdown (bold, italic, kutipan)

#### 👤 Profil & Pengaturan
- Deteksi lokasi otomatis via GPS
- Pencarian kota dengan autocomplete
- Pengaturan zona waktu
- Upload foto profil

#### 📱 PWA (Progressive Web App)
- Install sebagai app di mobile
- Service worker untuk offline cache
- Manifest.json dengan ikon

#### 🎨 UI/UX
- Mobile-first design
- Bottom navigation bar
- Dark mode ready
- Font Arab LPMQ IsepMisbah
- Smooth animations dan transitions

#### 🔧 Tech Stack
- Laravel 13
- Livewire 3
- Tailwind CSS
- Alpine.js
- MySQL/SQLite
- Vite

#### 🌐 API Integration
- **API Muslim** (api.myquran.com) - Quran, Hadis, Jadwal Sholat, Kiblat, Kalender Hijriah
- **Quran.com API** - Word-by-word audio
- **OpenCode Zen** - AI Chatbot (DeepSeek V4 Flash Free)
- **OpenStreetMap** - Reverse geocoding

---

## Links

- **Repository:** https://github.com/bayurama30/ngaOS
- **API Muslim:** https://api.myquran.com
- **Quran.com API:** https://api.quran.com
- **OpenCode Zen:** https://opencode.ai/zen
