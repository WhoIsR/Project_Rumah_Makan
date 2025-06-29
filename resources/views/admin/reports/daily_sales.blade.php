<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Laporan Penjualan Harian: ') . \Carbon\Carbon::parse($date)->format('d F Y') }}
                    </h2>
                </div>

                {{-- Card Body --}}
                <div class="p-6 bg-white">
                    {{-- FIX: Made the summary header responsive --}}
                    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-2 md:space-y-0">
                        <p class="text-lg font-bold text-gray-800">Total Penjualan Hari Ini: Rp {{ number_format($dailyTotalSales, 0, ',', '.') }}</p>
                        <a href="{{ route('admin.reports.index', ['start_date' => $date, 'end_date' => $date]) }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                            Kembali ke Ringkasan
                        </a>
                    </div>
                    
                    <div class="border-t pt-6">
                        <h3 class="text-xl font-semibold mb-4 text-center">{{ __('Daftar Pesanan Selesai') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pesanan
                                        </th>
                                        {{-- FIX: Hide less critical columns on smaller screens --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                            Waktu
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                            Kasir
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                            Detail Menu
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                                <div class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                                </div>
                                                {{-- FIX: Show hidden data on mobile --}}
                                                <div class="md:hidden text-xs text-gray-500 mt-1">
                                                     <span>{{ $order->created_at->format('H:i:s') }}</span> | <span>{{ $order->user->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden md:table-cell">
                                                {{ $order->created_at->format('H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden sm:table-cell">
                                                {{ $order->user->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 hidden lg:table-cell">
                                                <ul class="list-disc list-inside">
                                                    @foreach ($order->items as $item)
                                                        <li>{{ $item->menuItem->name ?? 'Menu Dihapus' }} (x{{ $item->quantity }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                Tidak ada pesanan selesai untuk tanggal ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
