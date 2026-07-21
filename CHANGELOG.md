# Changelog

All notable changes to NgaOS - Islamic Web App will be documented in this file.

## [2.1.0] - 2026-07-21

### ✨ New Features

#### Kalender Hijriah
- **Interactive Calendar** - Toggle between Masehi and Hijriah views
- **Month Navigation** - Previous/Next month buttons
- **Today Indicator** - Highlight current day in teal
- **Ramadhan Highlighting** - Amber gradient background with moon icon (🌙) for Ramadhan days
- **Friday Highlighting** - Green background for Jumat
- **Date Conversion** - Masehi ↔ Hijriah conversion (Julian Day algorithm)
- **Islamic Holidays** - 10 hari besar Islam (Tahun Baru Islam, Isra Mi'raj, Ramadhan, Idul Fitri, Idul Adha, dll)
- **"Hari Ini" Button** - Quick jump to today's date in calendar

#### Home Page
- **Swipeable Quick Actions** - Horizontal scroll grid like iPhone launcher
- **Kalender Menu** - Added to page 2 of quick actions grid
- **Dot Indicator** - Shows current page below grid

### 🐛 Bug Fixes

#### AI Chatbot Sidebar
- Fix dailyVerse function conflict (rename to sidebarDailyVerse)
- Fix dailyHadith function conflict (rename to sidebarDailyHadith)
- Fix nextPrayer function conflict (rename to sidebarNextPrayer)
- Fixes Ayat/Hadis not showing when logged in

#### Home Page
- Add fallback for Ayat/Hadis when API fails
- Add response.ok check before parsing JSON
- Add data validation for API responses

### 📱 Navigation Updates

#### Mobile Bottom Nav
- Removed Hadist tab (5 tabs: Home, Quran, Solat, Forum, AI)

#### Desktop Sidebar
- Added "Kalender" between Kiblat and Forum

### 📁 Files Added

| File | Description |
|------|-------------|
| `app/Http/Controllers/HijriCalendarController.php` | Controller with calendar, conversion, holidays |
| `resources/views/hijri/index.blade.php` | Interactive Hijri calendar view |

### 📁 Files Modified

| File | Changes |
|------|---------|
| `resources/views/home.blade.php` | Swipeable grid, Kalender menu |
| `resources/views/layouts/app.blade.php` | Navigation updates, sidebar function renames |
| `routes/web.php` | Added /hijri route |
| `routes/api.php` | Added /api/hijri/* endpoints |

---

## [2.0.0] - 2026-07-20

### 🎨 UI/UX Redesign

#### Glassmorphism Design System
- **Glass Cards** - Semi-transparent cards with backdrop blur effect
- **Glass Navigation** - Frosted glass header and bottom navigation
- **Glass Sidebar** - Desktop sidebar with glassmorphism effect
- **Glass Modals** - Modal dialogs with backdrop blur overlay
- **Glass Inputs** - Semi-transparent input fields with blur effect

#### Dark Mode Support
- **Manual Toggle** - Dark mode toggle in header/sidebar
- **Persistent Preference** - Saves dark mode choice to localStorage
- **Full Coverage** - All pages and components support dark mode
- **Color System** - Proper dark mode color palette for all elements

#### Threads.com-Inspired Layout
- **Centered Content** - max-w-[620px] centered feed layout
- **Desktop Sidebar** - Icon+label sidebar (w-64) on lg+ screens
- **Right Sidebar** - Ayat/Hadis Hari Ini, Jadwal Sholat, Profile Summary, Quick Links
- **Mobile Bottom Nav** - Glassmorphism bottom navigation
- **FAB Button** - Floating compose button on Forum page

#### Modern Abstract Logo
- **New Logo** - Abstract geometric shape with Islamic undertone
- **Gradient Colors** - Teal gradient with shadow effect
- **Consistent Usage** - Logo in header, sidebar, guest layout, PWA manifest

#### Animations & Transitions
- **Fade In** - Smooth fade-in animation for page content
- **Slide Up** - Slide-up animation for cards and sections
- **Scale In** - Scale-in animation for interactive elements
- **Hover Effects** - Scale transform on quick action icons
- **Active States** - Scale-down effect on button press

#### Component Updates
- **Primary Button** - Teal gradient with rounded-xl
- **Secondary Button** - Glass card style
- **Danger Button** - Red glass card style
- **Input Fields** - Glass input with backdrop blur
- **Badges** - Color-coded badges (teal, amber, red, green)
- **Cards** - Glass cards with rounded-2xl corners

### 📱 Responsive Design

#### Mobile (< 1024px)
- Single column centered layout
- Fixed bottom navigation with glass effect
- Sticky glass header with dark mode toggle
- Touch-friendly interactions

#### Desktop (≥ 1024px)
- Three-column layout: Sidebar | Feed | Right Panel
- Left sidebar with icon+label navigation
- Center feed with max-w-[620px]
- Right sidebar with quick info

### 🔧 Technical Changes

#### Tailwind Configuration
- Add `darkMode: 'class'` for manual dark mode toggle
- Add glassmorphism colors (glass-light, glass-dark, glass-border)
- Add surface colors (surface-light, surface-dark)
- Add custom animations (fade-in, slide-up, slide-down, scale-in)
- Add backdrop-blur-xs utility

#### CSS Utilities
- Add `.glass` - Base glassmorphism effect
- Add `.glass-card` - Glass card with rounded corners
- Add `.glass-header` - Glass header effect
- Add `.glass-nav` - Glass navigation effect
- Add `.glass-sidebar` - Glass sidebar effect
- Add `.glass-input` - Glass input effect
- Add `.glass-modal` - Glass modal effect
- Add `.btn-primary` - Primary button style
- Add `.btn-secondary` - Secondary button style
- Add `.btn-danger` - Danger button style
- Add `.card` - Default card style
- Add `.card-hover` - Card with hover effect
- Add `.input` - Default input style
- Add `.badge` - Badge base style
- Add `.badge-teal`, `.badge-amber`, `.badge-red`, `.badge-green` - Color variants

### 📁 Files Modified

#### Core Files (4)
- `tailwind.config.js` - Glassmorphism config, dark mode, animations
- `resources/css/app.css` - Glass utilities, dark mode variables
- `resources/views/layouts/app.blade.php` - Complete rewrite
- `resources/views/layouts/guest.blade.php` - Glass card design

#### Main Pages (5)
- `resources/views/home.blade.php` - Centered layout, glass cards
- `resources/views/quran/index.blade.php` - Glass surah list
- `resources/views/quran/surah.blade.php` - Glass ayah cards
- `resources/views/prayer/index.blade.php` - Glass prayer widget
- `resources/views/forum/index.blade.php` - Glass posts, FAB button

#### Secondary Pages (8)
- `resources/views/hadith/index.blade.php` - Glass collection cards
- `resources/views/chatbot/index.blade.php` - Glass chat container
- `resources/views/qibla/index.blade.php` - Glass compass card
- `resources/views/profile/edit.blade.php` - Glass profile card
- `resources/views/auth/login.blade.php` - Glass login card
- `resources/views/auth/forgot-password.blade.php` - Glass OTP card

#### Components (9)
- `resources/views/components/primary-button.blade.php` - Teal gradient
- `resources/views/components/secondary-button.blade.php` - Glass style
- `resources/views/components/danger-button.blade.php` - Red glass
- `resources/views/components/text-input.blade.php` - Glass input
- `resources/views/components/modal.blade.php` - Glass modal
- `resources/views/components/input-label.blade.php` - Dark mode
- `resources/views/components/input-error.blade.php` - Dark mode
- `resources/views/components/application-logo.blade.php` - New logo
- `resources/views/components/nav-link.blade.php` - Dark mode

---

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
