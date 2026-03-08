<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Permohonan</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

<!-- HEADER / -->
<div class="relative z-10 w-full h-24 px-12 bg-white flex justify-between items-center shadow-sm">

    <!-- Logo on left -->
    <a href="" class="block w-32 flex items-center text-lg md:text-2xl font-bold tracking-widest text-gray-700 hover:text-gray-400">
        eTalent
    </a>

    <!-- Navigation in the middle -->
    <nav class="absolute left-1/2 transform -translate-x-1/2 flex items-center space-x-8 h-full">
        <a href="{{ route('app.permohonan.index') }}"
           class="text-gray-700 hover:text-blue-900 font-medium text-sm uppercase tracking-wider py-2 border-b-2 {{ request()->routeIs('app.permohonan.index') ? 'border-blue-900 text-blue-900' : 'border-transparent hover:border-blue-900' }} transition duration-150">
            Permohonan
        </a>
        <a href="{{ route('app.profil') }}"
           class="text-gray-700 hover:text-blue-900 font-medium text-sm uppercase tracking-wider py-2 border-b-2 {{ request()->routeIs('app.profil') ? 'border-blue-900 text-blue-900' : 'border-transparent hover:border-blue-900' }} transition duration-150">
            Profil
        </a>
    </nav>

    <!-- Profile Dropdown -->
    <div class="relative" x-data="{ open: false }">
        <!-- Profile Button -->
        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
            <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center text-white font-semibold">
                <span>{{ substr(Auth::user()->name ?? 'AD', 0, 2) }}</span>
            </div>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" @click.away="open = false"
             x-cloak
             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border z-50">

            <!-- Profile Link -->
            <a href="{{ route('app.profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user w-4 h-4"></i>
                    <span>Profile</span>
                </div>
            </a>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('app.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt w-4 h-4"></i>
                        <span>Log keluar</span>
                    </div>
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Page Content -->
<main class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</main>

<!-- Alpine.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Livewire Scripts -->
@livewireScripts

<!-- Optional: Add this to hide Alpine elements before initialization -->
<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
