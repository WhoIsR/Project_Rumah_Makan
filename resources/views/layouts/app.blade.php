<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Resto Enak') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FIX: Added Alpine.js for dropdown functionality --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-200 flex relative">
        {{-- SIDEBAR --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 bg-gray-800 text-gray-300 transform -translate-x-full md:translate-x-0
                   transition-all duration-300 ease-in-out flex flex-col">

            {{-- SIDEBAR HEADER - FIX: Height set to h-16 --}}
            <div id="sidebar-header-container" class="border-b border-gray-700 w-full h-16 flex items-center">
                {{-- Expanded Logo --}}
                <div class="sidebar-brand-lg px-6">
                    <h1 class="text-2xl font-bold text-amber-500">Warung <span class="text-white"> Kita</span></h1>
                </div>
                {{-- Minimized Logo --}}
                <div class="sidebar-brand-sm">
                    <h1 class="text-2xl font-bold text-amber-500">W</h1>
                    <h1 class="text-2xl font-bold text-white">K</h1>
                </div>
            </div>

            {{-- NAVIGATION MENU - FIX: Complete restructure for stability --}}
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <ul class="flex-1 py-4 space-y-1">

                    @if (in_array(auth()->user()->role, ['admin', 'atasan']))
                        {{-- Dashboard --}}
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                <div class="sidebar-icon-wrapper w-14">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                </div>
                                <span class="sidebar-text pr-6">Dashboard</span>
                            </a>
                        </li>
                        @endif @if (auth()->user()->role === 'admin')
                            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase sidebar-text">Master
                                Data
                            </li>

                            {{-- Manajemen Menu --}}
                            <li>
                                <a href="{{ route('admin.menu-items.index') }}"
                                    class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.menu-items.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                    <div class="sidebar-icon-wrapper w-14">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                        </svg>
                                    </div>
                                    <span class="sidebar-text pr-6">Manajemen Menu</span>
                                </a>
                            </li>

                            {{-- Bahan Baku --}}
                            <li>
                                <a href="{{ route('admin.ingredients.index') }}"
                                    class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.ingredients.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                    <div class="sidebar-icon-wrapper w-14">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                                        </svg>
                                    </div>
                                    <span class="sidebar-text pr-6">Bahan Baku</span>
                                </a>
                            </li>

                            {{-- Kategori Menu --}}
                            <li>
                                <a href="{{ route('admin.categories.index') }}"
                                    class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                    <div class="sidebar-icon-wrapper w-14">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="sidebar-text pr-6">Kategori Menu</span>
                                </a>
                            </li>


                            {{-- Pengguna --}}
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                    <div class="sidebar-icon-wrapper w-14">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                    </div>
                                    <span class="sidebar-text pr-6">Pengguna</span>
                                </a>
                            </li>

                            {{-- Supplier --}}
                            <li>
                                <a href="{{ route('admin.suppliers.index') }}"
                                    class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.suppliers.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                    <div class="sidebar-icon-wrapper w-14">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                                        </svg>
                                    </div>
                                    <span class="sidebar-text pr-6">Supplier</span>
                                </a>
                            </li>
                            @endif @if (in_array(auth()->user()->role, ['admin', 'kasir']))
                                <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase sidebar-text">
                                    Transaksi</li>

                                {{-- Halaman Kasir (POS) --}}
                                <li>
                                    <a href="{{ route('kasir.index') }}"
                                        class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('kasir.index') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                        <div class="sidebar-icon-wrapper w-14">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                                            </svg>
                                        </div>
                                        <span class="sidebar-text pr-6">Halaman Kasir</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('kasir.history') }}"
                                        class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('kasir.history', 'kasir.show') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                        <div class="sidebar-icon-wrapper w-14">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </div>
                                        <span class="sidebar-text pr-6">Riwayat Kasir</span>
                                    </a>
                                </li>
                                @endif @if (in_array(auth()->user()->role, ['admin']))
                                    <li>
                                        <a href="{{ route('admin.purchases.index') }}"
                                            class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.purchases.*') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                            <div class="sidebar-icon-wrapper w-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                            <span class="sidebar-text pr-6">Pembelian Bahan Baku</span>
                                        </a>
                                    </li>
                                    @endif @if (in_array(auth()->user()->role, ['admin', 'atasan']))
                                        <li
                                            class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase sidebar-text">
                                            Laporan</li>

                                        {{-- Laporan Penjualan --}}
                                        <li>
                                            <a href="{{ route('admin.reports.index') }}"
                                                class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.reports.index') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                                <div class="sidebar-icon-wrapper w-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>

                                                </div>
                                                <span class="sidebar-text pr-6">Laporan Penjualan</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.reports.purchases') }}"
                                                class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.reports.purchases') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                                <div class="sidebar-icon-wrapper w-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>

                                                </div>
                                                <span class="sidebar-text pr-6">Laporan Pembelian</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.reports.profit_loss') }}"
                                                class="sidebar-link flex items-center h-12 rounded-md transition-colors {{ request()->routeIs('admin.reports.profit_loss') ? 'bg-amber-500 text-white font-bold' : 'hover:bg-gray-700 hover:text-white' }}">
                                                <div class="sidebar-icon-wrapper w-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>

                                                </div>
                                                <span class="sidebar-text pr-6">Laporan Laba Rugi</span>
                                            </a>
                                        </li>
                                    @endif
                </ul>
            </nav>

            {{-- BOTTOM LINKS (LOGOUT, ETC) --}}
            <div class="border-t border-gray-700">
                <ul class="flex-1 px-2 py-4 space-y-1">
                    <li>
                        <a href="{{ url('/') }}" target="_blank"
                            class="sidebar-link flex items-center h-12 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                            <div class="sidebar-icon-wrapper w-14">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>

                            </div>
                            <span class="sidebar-text pr-6">Lihat Halaman Depan</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="sidebar-link flex items-center h-12 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold transition-colors w-full">
                                <div class="sidebar-icon-wrapper w-14">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                </div>
                                <span class="sidebar-text pr-6">Logout</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div id="main-content" class="flex-1 flex flex-col">
            {{-- TOP HEADER --}}
            <header id="top-header"
                class="h-16 bg-white shadow py-4 px-6 flex items-center justify-between fixed top-0 right-0 z-30">
                {{-- Left side: Toggle button --}}
                <div class="flex items-center">
                    <button id="sidebar-toggle-desktop" class="text-gray-800 focus:outline-none hidden md:block">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <button id="sidebar-toggle-mobile" class="text-gray-800 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                {{-- Middle: Mobile Title --}}
                {{-- <div class="md:hidden text-lg font-bold text-gray-800">Resto Enak</div> --}}

                {{-- Right side: User menu --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    @auth
                        {{-- Dropdown Trigger --}}
                        <button @click="open = ! open"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                            style="display: none;" @click="open = false">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Log Out
                                    </a>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6 pt-20">
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
