<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Salam Sambutan -->
            <div class="bg-white shadow-sm rounded-xl mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-gray-600 mt-1">Ini adalah ringkasan dari restoran Anda hari ini.</p>
                </div>
            </div>

            <!-- Bagian Kartu Statistik & Jalan Pintas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Kartu Total Menu -->
                <a href="{{ route('admin.menu-items.index') }}"
                    class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-transform duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <p class="text-lg font-semibold uppercase tracking-wider opacity-80">Total Menu</p>
                            <p class="text-5xl font-bold">{{ $totalMenus }}</p>
                        </div>
                        <div class="p-3 bg-white/30 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Kartu Total Bahan -->
                <a href="{{ route('admin.ingredients.index') }}"
                    class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-transform duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <p class="text-lg font-semibold uppercase tracking-wider opacity-80">Bahan Baku</p>
                            <p class="text-5xl font-bold">{{ $totalIngredients }}</p>
                        </div>
                        <div class="p-3 bg-white/30 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Jalan Pintas: Tambah Menu -->
                <a href="{{ route('admin.menu-items.create') }}"
                    class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transform transition-all duration-300 flex flex-col justify-center items-center text-center">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-full mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">Tambah Menu Baru</p>
                    <p class="text-sm text-gray-500">Buat menu andalan selanjutnya.</p>
                </a>

                <!-- Jalan Pintas: Tambah Bahan -->
                <a href="{{ route('admin.ingredients.create') }}"
                    class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transform transition-all duration-300 flex flex-col justify-center items-center text-center">
                    <div class="p-3 bg-green-100 text-green-600 rounded-full mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">Tambah Bahan Baru</p>
                    <p class="text-sm text-gray-500">Isi ulang stok gudang.</p>
                </a>

            </div>

            <!-- Bagian Daftar Menu Terbaru dan Stok Menipis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Daftar Menu Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 border-b pb-3 text-gray-800">🍽️ Menu Baru Ditambahkan
                        </h3>
                        <ul class="space-y-4 mt-4">
                            @forelse ($recentMenus as $menu)
                                <li class="flex items-center space-x-4">
                                    <img src="{{ $menu->image_path ? Storage::url($menu->image_path) : 'https://placehold.co/64x64/e2e8f0/64748b?text=...' }}"
                                        alt="{{ $menu->name }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-md flex-shrink-0">
                                    <div class="flex-grow">
                                        <p class="font-bold text-gray-900">{{ $menu->name }}</p>
                                        <p class="text-sm text-gray-600">Rp
                                            {{ number_format($menu->price, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('admin.menu-items.edit', $menu) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                </li>
                            @empty
                                <li class="text-center text-gray-500 py-8">Belum ada menu yang dibuat.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Kolom Daftar Stok Menipis -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 border-b pb-3 text-gray-800">⚠️ Stok Bahan Menipis</h3>
                        <ul class="space-y-3 mt-4">
                            @forelse ($lowStockIngredients as $ingredient)
                                <li
                                    class="flex justify-between items-center p-3 rounded-lg {{ $ingredient->stock <= 10 ? 'bg-red-50' : 'bg-yellow-50' }}">
                                    <span class="font-medium text-gray-700">{{ $ingredient->name }}</span>
                                    <div class="text-right">
                                        <p
                                            class="font-bold {{ $ingredient->stock <= 10 ? 'text-red-600' : 'text-yellow-600' }}">
                                            Sisa: {{ $ingredient->stock }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $ingredient->unit }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-gray-500 py-8">Stok semua bahan masih aman!</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
