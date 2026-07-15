<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0D9488">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="NgaOS">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <link rel="manifest" href="/manifest.json">

        <title>{{ config('app.name', 'NgaOS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
            .safe-top { padding-top: env(safe-area-inset-top); }
            body { -webkit-tap-highlight-color: transparent; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 h-full">
        <div class="min-h-full flex flex-col">
            {{-- Top Header --}}
            <header class="bg-teal-600 text-white safe-top sticky top-0 z-40 shadow-lg">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8" viewBox="0 0 32 32" fill="none">
                            <circle cx="16" cy="16" r="14" fill="white" fill-opacity="0.2"/>
                            <path d="M16 4C9.373 4 4 9.373 4 16s5.373 12 12 12 12-5.373 12-12S22.627 4 16 4zm0 22c-5.523 0-10-4.477-10-10S10.477 6 16 6s10 4.477 10 10-4.477 10-10 10z" fill="white"/>
                            <path d="M16 8l2 4 4.5.6-3.2 3.2.8 4.4L16 18l-4.1 2.2.8-4.4-3.2-3.2 4.5-.6z" fill="white"/>
                        </svg>
                        <h1 class="text-lg font-bold">NgaOS</h1>
                    </div>
                    <div class="flex items-center space-x-3">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="p-2 rounded-full hover:bg-teal-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium">Masuk</a>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 pb-20">
                @if (isset($header))
                    <div class="bg-white border-b border-gray-200 px-4 py-4">
                        {{ $header }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mx-4 mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-4 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

            {{-- Bottom Navigation --}}
            @auth
                <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 safe-bottom z-50 shadow-lg">
                    <div class="flex justify-around items-center h-16 max-w-lg mx-auto">
                        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('home') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="text-xs mt-0.5">Home</span>
                        </a>
                        <a href="{{ route('quran.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('quran.*') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-xs mt-0.5">Quran</span>
                        </a>
                        <a href="{{ route('hadith.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('hadith.*') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-xs mt-0.5">Hadis</span>
                        </a>
                        <a href="{{ route('prayer.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('prayer.*') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs mt-0.5">Solat</span>
                        </a>
                        <a href="{{ route('forum.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('forum.*') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            <span class="text-xs mt-0.5">Forum</span>
                        </a>
                        <a href="{{ route('chatbot.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('chatbot.*') ? 'text-teal-600' : 'text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span class="text-xs mt-0.5">AI</span>
                        </a>
                    </div>
                </nav>
            @endauth
        </div>

        {{-- Alpine.js for interactivity --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
