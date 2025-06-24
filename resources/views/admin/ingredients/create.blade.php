<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Bahan Baku Baru') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Form untuk mengirim data bahan baru --}}
                    <form method="POST" action="{{ route('admin.ingredients.store') }}">
                        @csrf

                        <!-- Nama Bahan -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Bahan</label>
                            <input id="name"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Stok Awal -->
                        <div class="mt-4">
                            <label for="stock" class="block font-medium text-sm text-gray-700">Stok Awal</label>
                            <input id="stock"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                type="number" name="stock" :value="old('stock', 0)" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <!-- Satuan -->
                        <div class="mt-4">
                            <label for="unit" class="block font-medium text-sm text-gray-700">Satuan (Contoh: gram,
                                pcs, ml)</label>
                            <input id="unit"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                type="text" name="unit" :value="old('unit')" required />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.ingredients.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
