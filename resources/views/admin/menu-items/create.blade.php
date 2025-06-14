
<x-app-layout> <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Menu Baru') }}
        </h2>

    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form
                    method="POST"
                    action="{{ route('admin.menu-items.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Menu')"/>
                        <x-text-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required="required"
                            autofocus="autofocus"/>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Deskripsi')"/>
                        <textarea
                            id="description"
                            name="description"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Harga')"/>
                        <x-text-input
                            id="price"
                            class="block mt-1 w-full"
                            type="number"
                            name="price"
                            :value="old('price')"
                            required="required"
                            step="0.01"/>
                        <x-input-error :messages="$errors->get('price')" class="mt-2"/>
                    </div>

                    <!-- Category -->
                    <div class="mt-4">
                        <x-input-label for="category_id" :value="__('Kategori Menu')"/>
                        <select
                            name="category_id"
                            id="category_id"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2"/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Gambar Menu')"/>
                        <input
                            id="image"
                            class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            type="file"
                            name="image">
                            <x-input-error :messages="$errors->get('image')" class="mt-2"/>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Bahan Baku yang Dibutuhkan') }}</h3>
                            <div id="ingredients-container" class="space-y-4">
                                {{-- Baris bahan baku akan ditambahkan di sini oleh JavaScript --}}
                            </div>
                            <button
                                type="button"
                                id="add-ingredient-btn"
                                class="mt-2 text-sm bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah Bahan
                            </button>
                            <x-input-error :messages="$errors->get('item_ingredients')" class="mt-2"/>
                            <x-input-error :messages="$errors->get('item_ingredients.*.id')" class="mt-2"/>
                            <x-input-error
                                :messages="$errors->get('item_ingredients.*.quantity')"
                                class="mt-2"/>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('admin.menu-items.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Menu') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ingredientsContainer = document.getElementById('ingredients-container');
            const addIngredientBtn = document.getElementById('add-ingredient-btn');
            const ingredientsData = @json($ingredients->map->only(['id', 'name', 'unit'])); // Data bahan dari controller

            let ingredientRowIndex = 0;

            addIngredientBtn.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.classList.add('flex', 'space-x-2', 'items-center', 'ingredient-row');
                newRow.innerHTML = `
                    <select name="item_ingredients[${ingredientRowIndex}][id]" class="flex-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">-- Pilih Bahan --</option>
                        ${ingredientsData.map(ing => `<option value="${ing.id}">${ing.name} (${ing.unit})</option>`).join('')}
                    </select>
                    <input type="number" name="item_ingredients[${ingredientRowIndex}][quantity]" placeholder="Jumlah" class="w-1/4 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1">
                    <button type="button" class="remove-ingredient-btn text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                `;
                ingredientsContainer.appendChild(newRow);
                ingredientRowIndex++;
            });

            ingredientsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-ingredient-btn')) {
                    e.target.closest('.ingredient-row').remove();
                }
            });

            // Untuk form edit, pre-populate existing ingredients
            @if(isset($menuItem) && $menuItem->ingredients->count())
                const existingIngredients = @json($menuItem->ingredients->mapWithKeys(function ($item) {
                    return [$item->id => $item->pivot->quantity_needed];
                }));

                Object.entries(existingIngredients).forEach(([id, quantity]) => {
                    const newRow = document.createElement('div');
                    newRow.classList.add('flex', 'space-x-2', 'items-center', 'ingredient-row');
                    let optionsHtml = '<option value="">-- Pilih Bahan --</option>';
                    ingredientsData.forEach(ing => {
                        optionsHtml += `<option value="${ing.id}" ${ing.id == id ? 'selected' : ''}>${ing.name} (${ing.unit})</option>`;
                    });

                    newRow.innerHTML = `
                        <select name="item_ingredients[${ingredientRowIndex}][id]" class="flex-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            ${optionsHtml}
                        </select>
                        <input type="number" name="item_ingredients[${ingredientRowIndex}][quantity]" placeholder="Jumlah" value="${quantity}" class="w-1/4 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1">
                        <button type="button" class="remove-ingredient-btn text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                    `;
                    ingredientsContainer.appendChild(newRow);
                    ingredientRowIndex++;
                });
            @endif
        });
    </script>
</x-app-layout>
