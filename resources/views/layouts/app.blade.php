<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex">

            {{-- Tambahkan 'fixed', 'top-0', 'left-0', 'h-screen', dan 'z-40' (agar di atas konten tapi di bawah header jika diperlukan) --}}
            <aside class="w-64 bg-gray-800 text-white min-h-screen p-4 flex-shrink-0 fixed top-0 left-0 h-screen z-40 overflow-y-auto">
                <div class="mb-8 text-center">
                    <a href="{{ route('dashboard') }}"
                        class="text-2xl font-bold hover:text-amber-400 transition-colors">
                        Resto Enak
                    </a>
                    <p class="text-sm text-gray-400">Admin Panel</p>
                </div>

                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                {{ request()->routeIs('dashboard') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- ========================================================== --}}
                        {{-- ============ DI SINI KITA PASANG KONDISINYA ============ --}}
                        {{-- ========================================================== --}}

                        @if (auth()->user()->role === 'admin')
                            <li class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Master Data</li>
                            <li>
                                <a href="{{ route('admin.menu-items.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                    {{ request()->routeIs('admin.menu-items.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    <span>Manajemen Menu</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ingredients.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                    {{ request()->routeIs('admin.ingredients.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </svg>
                                    <span>Bahan Baku</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                    {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 11h10">
                                        </path>
                                    </svg>
                                    <span>Kategori Menu</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                    {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197">
                                        </path>
                                    </svg>
                                    <span>Pengguna</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.suppliers.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                    {{ request()->routeIs('admin.suppliers.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v2m7-7h.01">
                                        </path>
                                    </svg>
                                    <span>Supplier</span>
                                </a>
                            </li>
                        @endif

                        @if (in_array(auth()->user()->role, ['admin', 'kasir']))
                            <li class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Transaksi</li>
                            <li>
                                <a href="{{ route('kasir.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('kasir.index') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Halaman Kasir</span>
                                </a>
                            </li>
                        @endif

                        @if (in_array(auth()->user()->role, ['admin', 'atasan']))
                            <li class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Laporan</li>
                            <li>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors {{ request()->routeIs('admin.reports.index') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span>Laporan Penjualan</span>
                                </a>
                            </li>
                        @endif

                    </ul>

                    <ul class="space-y-2 mt-8 pt-4 border-t border-gray-700">
                        <li>
                            <a href="{{ route('customer.landing') }}" {{-- target="_blank"  Uncomment jika ingin membuka di tab baru --}}
                                class="flex items-center space-x-3 px-4 py-3 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                <span>Lihat Halaman Depan</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold transition-colors w-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span>Logout</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </aside>

            {{-- Tambahkan 'ml-64' (margin-left sebesar lebar sidebar) dan 'pt-[5rem]' (padding-top untuk header) --}}
            <div class="flex-1 ml-64"> 
                {{-- Tambahkan 'fixed', 'top-0', 'left-0', 'w-full', 'z-50' (lebih tinggi dari sidebar) --}}
                {{-- Sesuaikan 'w-full' dengan 'left-64' agar hanya membentang di area konten --}}
                <header class="bg-white shadow-md fixed top-0 left-64 right-0 w-[calc(100%-16rem)] z-50">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{-- Ini adalah tempat untuk judul halaman, misal 'Dashboard', 'Tambah Menu', dll. --}}
                        {{ $header }}
                    </div>
                </header>

                {{-- Berikan padding-top agar tidak tertutup header --}}
                <main class="pt-[5rem]"> {{-- Sesuaikan 'pt-[5rem]' dengan tinggi header Anda (misal 80px) --}}
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>

</html>