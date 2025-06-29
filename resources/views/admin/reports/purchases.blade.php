<x-app-layout>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form Filter Tanggal --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6 mx-4 sm:mx-0">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Laporan Pembelian Bahan Baku') }}
                    </h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.reports.purchases') }}" method="GET">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex flex-col sm:flex-row sm:space-x-2 w-full md:w-auto space-y-2 sm:space-y-0">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Tampilkan
                                </button>
                                <a href="{{ route('admin.reports.purchases', ['start_date' => $startDate, 'end_date' => $endDate, 'print' => 1]) }}" target="_blank" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center mb-8">
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-red-600">Total Pengeluaran</p>
                            <p class="mt-1 text-xl sm:text-2xl font-bold text-red-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-600">Total Transaksi Pembelian</p>
                            <p class="mt-1 text-xl sm:text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                        </div>
                    </div>

                    {{-- Tabel Detail Transaksi --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Pembelian</th>
                                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('D MMM Y') }}</div>
                                            <div class="text-gray-500">
                                                <span class="font-mono">{{ $purchase->invoice_number }}</span>
                                                <div class="md:hidden">{{ $purchase->supplier->name }} ({{ $purchase->supplier->contact_name }})</div>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->supplier->name }} ({{ $purchase->supplier->contact_name }})</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-8 text-gray-500">Tidak ada data pembelian pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>