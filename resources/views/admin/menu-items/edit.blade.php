<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ isset($menuItem) ? __('Edit Menu Item') : __('Tambah Menu Item Baru') }}
                    </h2>
                </div>

                {{-- Card Body --}}
                <div class="p-6 bg-white">
                    <form method="POST" action="{{ isset($menuItem) ? route('admin.menu-items.update', $menuItem->id) : route('admin.menu-items.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($menuItem))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            {{-- KOLOM KIRI (Detail Menu) --}}
                            <div class="space-y-6">
                                <div> {{-- Nama Menu --}}
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Menu</label>
                                    <input id="name"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50"
                                        type="text" name="name" value="{{ old('name', $menuItem->name ?? '') }}" required
                                        autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div> {{-- Deskripsi --}}
                                    <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                    <textarea id="description" name="description" rows="8"
                                        class="p-3 mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">{{ old('description', $menuItem->description ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div> {{-- Harga --}}
                                    <label for="price" class="block font-medium text-sm text-gray-700">Harga</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input id="price"
                                            class="block w-full rounded-md border-gray-300 pl-8 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            type="number" name="price" value="{{ old('price', $menuItem->price ?? '') }}" required
                                            step="100" />
                                    </div>
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>
                                <div> {{-- Kategori Menu --}}
                                    <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori Menu</label>
                                    <select name="category_id" id="category_id"
                                        class="p-2 mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id', $menuItem->category_id ?? '') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>
                            </div> {{-- END KOLOM KIRI --}}


                            {{-- KOLOM KANAN (Gambar dan Bahan Baku) --}}
                            <div class="space-y-6">
                                {{-- Bagian Upload Gambar --}}
                                <div>
                                    <label for="image" class="block font-medium text-sm text-gray-700">Gambar Menu</label>
                                    <div id="image-upload-area"
                                        class="relative mt-1 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                        <div class="text-center">
                                            <div id="upload-prompt" class="">
                                                <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25-2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" name="image" type="file"
                                                            class="sr-only" accept="image/*" />
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                            
                                            <div id="image-display-area" class="hidden">
                                                <img id="image-preview" src="#" alt="Preview Gambar"
                                                    class="max-h-48 mx-auto object-cover rounded-lg shadow-md mb-2" />
                                                <p id="file-name-display"
                                                    class="text-sm font-medium text-gray-700 break-words"></p>
                                            </div>
                                        </div>
                                        
                                        <button type="button" id="remove-image-btn"
                                            class="hidden absolute top-2 right-2 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-sm font-bold shadow-md hover:bg-red-600 transition-colors">
                                            &times;
                                        </button>
                                        <input type="hidden" name="remove_existing_image" id="remove-existing-image" value="0">
                                    </div>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                                <div class="pt-6 border-t border-gray-200">
                                    <div class="flex justify-between items-center mb-2">
                                       <label class="block font-medium text-sm text-gray-700">Bahan Baku yang Dibutuhkan</label>
                                       <button type="button" id="add-ingredient-btn"
                                           class="text-sm bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors">
                                           + Tambah Bahan
                                       </button>
                                   </div>
                                   <div id="ingredients-container" class="space-y-3">
                                       {{-- Baris bahan baku akan ditambahkan di sini oleh JavaScript --}}
                                   </div>
                                   <x-input-error :messages="$errors->get('item_ingredients')" class="mt-2" />
                                   <x-input-error :messages="$errors->get('item_ingredients.*.id')" class="mt-2" />
                                   <x-input-error :messages="$errors->get('item_ingredients.*.quantity')" class="mt-2" />
                                </div>
                            </div> {{-- END KOLOM KANAN --}}
                        </div> {{-- END Row Utama --}}

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.menu-items.index') }}"
                                class="mr-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ isset($menuItem) ? __('Simpan Perubahan') : __('Simpan Menu') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-upload');
            const uploadPrompt = document.getElementById('upload-prompt');
            const imageDisplayArea = document.getElementById('image-display-area');
            const fileNameDisplay = document.getElementById('file-name-display');
            const imagePreview = document.getElementById('image-preview');
            const removeImageBtn = document.getElementById('remove-image-btn');
            const imageUploadArea = document.getElementById('image-upload-area');
            const removeExistingImageInput = document.getElementById('remove-existing-image');

            function showImagePreview(fileName, imageUrl) {
                uploadPrompt.classList.add('hidden');
                imageDisplayArea.classList.remove('hidden');
                removeImageBtn.classList.remove('hidden');
                fileNameDisplay.textContent = fileName;
                imagePreview.src = imageUrl;
                if(removeExistingImageInput) removeExistingImageInput.value = '0';
            }

            function resetImageUpload() {
                fileInput.value = '';
                uploadPrompt.classList.remove('hidden');
                imageDisplayArea.classList.add('hidden');
                removeImageBtn.classList.add('hidden');
                fileNameDisplay.textContent = '';
                imagePreview.src = '#';
                if(removeExistingImageInput) removeExistingImageInput.value = '1';
            }

            if (fileInput) {
                fileInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        const reader = new FileReader();
                        reader.onload = (e) => showImagePreview(file.name, e.target.result);
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', resetImageUpload);
            }

            if (imageUploadArea) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    imageUploadArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
                    document.body.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
                });
                ['dragenter', 'dragover'].forEach(eventName => {
                    imageUploadArea.addEventListener(eventName, () => imageUploadArea.classList.add('border-indigo-500', 'bg-indigo-50'), false);
                });
                ['dragleave', 'drop'].forEach(eventName => {
                    imageUploadArea.addEventListener(eventName, () => imageUploadArea.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
                });
                imageUploadArea.addEventListener('drop', e => {
                    const files = e.dataTransfer.files;
                    if (files && files.length > 0) {
                        fileInput.files = files;
                        fileInput.dispatchEvent(new Event('change'));
                    }
                }, false);
            }
            
            @if (isset($menuItem) && $menuItem->image_path)
                showImagePreview("{{ basename($menuItem->image_path) }}", "{{ Storage::url($menuItem->image_path) }}");
            @endif

            // --- LOGIKA BAHAN BAKU ---
            const ingredientsContainer = document.getElementById('ingredients-container');
            const addIngredientBtn = document.getElementById('add-ingredient-btn');
            const ingredientsData = @json($ingredients->map->only(['id', 'name', 'unit']));
            let ingredientRowIndex = 0;

            const createIngredientRow = (ingredientId = '', quantity = '') => {
                const newRow = document.createElement('div');
                newRow.classList.add('flex', 'space-x-4', 'items-center', 'ingredient-row');
                const optionsHtml = ingredientsData.map(ing => `<option value="${ing.id}" ${ing.id == ingredientId ? 'selected' : ''}>${ing.name}</option>`).join('');
                
                // FIX: Updated the remove button classes to be a red circle
                newRow.innerHTML = `
                    <select name="item_ingredients[${ingredientRowIndex}][id]" class="p-2 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                        <option value="">Pilih Bahan</option>
                        ${optionsHtml}
                    </select>
                    <input type="number" name="item_ingredients[${ingredientRowIndex}][quantity]" placeholder="Jumlah" value="${quantity}" class="block w-1/3 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" min="0.01" step="0.01">
                    <button type="button" class="remove-ingredient-btn flex-shrink-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-sm font-bold shadow-sm hover:bg-red-600 transition-colors">&times;</button>
                `;
                ingredientsContainer.appendChild(newRow);
                ingredientRowIndex++;
            };

            addIngredientBtn.addEventListener('click', () => createIngredientRow());

            ingredientsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-ingredient-btn')) {
                    e.target.closest('.ingredient-row').remove();
                }
            });

            const oldIngredients = @json(old('item_ingredients'));
            if (oldIngredients && oldIngredients.length > 0) {
                 oldIngredients.forEach(item => {
                    createIngredientRow(item.id, item.quantity);
                });
            } else if ({{ isset($menuItem) ? 'true' : 'false' }}) {
                const existingIngredients = @json($menuItem->ingredients->mapWithKeys(fn($item) => [$item->id => $item->pivot->quantity_needed]));
                if (Object.keys(existingIngredients).length > 0) {
                    Object.entries(existingIngredients).forEach(([id, quantity]) => createIngredientRow(id, quantity));
                }
            } else {
                createIngredientRow();
            }

        });
    </script>
</x-app-layout>
