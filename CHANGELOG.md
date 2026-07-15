# Changelog

All notable changes to NgaOS - Islamic Web App will be documented in this file.

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
