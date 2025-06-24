<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Tambah Menu Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.menu-items.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Row Utama yang Memegang Dua Kolom Besar (Kiri & Kanan) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- KOLOM KIRI (Nama, Deskripsi, Harga, dan Tambah Bahan Baku) --}}
                            <div>
                                <div class="mb-4"> {{-- Nama Menu --}}
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Menu</label>
                                    <input id="name"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-200 focus-ring-opacity-50"
                                        type="text" name="name" :value="old('name')" required="required"
                                        autofocus="autofocus" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4"> {{-- Deskripsi --}}
                                    <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                    <textarea id="description" name="description" rows="3"
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>
                                    
                                    <div class="mb-4"> {{-- Harga --}}
                                    <label for="price" class="block font-medium text-sm text-gray-700">Harga</label>
                                    <input id="price"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-200 focus-ring-opacity-50"
                                        type="number" name="price" :value="old('price')" required="required"
                                        step="0.01" />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                    </div>
                                    
                                    {{-- Bahan Baku (Sekarang di Kolom Kiri) --}}
                                    <div class="mt-6">
                                        <label class="block font-medium text-sm text-gray-700 mb-2">Bahan Baku yang Dibutuhkan</label>
                                            <button type="button" id="add-ingredient-btn"
                                            class="mb-2 text-sm bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            + Tambah Bahan
                                    </button>
                                    <div id="ingredients-container" class="space-y-4">
                                        {{-- Baris bahan baku akan ditambahkan di sini oleh JavaScript --}}
                                    </div>
                                    <x-input-error :messages="$errors->get('item_ingredients')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('item_ingredients.*.id')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('item_ingredients.*.quantity')" class="mt-2" />
                                </div>
                            </div> {{-- END KOLOM KIRI --}}

                            
                            {{-- KOLOM KANAN (Kategori Menu dan Gambar Menu) --}}
                            <div>
                                <div class="mb-4"> {{-- Kategori Menu --}}
                                    <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori Menu</label>
                                    <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                {{-- Bagian Upload Gambar --}}
                                <div class="mt-6">
                                    <label for="image" class="block font-medium text-sm text-gray-700">Gambar Menu</label>
                                    <div id="image-upload-area"
                                        class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                        <div class="text-center">
                                            {{-- Area input file default, akan disembunyikan setelah upload --}}
                                            <div id="upload-prompt" class="">
                                                <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24"
                                                    fill="currentColor" aria-hidden="true" data-slot="icon">
                                                    <path fill-rule="evenodd"
                                                        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="mt-4 flex text-sm/6 text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" name="image" type="file"
                                                            class="sr-only" accept="image/*" />
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                            </div>

                                            {{-- Area Preview Gambar & Nama File (Akan ditampilkan setelah upload) --}}
                                            <div id="image-display-area" class="hidden relative">
                                                <img id="image-preview" src="#" alt="Preview Gambar"
                                                    class="max-h-48 mx-auto object-cover rounded-lg shadow-md mb-2" />
                                                <p id="file-name-display"
                                                    class="text-sm font-medium text-gray-700 break-words"></p>
                                                <button type="button" id="remove-image-btn"
                                                    class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-500 text-white rounded-full size-6 flex items-center justify-center text-xs font-bold shadow-md hover:bg-red-600 transition-colors">
                                                    &times;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                            </div> {{-- END KOLOM KANAN --}}
                        </div> {{-- END Row Utama --}}

                        {{-- Tombol Submit dan Batal --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.menu-items.index') }}"
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('file-upload');
                const uploadPrompt = document.getElementById('upload-prompt');
                const imageDisplayArea = document.getElementById('image-display-area');
                const fileNameDisplay = document.getElementById('file-name-display');
                const imagePreview = document.getElementById('image-preview');
                const removeImageBtn = document.getElementById('remove-image-btn');
                const imageUploadArea = document.getElementById('image-upload-area'); // Ambil area drag and drop

                // Fungsi untuk menampilkan area preview dan menyembunyikan prompt upload
                function showImagePreview(fileName, imageUrl) {
                    uploadPrompt.classList.add('hidden');
                    imageDisplayArea.classList.remove('hidden');
                    fileNameDisplay.textContent = fileName;
                    imagePreview.src = imageUrl;
                }

                // Fungsi untuk mereset ke tampilan awal upload
                function resetImageUpload() {
                    fileInput.value = ''; // Reset input file
                    uploadPrompt.classList.remove('hidden');
                    imageDisplayArea.classList.add('hidden');
                    fileNameDisplay.textContent = '';
                    imagePreview.src = '#';
                }

                // Event listener untuk perubahan input file (klik & pilih file)
                if (fileInput) {
                    fileInput.addEventListener('change', function(event) {
                        const files = event.target.files;
                        if (files && files.length > 0) {
                            const file = files[0];
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                showImagePreview(file.name, e.target.result);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            resetImageUpload();
                        }
                    });
                }

                // Event listener untuk tombol hapus gambar
                if (removeImageBtn) {
                    removeImageBtn.addEventListener('click', function() {
                        resetImageUpload();
                    });
                }

                // LOGIKA DRAG AND DROP START
                if (imageUploadArea) {
                    // Mencegah default behavior (membuka file di browser)
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        imageUploadArea.addEventListener(eventName, preventDefaults, false);
                        document.body.addEventListener(eventName, preventDefaults,
                        false); // Mencegah di seluruh body
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    // Menambah/menghapus kelas styling saat drag
                    ['dragenter', 'dragover'].forEach(eventName => {
                        imageUploadArea.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        imageUploadArea.addEventListener(eventName, unhighlight, false);
                    });

                    function highlight(e) {
                        imageUploadArea.classList.add('border-indigo-500'); // Ganti warna border
                        imageUploadArea.classList.add('bg-indigo-50'); // Tambah background terang
                    }

                    function unhighlight(e) {
                        imageUploadArea.classList.remove('border-indigo-500');
                        imageUploadArea.classList.remove('bg-indigo-50');
                    }

                    // Menangani event drop
                    imageUploadArea.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;

                        if (files && files.length > 0) {
                            // Assign files to the hidden file input
                            fileInput.files = files;
                            // Manually trigger the 'change' event to reuse existing logic
                            const event = new Event('change');
                            fileInput.dispatchEvent(event);
                        }
                    }
                }
                // LOGIKA DRAG AND DROP END


                // Logika untuk menampilkan gambar yang sudah ada (saat edit) - PERHATIKAN INI DI EDIT.BLADE.PHP
                // Jika ini form edit dan ada gambar_path lama, tampilkan
                @if (isset($menuItem) && $menuItem->image_path)
                    showImagePreview("{{ basename($menuItem->image_path) }}",
                        "{{ Storage::url($menuItem->image_path) }}");
                @endif


                // LOGIKA UNTUK BAHAN BAKU (SAMA SEPERUMNA)
                const ingredientsContainer = document.getElementById('ingredients-container');
                const addIngredientBtn = document.getElementById('add-ingredient-btn');
                const ingredientsData = @json($ingredients->map->only(['id', 'name']));

                let ingredientRowIndex = 0;

                addIngredientBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.classList.add('flex', 'space-x-2', 'items-center', 'ingredient-row', 'mb-2');
                    newRow.innerHTML = `
                    <select name="item_ingredients[${ingredientRowIndex}][id]" class="flex-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">-- Pilih Bahan --</option>
                        ${ingredientsData.map(ing => `<option value="${ing.id}">${ing.name}</option>`).join('')}
                    </select>
                    <input type="number" name="item_ingredients[${ingredientRowIndex}][quantity]" placeholder="Jumlah" class="w-1/4 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1">
                    <button type="button" class="remove-ingredient-btn text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                    `;
                    ingredientsContainer.appendChild(newRow);
                    ingredientRowIndex++;
                });

                ingredientsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-ingredient-btn')) {
                        e.target.closest('.ingredient-row').remove();
                    }
                });

                // Untuk form edit, pre-populate existing ingredients
                @if (isset($menuItem) && $menuItem->ingredients->count())
                    const existingIngredients = @json(
                        $menuItem->ingredients->mapWithKeys(function ($item) {
                            return [$item->id => $item->pivot->quantity_needed];
                        }));

                    Object.entries(existingIngredients).forEach(([id, quantity]) => {
                        const newRow = document.createElement('div');
                        newRow.classList.add('flex', 'space-x-2', 'items-center', 'ingredient-row', 'mb-2');
                        let optionsHtml = '<option value="">-- Pilih Bahan --</option>';
                        ingredientsData.forEach(ing => {
                            optionsHtml +=
                                `<option value="${ing.id}" ${ing.id == id ? 'selected' : ''}>${ing.name}</option>`;
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
    </div>
</x-app-layout>
