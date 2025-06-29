<x-app-layout>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Salam Sambutan -->
            <div class="bg-white shadow-sm rounded-xl mb-6 mx-4 sm:mx-0">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg sm:text-xl font-semibold">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋
                    </h3>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Ini adalah ringkasan dari restoran Anda hari ini,
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}.</p>
                </div>
            </div>

            @if (in_array(auth()->user()->role, ['admin', 'kasir']))
                <!-- Bagian Kartu Statistik Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
                    <!-- Kartu Pendapatan Hari Ini -->
                    <div
                        class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-4 sm:p-6 rounded-xl shadow-lg">
                        <p class="text-base font-semibold uppercase tracking-wider opacity-80">Pendapatan Hari Ini</p>
                        <p class="text-3xl md:text-4xl font-bold mt-2">Rp
                            {{ number_format($todaysRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-80 mt-1">{{ $todaysTransactions }} Transaksi</p>
                    </div>

                    <!-- Kartu Laba Bulan Ini -->
                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-4 sm:p-6 rounded-xl shadow-lg">
                        <p class="text-base font-semibold uppercase tracking-wider opacity-80">Laba Bulan Ini</p>
                        <p class="text-3xl md:text-4xl font-bold mt-2">Rp
                            {{ number_format($monthlyProfit, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-80 mt-1">*Penjualan - Pembelian</p>
                    </div>

                    <!-- Jalan Pintas: Halaman Kasir -->
                    <a href="{{ route('kasir.index') }}"
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transform transition-all duration-300 flex flex-col justify-center items-center text-center md:col-span-2 lg:col-span-1">
                        <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full mb-3">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                        </div>
                        <p class="font-bold text-gray-800 text-lg">Buka Halaman Kasir</p>
                        <p class="text-sm text-gray-500">Mulai catat penjualan baru.</p>
                    </a>
                </div>
            @endif

            @if (in_array(auth()->user()->role, ['atasan']))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8 px-4 sm:px-0">
                    <!-- Kartu Pendapatan Hari Ini -->
                    <div
                        class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-4 sm:p-6 rounded-xl shadow-lg">
                        <p class="text-base font-semibold uppercase tracking-wider opacity-80">Pendapatan Hari Ini</p>
                        <p class="text-3xl md:text-4xl font-bold mt-2">Rp
                            {{ number_format($todaysRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-80 mt-1">{{ $todaysTransactions }} Transaksi</p>
                    </div>

                    <!-- Kartu Laba Bulan Ini -->
                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-4 sm:p-6 rounded-xl shadow-lg">
                        <p class="text-base font-semibold uppercase tracking-wider opacity-80">Laba Bulan Ini</p>
                        <p class="text-3xl md:text-4xl font-bold mt-2">Rp
                            {{ number_format($monthlyProfit, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-80 mt-1">*Penjualan - Pembelian</p>
                    </div>
                </div>
            @endif

            <!-- Grafik Penjualan Mingguan -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl mb-6 mx-4 sm:mx-0">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800">📈 Grafik Penjualan 7 Hari Terakhir</h3>
                    <div class="mt-4 h-64 sm:h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bagian Daftar Menu Terlaris dan Stok Menipis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 sm:px-0">
                <!-- Kolom Menu Terlaris -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 border-b pb-3 text-gray-800">🏆 Menu Terlaris
                            Bulan Ini</h3>
                        <ul class="space-y-4 mt-4">
                            @forelse ($topSellingMenus as $menu)
                                <li class="flex items-center space-x-4">
                                    <p class="text-lg font-bold text-gray-400 w-8 text-center">{{ $loop->iteration }}
                                    </p>
                                    <div class="flex-grow">
                                        <p class="font-bold text-gray-900">{{ $menu->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $menu->total_sold }} porsi terjual</p>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-gray-500 py-8">Belum ada data penjualan bulan ini.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Kolom Daftar Stok Menipis -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 border-b pb-3 text-gray-800">⚠️ Stok Bahan
                            Menipis</h3>
                        <ul class="space-y-3 mt-4">
                            @forelse ($lowStockIngredients as $ingredient)
                                <li class="flex justify-between items-center p-3 rounded-lg bg-yellow-50">
                                    <span class="font-medium text-gray-700">{{ $ingredient->name }}</span>
                                    <div class="text-right">
                                        <p class="font-bold text-yellow-600">
                                            Sisa: {{ $ingredient->stock }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $ingredient->baseUnit->name ?? '' }}</p>
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

    {{-- Script untuk Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const salesChartCtx = document.getElementById('salesChart');
            if (salesChartCtx) {
                const salesData = @json($salesData);

                new Chart(salesChartCtx, {
                    type: 'bar',
                    data: {
                        labels: salesData.labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: salesData.data,
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000) {
                                            return 'Rp ' + (value / 1000) + 'k';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
