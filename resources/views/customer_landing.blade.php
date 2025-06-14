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
    </style>
</head>
<body class="antialiased text-gray-800">
    <div id="app-customer">
        {{-- Komponen Vue akan di-mount di sini oleh resources/js/app.js --}}
        <customer-landing-page></customer-landing-page>
    </div>

    {{-- ======================================================= --}}
    {{-- ============== BAGIAN PINTU RAHASIA ADMIN =============== --}}
    {{-- ======================================================= --}}
    <div class="text-center py-5 bg-gray-800">
        {{-- Logika ini menggunakan Blade, bukan Vue --}}
        {{-- @auth akan berjalan jika user sudah login --}}
        @auth
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-300 hover:text-white underline transition-colors">
                Masuk ke Dashboard Admin
            </a>
        {{-- @guest akan berjalan jika user belum login --}}
        @else
            <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white underline transition-colors">
                Login sebagai Admin
            </a>
        @endauth
    </div>

</body>
</html>
