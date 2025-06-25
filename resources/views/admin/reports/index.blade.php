<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Laporan Penjualan') }}
                    </h2>
                </div>

                {{-- Card Body --}}
                <div class="p-6 bg-white">

                    {{-- Filter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4">
                            {{-- FIX: Adjusted grid for better tablet and desktop alignment --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal:</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                           class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal:</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                           class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                {{-- Spacer to push buttons to the right on large screens --}}
                                <div class="hidden lg:block lg:col-span-1"></div>

                                {{-- FIX: Button container adjustments for responsiveness --}}
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 sm:col-span-2 lg:col-span-2 sm:justify-end">
                                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                        Filter
                                    </button>
                                    <a href="{{ route('admin.reports.daily', ['date' => $endDate]) }}"
                                       class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                        Laporan Harian
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Summary Cards --}}
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-center">{{ __('Ringkasan Penjualan') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-indigo-50 p-6 rounded-lg shadow-md text-center">
                                <p class="text-sm text-indigo-700 font-medium">{{ __('Total Penjualan Bersih') }}</p>
                                <p class="text-3xl font-bold text-indigo-800 mt-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-blue-50 p-6 rounded-lg shadow-md text-center">
                                <p class="text-sm text-blue-700 font-medium">{{ __('Jumlah Pesanan Selesai') }}</p>
                                <p class="text-3xl font-bold text-blue-800 mt-2">{{ $totalOrders }}</p>
                            </div>
                            <div class="bg-green-50 p-6 rounded-lg shadow-md text-center">
                                <p class="text-sm text-green-700 font-medium">{{ __('Rata-rata Per Pesanan') }}</p>
                                <p class="text-3xl font-bold text-green-800 mt-2">
                                    Rp {{ number_format($totalOrders > 0 ? $totalSales / $totalOrders : 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>


                    {{-- Top Selling Items Table --}}
                    <div class="pt-6 border-t">
                        <h3 class="text-xl font-semibold mb-4">{{ __('5 Menu Terlaris') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Menu
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Terjual
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Pendapatan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($topSellingMenuItems as $menu)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                {{ $menu->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-right">
                                                {{ $menu->total_quantity_sold }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-right">
                                                Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                Tidak ada data menu terlaris untuk periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
