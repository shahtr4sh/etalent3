<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>eTalent</title>

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

<!-- HEADER -->
<section class="w-full px-4 sm:px-8 text-gray-500 bg-white" {!! $attributes ?? '' !!}>
    <div class="container flex items-center justify-between py-3 mx-auto max-w-8xl">

        {{-- LEFT: Logo --}}
        <div class="flex items-center gap-2">
            <span class="text-4xl font-black leading-none text-gray-900 select-none">
                e<span class="text-indigo-600">Talent.</span>
            </span>
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
