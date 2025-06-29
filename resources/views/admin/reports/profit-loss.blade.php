<x-app-layout>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form Filter Tanggal --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6 mx-4 sm:mx-0">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Laporan Laba Rugi') }}
                    </h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.reports.profit_loss') }}" method="GET">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex flex-col sm:flex-row sm:space-x-2 w-full md:w-auto space-y-2 sm:space-y-0">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Tampilkan
                                </button>
                                <a href="{{ route('admin.reports.profit_loss', ['start_date' => $startDate, 'end_date' => $endDate, 'print' => 1]) }}"
                                    target="_blank"
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Cetak
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Area Hasil Laporan --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mx-4 sm:mx-0">
                <div class="p-4 sm:p-6 bg-white">
                    {{-- Ringkasan Data --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div class="bg-green-50 p-4 sm:p-6 rounded-lg border border-green-200">
                            <p class="text-sm font-medium text-green-600">Total Pendapatan</p>
                            <p class="mt-1 text-xl sm:text-2xl lg:text-3xl font-bold text-green-800">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-50 p-4 sm:p-6 rounded-lg border border-red-200">
                            <p class="text-sm font-medium text-red-600">Total Pengeluaran</p>
                            <p class="mt-1 text-xl sm:text-2xl lg:text-3xl font-bold text-red-800">Rp
                                {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="{{ $grossProfit >= 0 ? 'bg-blue-50 border-blue-200' : 'bg-orange-50 border-orange-200' }} p-4 sm:p-6 rounded-lg border">
                            <p
                                class="text-sm font-medium {{ $grossProfit >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                                Estimasi Laba Kotor</p>
                            <p
                                class="mt-1 text-xl sm:text-2xl lg:text-3xl font-bold {{ $grossProfit >= 0 ? 'text-blue-800' : 'text-orange-800' }}">
                                Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-xs text-gray-500">
                        <p>* Laporan ini hanya menghitung laba kotor dari penjualan dikurangi pembelian bahan baku.</p>
                        <p>Biaya operasional lain seperti gaji, listrik, dan sewa tidak termasuk.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
