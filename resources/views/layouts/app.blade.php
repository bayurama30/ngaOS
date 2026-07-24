<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0D9488">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="NgaOS">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.png" type="image/png" sizes="512x512">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="manifest" href="/manifest.json">

        <title>{{ config('app.name', 'NgaOS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Scheherazade+New:wght@400;700&family=Noto+Naskh+Arabic:wght@400;700&family=Noto+Sans+Arabic:wght@400;700&family=Lateef:wght@400;700&family=Kitab:wght@400;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { -webkit-tap-highlight-color: transparent; }
        </style>
    </head>
    <body class="font-sans antialiased bg-surface-light dark:bg-surface-dark h-full">
        <div class="min-h-full flex">
            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 glass-sidebar safe-top safe-bottom z-40">
                {{-- Logo --}}
                <div class="flex items-center h-16 px-6 border-b border-white/20 dark:border-white/10">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/25">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity="0.3"/>
                                <path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">NgaOS</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('home') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">Home</span>
                    </a>
                    <a href="{{ route('quran.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('quran.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="font-medium">Quran</span>
                    </a>
                    <a href="{{ route('hadith.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('hadith.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="font-medium">Hadis</span>
                    </a>
                    <a href="{{ route('prayer.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('prayer.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Solat</span>
                    </a>
                    <a href="{{ route('qibla.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('qibla.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span class="font-medium">Kiblat</span>
                    </a>
                    <a href="{{ route('hijri.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('hijri.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Kalender</span>
                    </a>
                    <a href="{{ route('chatbot.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('chatbot.*') ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <span class="font-medium">AI Chat</span>
                    </a>
                </nav>

                {{-- Dark Mode Toggle & Profile --}}
                <div class="p-3 border-t border-white/20 dark:border-white/10 space-y-1">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl w-full text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                        <svg x-show="!darkMode" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="font-medium" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <span class="font-medium truncate">{{ auth()->user()->name ?? 'User' }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span class="font-medium">Masuk</span>
                        </a>
                    @endauth
                </div>
            </aside>

            {{-- Main Content Area --}}
            <div class="flex-1 lg:ml-64">
                {{-- Mobile Header --}}
                <header class="lg:hidden sticky top-0 z-40 glass-header safe-top shadow-sm">
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity="0.3"/>
                                    <path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h1 class="text-lg font-bold text-white">NgaOS</h1>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full hover:bg-white/20 transition">
                                <svg x-show="!darkMode" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="darkMode" x-cloak class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>
                            @auth
                                <a href="{{ route('profile.edit') }}" class="p-2 rounded-full hover:bg-white/20 transition">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-white">Masuk</a>
                            @endauth
                        </div>
                    </div>
                </header>

                {{-- Content with Right Sidebar --}}
                <div class="flex">
                    {{-- Main Feed --}}
                    <main class="flex-1 pb-20 lg:pb-8">
                        @if (isset($header))
                            <div class="glass-card mx-4 mt-4 px-4 py-4">
                                {{ $header }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mx-4 mt-4 p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-300 text-sm animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mx-4 mt-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{ $slot }}
                    </main>

                    {{-- Desktop Right Sidebar --}}
                    <aside class="hidden lg:block lg:w-80 lg:flex-shrink-0">
                        <div class="fixed top-0 right-0 w-80 h-full pt-4 pb-8 px-4 overflow-y-auto scrollbar-thin">
                            <div class="space-y-4">
                                {{-- Jadwal Sholat Berikutnya --}}
                                @auth
                                    <div x-data="sidebarNextPrayer()" x-init="load()" class="glass-card p-4">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Jadwal Sholat</span>
                                        </h3>
                                        <div x-show="prayer" x-cloak>
                                            <div class="flex items-center justify-between">
                                                <span class="text-2xl font-bold text-teal-600 dark:text-teal-400" x-text="prayer?.name"></span>
                                                <span class="text-lg font-semibold text-gray-900 dark:text-white" x-text="prayer?.time"></span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="'Sisa: ' + prayer?.remaining"></p>
                                        </div>
                                        <div x-show="!prayer" x-cloak>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Memuat...</p>
                                        </div>
                                        <a href="{{ route('prayer.index') }}" class="block mt-3 text-xs text-teal-600 dark:text-teal-400 hover:underline">Lihat jadwal lengkap →</a>
                                    </div>
                                @endauth

                                {{-- Ayat Hari Ini --}}
                                <div x-data="sidebarDailyVerse()" x-init="load()" class="glass-card p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Ayat Hari Ini</span>
                                    </h3>
                                    <div x-show="verse" x-cloak>
                                        <p class="font-arabic text-lg text-gray-900 dark:text-white text-right leading-relaxed mb-2" x-text="verse?.arabic"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3" x-text="verse?.translation"></p>
                                        <p class="text-xs text-teal-600 dark:text-teal-400 mt-2" x-text="verse?.reference"></p>
                                    </div>
                                    <div x-show="!verse" x-cloak>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Memuat...</p>
                                    </div>
                                </div>

                                {{-- Hadis Hari Ini --}}
                                <div x-data="sidebarDailyHadith()" x-init="load()" class="glass-card p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Hadis Hari Ini</span>
                                    </h3>
                                    <div x-show="hadith" x-cloak>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-4 mb-2" x-text="hadith?.translation"></p>
                                        <p class="text-xs text-blue-600 dark:text-blue-400" x-text="hadith?.reference"></p>
                                    </div>
                                    <div x-show="!hadith" x-cloak>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Memuat...</p>
                                    </div>
                                </div>

                                {{-- User Profile Summary --}}
                                @auth
                                    <div class="glass-card p-4">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center">
                                                <span class="text-lg font-bold text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ auth()->user()->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? auth()->user()->phone }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="block text-xs text-teal-600 dark:text-teal-400 hover:underline">Edit profil →</a>
                                    </div>
                                @endauth

                                {{-- Quick Links --}}
                                <div class="glass-card p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Quick Links</h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('qibla.index') }}" class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                            <span>Arah Kiblat</span>
                                        </a>
                                         <a href="{{ route('chatbot.index') }}" class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                             </svg>
                                             <span>AI Chatbot</span>
                                         </a>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="text-center text-xs text-gray-400 dark:text-gray-600 py-4">
                                    <p>&copy; {{ date('Y') }} NgaOS</p>
                                    <p class="mt-1">Islamic Web App</p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                {{-- Mobile Bottom Navigation --}}
                @auth
                    <nav class="lg:hidden fixed bottom-0 left-0 right-0 glass-nav safe-bottom z-50 shadow-lg">
                        <div class="flex justify-around items-center h-16 max-w-lg mx-auto">
                            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('home') ? 'text-teal-600 dark:text-teal-400' : 'text-gray-500 dark:text-gray-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-xs mt-0.5">Home</span>
                            </a>
                            <a href="{{ route('quran.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('quran.*') ? 'text-teal-600 dark:text-teal-400' : 'text-gray-500 dark:text-gray-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-xs mt-0.5">Quran</span>
                            </a>
                            <a href="{{ route('prayer.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('prayer.*') ? 'text-teal-600 dark:text-teal-400' : 'text-gray-500 dark:text-gray-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs mt-0.5">Solat</span>
                            </a>
                             <a href="{{ route('chatbot.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('chatbot.*') ? 'text-teal-600 dark:text-teal-400' : 'text-gray-500 dark:text-gray-400' }}">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                 </svg>
                                 <span class="text-xs mt-0.5">AI</span>
                             </a>
                        </div>
                    </nav>
                @endauth
            </div>
        </div>

        {{-- Alpine.js for interactivity --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        {{-- Sidebar Data Scripts --}}
        @auth
        <script>
            function sidebarNextPrayer() {
                return {
                    prayer: null,
                    async load() {
                        try {
                            const cityId = '{{ auth()->user()->city_id ?? "" }}';
                            const tz = '{{ auth()->user()->timezone ?? "Asia/Jakarta" }}';
                            if (!cityId) return;
                            const res = await fetch(`/api/muslim/prayer?city_id=${cityId}&tz=${tz}`);
                            const data = await res.json();
                            if (data?.jadwal) {
                                const now = new Date();
                                const prayers = [
                                    { name: 'Subuh', time: data.jadwal.subuh },
                                    { name: 'Dzuhur', time: data.jadwal.dzuhur },
                                    { name: 'Ashar', time: data.jadwal.ashar },
                                    { name: 'Maghrib', time: data.jadwal.maghrib },
                                    { name: 'Isya', time: data.jadwal.isya },
                                ];
                                for (const p of prayers) {
                                    if (!p.time) continue;
                                    const [h, m] = p.time.split(':');
                                    const prayerDate = new Date();
                                    prayerDate.setHours(parseInt(h), parseInt(m), 0);
                                    if (prayerDate > now) {
                                        const diff = prayerDate - now;
                                        const hours = Math.floor(diff / 3600000);
                                        const mins = Math.floor((diff % 3600000) / 60000);
                                        this.prayer = { name: p.name, time: p.time, remaining: `${hours}j ${mins}m` };
                                        return;
                                    }
                                }
                                this.prayer = { name: 'Subuh', time: prayers[0].time, remaining: 'Besok' };
                            }
                        } catch (e) { console.error('Prayer load error:', e); }
                    }
                };
            }

            function sidebarDailyVerse() {
                return {
                    verse: null,
                    async load() {
                        try {
                            const res = await fetch(`/api/muslim/quran/random?t=${Date.now()}`);
                            const data = await res.json();
                            if (data) {
                                this.verse = {
                                    arabic: data.arab || data.teks_arab || '',
                                    translation: data.translation || data.teks_indonesia || '',
                                    reference: `QS. ${data.surah?.name_latin || data.surah || ''}: ${data.ayah_number || data.ayat || ''}`
                                };
                            }
                        } catch (e) { console.error('Verse load error:', e); }
                    }
                };
            }

            function sidebarDailyHadith() {
                return {
                    hadith: null,
                    async load() {
                        try {
                            const res = await fetch(`/api/muslim/hadis/random?t=${Date.now()}`);
                            const data = await res.json();
                            if (data) {
                                this.hadith = {
                                    translation: data.text?.id || data.id || '',
                                    reference: data.takhrij || data.judul || ''
                                };
                            }
                        } catch (e) { console.error('Hadith load error:', e); }
                    }
                };
            }
        </script>
        @endauth
    </body>
</html>
