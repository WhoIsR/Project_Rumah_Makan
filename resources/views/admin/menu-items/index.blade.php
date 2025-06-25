<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                {{-- FIX: Made the header responsive --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Manajemen Menu Makanan') }}
                    </h2>
                    <a href="{{ route('admin.menu-items.create') }}"
                       class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                        + Tambah Menu Baru
                    </a>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Menampilkan pesan sukses/error jika ada --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Tabel untuk menampilkan daftar menu --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- FIX: Hide image on extra small screens --}}
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        Gambar</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($menuItems as $menuItem)
                                    <tr>
                                        {{-- FIX: Hide image on extra small screens --}}
                                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                            @if ($menuItem->image_path)
                                                <img src="{{ Storage::url($menuItem->image_path) }}"
                                                     alt="{{ $menuItem->name }}"
                                                     class="w-16 h-16 object-cover rounded-md">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500">
                                                    No Image</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $menuItem->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp
                                            {{ number_format($menuItem->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.menu-items.edit', $menuItem) }}"
                                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            {{-- Form untuk tombol Hapus --}}
                                            <form action="{{ route('admin.menu-items.destroy', $menuItem) }}"
                                                  method="POST" class="inline-block ml-4"
                                                  onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Belum ada menu yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link untuk pagination --}}
                    <div class="mt-4">
                        {{ $menuItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
