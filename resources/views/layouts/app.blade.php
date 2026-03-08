<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>eTalent</title>

    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">

<!-- HEADER - Hover Dropdown dengan Gap -->
<section class="w-full px-4 sm:px-8 text-gray-500 bg-white" {!! $attributes ?? '' !!}>
    <div class="container flex items-center justify-between py-3 mx-auto max-w-8xl">

        {{-- LEFT: Logo --}}
        <a href="/app" class="flex items-center">
            <span class="text-xl font-black leading-none text-gray-900 select-none">
                e<span class="text-indigo-600">Talent.</span>
            </span>
        </a>

        {{-- RIGHT: Profile Section with CSS Dropdown --}}
        <div class="relative group">

            {{-- Profile Button (dengan extra padding bawah untuk 'jambatan') --}}
            <div class="flex items-center space-x-2 cursor-pointer pb-2">

                {{-- Nama Ringkas (mobile) / Nama Penuh (desktop) --}}
                <span class="text-sm font-medium text-gray-700 hidden sm:inline">
                    {{ Auth::user()->name ?? 'Nama' }}
                </span>
                <span class="text-sm font-medium text-gray-700 sm:hidden">
                    {{ substr(Auth::user()->name ?? 'User', 0, 8) }}{{ strlen(Auth::user()->name ?? '') > 8 ? '...' : '' }}
                </span>

                {{-- Profile Icon --}}
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                    <span>{{ substr(Auth::user()->name ?? 'AD', 0, 2) }}</span>
                </div>

                {{-- Dropdown Arrow --}}
                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>

            {{-- Dropdown Menu dengan margin-top negative untuk rapat dengan button --}}
            <div class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 border z-50
                        invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200
                        mt-1">

                <a href="{{ route('app.profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user w-4 h-4 inline mr-2"></i>Profile
                </a>

                <form method="POST" action="{{ route('app.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt w-4 h-4 inline mr-2"></i>Log keluar
                    </button>
                </form>
            </div>

            {{-- Pseudo-element untuk buat 'jambatan' antara button dan dropdown --}}
            <div class="absolute h-2 w-full invisible group-hover:visible"></div>
        </div>
    </div>
</section>

<!-- Page Content -->
<main class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>

</main>

<!-- Livewire Scripts -->
@livewireScripts

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
