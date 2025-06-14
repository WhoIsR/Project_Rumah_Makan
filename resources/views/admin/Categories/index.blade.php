<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- ... (Tombol Tambah & Pesan Sukses/Error biarkan seperti sebelumnya) ... --}}
                    <div class="mb-4">
                        <a href="{{ route('admin.categories.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                            + Tambah Kategori Baru
                        </a>
                    </div>
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Menu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $category->name }}</td>

                                    {{-- ======================================================= --}}
                                    {{-- ==> BAGIAN INTERAKTIF BARU <== --}}
                                    {{-- ======================================================= --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($category->menu_items_count > 0)
                                            <button data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}" class="view-menus-btn text-blue-600 hover:text-blue-900 hover:underline font-semibold">
                                                {{ $category->menu_items_count }} Menu
                                            </button>
                                        @else
                                            <span class="text-gray-500">{{ $category->menu_items_count }} Menu</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        {{-- ... (Tombol Edit dan Hapus biarkan seperti sebelumnya) ... --}}
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    {{-- ... --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- ==> MODAL POP-UP (ditaruh di luar div utama) <== --}}
    {{-- ======================================================= --}}
    <div id="menu-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold" id="modal-title">Daftar Menu</h3>
                <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            </div>
            <div id="modal-body" class="p-6 overflow-y-auto">
                <p id="modal-loading" class="text-center py-8">Memuat...</p>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- ==> JAVASCRIPT UNTUK MENGONTROL MODAL <== --}}
    {{-- ======================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('menu-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');
            const modalLoading = document.getElementById('modal-loading');
            const viewMenuBtns = document.querySelectorAll('.view-menus-btn');

            // Fungsi untuk membuka modal
            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // Fungsi untuk menutup modal
            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            // Event listener untuk tombol tutup modal
            closeModalBtn.addEventListener('click', closeModal);

            // Event listener untuk menutup modal jika klik di area luar modal
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Event listener untuk semua tombol "Lihat Menu"
            viewMenuBtns.forEach(button => {
                button.addEventListener('click', async function() {
                    const categoryId = this.dataset.categoryId;
                    const categoryName = this.dataset.categoryName;

                    // Buka modal dan tampilkan loading
                    openModal();
                    modalTitle.innerText = `Menu dalam Kategori: ${categoryName}`;
                    modalBody.innerHTML = ''; // Kosongkan body
                    modalBody.appendChild(modalLoading); // Tampilkan loading
                    modalLoading.style.display = 'block';

                    try {
                        // Ambil data menu dari API yang kita buat
                        const response = await fetch(`/admin/categories/${categoryId}/menu-items`);
                        const menuItems = await response.json();

                        // Sembunyikan loading
                        modalLoading.style.display = 'none';

                        if(menuItems.length === 0) {
                            modalBody.innerHTML = '<p class="text-center text-gray-500">Tidak ada menu di kategori ini.</p>';
                            return;
                        }

                        // Buat daftar menu untuk ditampilkan di modal
                        const menuList = document.createElement('ul');
                        menuList.className = 'space-y-4';

                        menuItems.forEach(menu => {
                            const imageUrl = menu.image_path ? `/storage/${menu.image_path}` : 'https://placehold.co/64x64/e2e8f0/64748b?text=...';
                            const li = document.createElement('li');
                            li.className = 'flex items-center space-x-4 p-2 rounded-md hover:bg-gray-50';
                            li.innerHTML = `
                                <img src="${imageUrl}" alt="${menu.name}" class="w-16 h-16 object-cover rounded-lg shadow">
                                <div>
                                    <p class="font-bold text-gray-800">${menu.name}</p>
                                    <p class="text-sm text-gray-600">Rp ${Number(menu.price).toLocaleString('id-ID')}</p>
                                </div>
                            `;
                            menuList.appendChild(li);
                        });

                        modalBody.appendChild(menuList);

                    } catch (error) {
                        console.error('Gagal mengambil data menu:', error);
                        modalLoading.style.display = 'none';
                        modalBody.innerHTML = '<p class="text-center text-red-500">Gagal memuat data menu.</p>';
                    }
                });
            });
        });
    </script>
</x-app-layout>
