<x-app-layout>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mx-4 sm:mx-0">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Riwayat Transaksi Pembelian') }}
                    </h2>
                    <a href="{{ route('admin.purchases.create') }}"
                       class="inline-flex justify-center w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                        + Buat Transaksi Baru
                    </a>
                </div>
                <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Pembelian</th>
                                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($purchase->transaction_date)->isoFormat('D MMMM YYYY') }}</div>
                                            <div class="text-gray-500">
                                                <span class="font-mono">{{ $purchase->invoice_number ?? '-' }}</span>
                                                <div class="md:hidden mt-1">{{ $purchase->supplier->name }}</div>
                                                <div class="md:hidden mt-1">({{ $purchase->supplier->contact_person }})</div>
                                                <div class="md:hidden mt-1">Total: Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</div>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $purchase->supplier->name }} ({{ $purchase->supplier->contact_person }})
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('admin.purchases.show', $purchase) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            <a href="{{ route('admin.purchases.edit', $purchase) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                            <form action="{{ route('admin.purchases.destroy', $purchase) }}"
                                                  method="POST" class="inline-block ml-4"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok bahan baku akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada transaksi pembelian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 px-2 sm:px-0">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
