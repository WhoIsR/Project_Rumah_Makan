<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- ========== BAGIAN BARU: STRUKTUR DENGAN SIDEBAR ========== -->
            <div class="flex">
                <!-- Sidebar Navigasi -->
                <aside class="w-64 bg-gray-800 text-white min-h-screen p-4 flex-shrink-0">
                    <div class="mb-8 text-center">
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold hover:text-amber-400 transition-colors">
                            Resto Enak
                        </a>
                        <p class="text-sm text-gray-400">Admin Panel</p>
                    </div>

                    <nav>
                        <ul class="space-y-2">
                            <!-- Link Dashboard -->
                            <li>
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                          {{ request()->routeIs('dashboard') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <!-- Link kategori pengguna -->
                            <li>
                                <a href="{{ route('admin.categories.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                        {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 11h10"></path></svg>
                                    <span>Kategori Menu</span>
                                </a>
                            </li>
                            <!-- Link Manajemen Menu -->
                            <li>
                                <a href="{{ route('admin.menu-items.index') }}"
                                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                          {{ request()->routeIs('admin.menu-items.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                    <span>Manajemen Menu</span>
                                </a>
                            </li>
                            <!-- Link Manajemen Bahan -->
                            <li>
                                <a href="{{ route('admin.ingredients.index') }}"
                                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors
                                          {{ request()->routeIs('admin.ingredients.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    <span>Bahan Baku</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Link Tambahan di Bawah -->
                        <ul class="space-y-2 mt-8 pt-4 border-t border-gray-700">
                             <!-- Link kembali ke Landing Page Customer -->
                            <li>
                                <a href="{{ route('customer.landing') }}" target="_blank"
                                   class="flex items-center space-x-3 px-4 py-3 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span>Lihat Halaman Depan</span>
                                </a>
                            </li>
                            <!-- Tombol Logout -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="flex items-center space-x-3 px-4 py-3 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        <span>Logout</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <!-- Konten Utama Halaman -->
                <div class="flex-1">
                    <!-- Navigasi Atas (Top Bar) -->
                    <header class="bg-white shadow-md">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{-- Ini adalah tempat untuk judul halaman, misal 'Dashboard', 'Tambah Menu', dll. --}}
                            {{ $header }}
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
            <!-- ========== AKHIR BAGIAN BARU ========== -->
        </div>
    </body>
</html>
