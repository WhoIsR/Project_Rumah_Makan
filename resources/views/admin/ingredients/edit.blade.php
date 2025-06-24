<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Bahan Baku: ') }} {{ $ingredient->name }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Form untuk memperbarui data bahan --}}
                    <form method="POST" action="{{ route('admin.ingredients.update', $ingredient) }}">
                        @csrf
                        @method('PUT') {{-- Method spoofing untuk update --}}

                        <!-- Nama Bahan -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Bahan')" />
                            <input id="name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" value="{{ old('name', $ingredient->name) }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Stok Saat Ini -->
                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Stok Saat Ini')" />
                            <input id="stock" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" name="stock" value="{{ old('stock', $ingredient->stock) }}" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <!-- Satuan -->
                        <div class="mt-4">
                            <x-input-label for="unit" :value="__('Satuan (Contoh: gram, pcs, ml)')" />
                            <input id="unit" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="unit" value="{{ old('unit', $ingredient->unit) }}" required />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.ingredients.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
