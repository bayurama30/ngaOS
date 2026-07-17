<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0D9488">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <title>{{ config('app.name', 'NgaOS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:wght@400;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-arabic { font-family: 'Amiri Quran', 'Amiri', serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased h-full bg-gradient-to-br from-teal-50 via-white to-teal-50">
        <div class="min-h-full flex flex-col items-center justify-center px-4 py-8">
            {{-- Logo & Brand --}}
            <div class="mb-8 text-center">
                <div class="w-20 h-20 bg-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-white" viewBox="0 0 32 32" fill="none">
                        <path d="M16 4C9.373 4 4 9.373 4 16s5.373 12 12 12 12-5.373 12-12S22.627 4 16 4zm0 22c-5.523 0-10-4.477-10-10S10.477 6 16 6s10 4.477 10 10-4.477 10-10 10z" fill="white" fill-opacity="0.3"/>
                        <path d="M16 8l2 4 4.5.6-3.2 3.2.8 4.4L16 18l-4.1 2.2.8-4.4-3.2-3.2 4.5-.6z" fill="white"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">NgaOS</h1>
                <p class="text-sm text-gray-500 mt-1">Islamic Web App</p>
            </div>

            {{-- Card Form --}}
            <div class="w-full sm:max-w-md">
                <div class="bg-white rounded-2xl shadow-xl shadow-teal-100/50 overflow-hidden border border-gray-100">
                    <div class="px-6 py-8">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <p class="text-center text-xs text-gray-400 mt-6">
                    &copy; {{ date('Y') }} {{ config('app.name', 'NgaOS') }}. All rights reserved.
                </p>
            </div>
        </div>

        {{-- Alpine.js --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
