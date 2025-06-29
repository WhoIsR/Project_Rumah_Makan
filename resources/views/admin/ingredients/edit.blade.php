<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 justify-between flex items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Edit Bahan Baku') }}
                    </h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Form untuk memperbarui data bahan --}}
                    <form method="POST" action="{{ route('admin.ingredients.update', $ingredient) }}">
                        @csrf
                        @method('PUT') {{-- Method spoofing untuk update --}}

                        <!-- Nama Bahan -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Bahan</label>
                            <input id="name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" value="{{ old('name', $ingredient->name) }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Stok Saat Ini -->
                        <div class="mt-4">
                            <label for="stock" class="block font-medium text-sm text-gray-700">Stok Saat Ini</label>
                            <input id="stock" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" name="stock" value="{{ old('stock', $ingredient->stock) }}" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                        
                        <!-- Satuan (Dropdown) -->
                        <div class="mt-4">
                            <label for="base_unit_id" class="block font-medium text-sm text-gray-700">Unit Standar</label>
                            <select name="base_unit_id" id="base_unit_id" class="p-2 mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" 
                                        {{ old('base_unit_id', $ingredient->base_unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->symbol }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('base_unit_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.ingredients.index') }}"
                                class="mr-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
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
