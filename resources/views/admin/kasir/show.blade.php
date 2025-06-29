<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div
                    class="px-6 py-4 bg-white border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Pesanan') }}
                    </h2>
                    <a href="{{ route('kasir.history') }}"
                        class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                        Kembali ke Riwayat Pesanan
                    </a>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pesanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">ID Pesanan:</p>
                                <p class="font-medium text-gray-900">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal & Waktu:</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jenis Pesanan:</p>
                                <p class="font-medium text-gray-900">
                                    @if ($order->order_type == 'dine_in')
                                        Makan di Tempat
                                    @elseif($order->order_type == 'take_away')
                                        Bawa Pulang
                                        {{-- Pilihan 'delivery' dihapus dari sini --}}
                                    @else
                                        {{ $order->order_type }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status:</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($order->status) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dicatat Oleh Kasir:</p>
                                <p class="font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>
                            @if ($order->completed_at)
                                <div>
                                    <p class="text-sm text-gray-600">Selesai Pada:</p>
                                    <p class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($order->completed_at)->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-6"></div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pesanan</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Menu Item</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga Satuan</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- PERBAIKAN DI SINI: Menggunakan $order->items --}}
                                @forelse ($order->items as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $detail->menuItem->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($detail->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                            Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Tidak ada item dalam pesanan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                        Total Keseluruhan</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                        Jumlah Dibayar</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                                        Rp {{ number_format($order->paid_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                        Kembalian</td>
                                    <td
                                        class="px-6 py-3 text-right text-sm font-bold {{ $order->change_amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        Rp {{ number_format($order->change_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
