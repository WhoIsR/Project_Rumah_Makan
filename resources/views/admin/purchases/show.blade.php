<x-app-layout>
    {{-- Tambahkan class 'printable-wrapper' untuk mengontrol layout saat print --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Tambahkan class 'printable-card' untuk mengontrol tampilan card saat print --}}
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Transaksi Pembelian') }}
                    </h2>
                    <a href="{{ route('admin.purchases.index') }}"
                        class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                        Kembali
                    </a>
                </div>

                {{-- Bagian Atas: Header Faktur & Tombol Aksi --}}
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">Faktur Pembelian</p>
                            <h3 class="text-2xl font-bold text-gray-900 font-mono">
                                {{ $purchase->invoice_number ?? '-' }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Bagian Tengah: Detail Supplier & Transaksi --}}
                <div class="p-6 sm:px-20 bg-white border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Diterima Dari</p>
                            <div class="mt-2 text-gray-900">
                                <p class="font-bold">{{ $purchase->supplier->contact_person }}</p>
                                <p class="text-sm">{{ $purchase->supplier->name }}</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-sm font-medium text-gray-500">Detail Transaksi</p>
                            <div class="mt-2 text-gray-900">
                                <p class="font-bold">
                                    {{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                                @if ($purchase->notes)
                                    <p class="text-sm mt-2">
                                        <span class="font-semibold">Catatan:</span> {{ $purchase->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Bawah: Tabel Rincian Bahan Baku --}}
                <div class="flex flex-col px-6">
                    <div class="-my-2 overflow-x-auto">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow-none overflow-hidden border-b border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Bahan Baku</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Harga Satuan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($purchase->details as $detail)
                                            <tr>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $detail->ingredient->name }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ rtrim(rtrim(number_format($detail->quantity, 2, ',', '.'), '0'), ',') }}
                                                    {{ $detail->unit }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                                    Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Total --}}
                <div class="px-20 py-6 bg-white">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between">
                                <p class="text-sm font-medium text-gray-500">Total Pembelian</p>
                                <p class="text-sm font-bold text-gray-900">Rp
                                    {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
