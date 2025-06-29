<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di Restoran Kami!</title>

    <!-- Fonts (Contoh menggunakan Figtree dari Bunny Fonts) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <!-- Memuat Font Kustom dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">


    <!-- Vite Assets (CSS dan JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS dasar jika diperlukan, Tailwind akan di-handle oleh app.css */
        body { font-family: 'Quicksand', sans-serif; margin: 0; padding: 0; background-color: #f9fafb; /* Tailwind gray-50 */ }
        #app-customer { /* Container untuk aplikasi Vue */ }
        /* CSS untuk smooth scrolling jika menggunakan anchor link internal */
        html {
            scroll-behavior: smooth;
        }

        /* Navigasi */
        .nav-link { position: relative; padding-bottom: 4px; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 50%; transform: translateX(-50%); background-color: #d97706; transition: width 0.3s ease-in-out; }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<!-- HEADER -->
    <header class="bg-white/90 backdrop-blur-lg shadow-sm sticky top-0 z-50">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl font-bold" style="font-family: 'Playfair Display', serif;">Warung Kita</h1>
        <nav class="hidden md:flex mt-2 space-x-8 items-center text-sm font-semibold tracking-wider uppercase">
          <a href="#keunggulan" class="nav-link">Keunggulan</a>
          <a href="#menu" class="nav-link">Menu</a>
          <a href="#testimoni" class="nav-link">Testimoni</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            @endauth
        </nav>
      </div>
    </header>
<body class="antialiased text-gray-800">
    <div id="app-customer">
        {{-- Komponen Vue akan di-mount di sini oleh resources/js/app.js --}}
        <customer-landing-page></customer-landing-page>
    </div>

    {{-- ======================================================= --}}
    {{-- ============== BAGIAN PINTU RAHASIA ADMIN =============== --}}
    {{-- ======================================================= --}}
    <div class="text-center py-5 bg-gray-800">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
                <div class="w-full md:w-1/2 md:text-center md:mb-0 mb-8">
                    <p class="text-xs text-gray-400 md:text-sm">Copyright 2025 &copy; All Rights Reserved</p>
                </div>
                <div class="w-full md:w-1/2 md:text-center md:mb-0 mb-8">
                    <ul class="list-reset flex justify-center flex-wrap text-xs md:text-sm gap-3">
                        <li><a href="#keunggulan" class="text-gray-400 hover:text-white">Keunggulan</a></li>
                        <li class="mx-4"><a href="#menu" class="text-gray-400 hover:text-white">Menu</a></li>
                        <li><a href="#testimoni" class="text-gray-400 hover:text-white">Testimoni</a></li>
                    </ul>
                </div>
            </div>
    </div>

</body>
</html>
