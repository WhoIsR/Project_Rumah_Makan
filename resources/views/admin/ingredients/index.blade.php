<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Tombol untuk menambah bahan baku baru --}}
                    <div class="mb-4">
                        <a href="{{ route('admin.ingredients.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            + Tambah Bahan Baku
                        </a>
                    </div>

                    {{-- Menampilkan pesan sukses/error jika ada --}}
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

                    {{-- Tabel untuk menampilkan daftar bahan baku --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ingredients as $ingredient)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ingredient->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ingredient->stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ingredient->unit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.ingredients.edit', $ingredient) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        {{-- Form untuk tombol Hapus --}}
                                        <form action="{{ route('admin.ingredients.destroy', $ingredient) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        Belum ada bahan baku. Silakan tambahkan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link untuk pagination --}}
                    <div class="mt-4">
                        {{ $ingredients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
