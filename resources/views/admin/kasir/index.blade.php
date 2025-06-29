    <x-app-layout>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    {{-- Card Header --}}
                    <div class="px-6 py-4 bg-white border-b border-gray-200 justify-between flex items-center">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Daftar Menu') }}
                        </h2>
                    </div>
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
                    {{-- Menampilkan error validasi dari AJAX --}}
                    <div id="ajax-errors" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        <ul class="list-disc list-inside"></ul>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                        <div class="md:col-span-2">
                            <div class="flex space-x-2 mb-4 overflow-x-auto pb-2">
                                <button data-category-id="all"
                                    class="category-filter-btn px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors whitespace-nowrap">Semua</button>
                                @foreach ($categories as $category)
                                    <button data-category-id="{{ $category->id }}"
                                        class="category-filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors whitespace-nowrap">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>

                            <div id="menu-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse ($menuItems as $menuItem)
                                    <div class="menu-item-card bg-gray-100 p-4 rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow"
                                        data-menu-id="{{ $menuItem->id }}" data-menu-name="{{ $menuItem->name }}"
                                        data-menu-price="{{ $menuItem->price }}"
                                        data-category-id="{{ $menuItem->category_id }}">
                                        <img src="{{ $menuItem->image_path ? Storage::url($menuItem->image_path) : 'https://placehold.co/150x150/e2e8f0/64748b?text=Menu' }}"
                                            alt="{{ $menuItem->name }}"
                                            class="w-full h-32 object-cover rounded-md mb-2">
                                        <h4 class="font-bold text-gray-800">{{ $menuItem->name }}</h4>
                                        <p class="text-sm text-gray-600">Rp
                                            {{ number_format($menuItem->price, 0, ',', '.') }}</p>
                                    </div>
                                @empty
                                    <p class="col-span-3 text-center text-gray-500">Belum ada menu yang tersedia.</p>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Keranjang Pesanan</h3>
                            <div id="cart-items"
                                class="border border-gray-200 rounded-lg p-4 mb-4 h-64 overflow-y-auto">
                                <p id="cart-empty-message" class="text-center text-gray-500">Keranjang kosong.</p>
                            </div>

                            <div class="mb-4">
                                <label for="order_type" class="block text-sm font-medium text-gray-700">Jenis
                                    Pesanan:</label>
                                <select id="order_type" name="order_type"
                                    class="p-2 mt-1 block w-full rounded-md shadow-sm border-gray-300">
                                    <option value="Makan di Tempat">Makan di Tempat</option>
                                    <option value="Bawa Pulang">Bawa Pulang</option>
                                </select>
                            </div>

                            <div class="flex justify-between items-center mb-4 border-t pt-4">
                                <span class="text-lg font-bold">Total:</span>
                                <span id="cart-total" class="text-lg font-bold">Rp 0</span>
                            </div>

                            <div class="mb-4">
                                <label for="paid_amount" class="block text-sm font-medium text-gray-700">Jumlah
                                    Dibayar:</label>
                                <input type="number" id="paid_amount" name="paid_amount" min="0" step="1"
                                    value="" placeholder="Silahkan Masukkan Nominal..."
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                            </div>

                            <div class="flex justify-between items-center mb-6">
                                <span class="text-lg font-bold">Kembalian:</span>
                                <span id="change-amount" class="text-lg font-bold text-green-600">Rp 0</span>
                            </div>

                            <button id="place-order-btn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-lg">
                                Proses Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL NOTIFIKASI SUKSES --}}
        <div id="success-modal"
            class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Pembayaran Berhasil!</h3>
                    <div class="mt-2 px-7 py-3">
                        <div class="flex justify-between text-gray-700">
                            <span>Total Belanja:</span>
                            <span id="modal-total-amount" class="font-semibold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Jumlah Bayar:</span>
                            <span id="modal-paid-amount" class="font-semibold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-2xl font-bold text-green-600 mt-2 pt-2 border-t">
                            <span>Kembalian:</span>
                            <span id="modal-change-amount">Rp 0</span>
                        </div>
                    </div>
                    <div class="items-center px-4 py-3 space-y-2">
                        <a id="print-receipt-btn" href="#" target="_blank"
                            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Cetak Struk
                        </a>
                        <button id="close-modal-btn"
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Tutup & Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuList = document.getElementById('menu-list');
                const cartItemsContainer = document.getElementById('cart-items');
                const cartEmptyMessage = document.getElementById('cart-empty-message');
                const cartTotalSpan = document.getElementById('cart-total');
                const paidAmountInput = document.getElementById('paid_amount');
                const changeAmountSpan = document.getElementById('change-amount');
                const placeOrderBtn = document.getElementById('place-order-btn');
                const orderTypeSelect = document.getElementById('order_type');
                const categoryFilterBtns = document.querySelectorAll('.category-filter-btn');
                const ajaxErrorsDiv = document.getElementById('ajax-errors');
                const ajaxErrorsList = ajaxErrorsDiv.querySelector('ul');

                const successModal = document.getElementById('success-modal');
                const printReceiptBtn = document.getElementById('print-receipt-btn');
                const closeModalBtn = document.getElementById('close-modal-btn');


                let cart = {}; // { menu_item_id: { name, price, quantity, subtotal } }

                // Fungsi untuk memperbarui tampilan keranjang
                function updateCartDisplay() {
                    cartItemsContainer.innerHTML = '';
                    let total = 0;
                    let hasItems = false;

                    for (const id in cart) {
                        hasItems = true;
                        const item = cart[id];
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'flex justify-between items-center py-2 border-b last:border-b-0';
                        itemDiv.innerHTML = `
                            <div>
                                <p class="font-semibold">${item.name}</p>
                                <p class="text-sm text-gray-600">Rp ${Number(item.price).toLocaleString('id-ID')} x ${item.quantity}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button data-id="${id}" class="remove-item-btn text-red-500 hover:text-red-700 text-xl font-bold">&minus;</button>
                                <span class="font-bold">${item.quantity}</span>
                                <button data-id="${id}" class="add-item-btn text-green-500 hover:text-green-700 text-xl font-bold">&plus;</button>
                                <button data-id="${id}" class="delete-item-btn text-red-700 hover:text-red-900 ml-2">Hapus</button>
                            </div>
                            <span class="font-semibold">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</span>
                        `;
                        cartItemsContainer.appendChild(itemDiv);
                        total += item.subtotal;
                    }

                    cartTotalSpan.innerText = `Rp ${Number(total).toLocaleString('id-ID')}`;
                    cartEmptyMessage.style.display = hasItems ? 'none' : 'block';
                    calculateChange(); // Hitung kembalian setelah update keranjang
                }

                // Fungsi untuk menambahkan item ke keranjang
                function addItemToCart(menuItem) {
                    const id = menuItem.dataset.menuId;
                    const name = menuItem.dataset.menuName;
                    const price = parseFloat(menuItem.dataset.menuPrice);

                    if (cart[id]) {
                        cart[id].quantity++;
                        cart[id].subtotal = cart[id].quantity * cart[id].price;
                    } else {
                        cart[id] = {
                            menu_item_id: id,
                            name: name,
                            price: price,
                            quantity: 1,
                            subtotal: price
                        };
                    }
                    updateCartDisplay();
                }

                // Fungsi untuk mengurangi kuantitas item di keranjang
                function removeItemQuantity(id) {
                    if (cart[id]) {
                        cart[id].quantity--;
                        if (cart[id].quantity <= 0) {
                            delete cart[id];
                        } else {
                            cart[id].subtotal = cart[id].quantity * cart[id].price;
                        }
                    }
                    updateCartDisplay();
                }

                // Fungsi untuk menghapus item sepenuhnya dari keranjang
                function deleteItemFromCart(id) {
                    delete cart[id];
                    updateCartDisplay();
                }

                // Fungsi untuk menghitung kembalian
                function calculateChange() {
                    const totalText = cartTotalSpan.innerText;
                    // Bersihkan teks total dari "Rp " dan pemisah ribuan
                    const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '').replace(/,/g, '.'));

                    const paid = parseFloat(paidAmountInput.value) || 0;
                    const change = paid - total;

                    // PERUBAHAN KRUSIAL DI SINI:
                    // Hilangkan `(change >= 0 ? change : 0)` agar nilai negatif tetap ditampilkan
                    changeAmountSpan.innerText = `Rp ${Number(change).toLocaleString('id-ID')}`;

                    if (change < 0) {
                        changeAmountSpan.classList.add('text-red-600');
                        changeAmountSpan.classList.remove('text-green-600');
                    } else {
                        changeAmountSpan.classList.remove('text-red-600');
                        changeAmountSpan.classList.add('text-green-600');
                    }
                }


                // Event listener untuk klik pada item menu
                menuList.addEventListener('click', function(event) {
                    const menuItemCard = event.target.closest('.menu-item-card');
                    if (menuItemCard) {
                        addItemToCart(menuItemCard);
                    }
                });

                // Event listener untuk tombol tambah/kurang/hapus di keranjang
                cartItemsContainer.addEventListener('click', function(event) {
                    const target = event.target;
                    const id = target.dataset.id;

                    if (target.classList.contains('add-item-btn')) {
                        if (cart[id]) { // Pastikan item ada sebelum menambah
                            cart[id].quantity++;
                            cart[id].subtotal = cart[id].quantity * cart[id].price;
                        }
                        updateCartDisplay();
                    } else if (target.classList.contains('remove-item-btn')) {
                        removeItemQuantity(id);
                    } else if (target.classList.contains('delete-item-btn')) {
                        deleteItemFromCart(id);
                    }
                });

                // Event listener untuk input jumlah dibayar
                paidAmountInput.addEventListener('input', calculateChange);

                // Fungsi untuk menampilkan error AJAX
                function showAjaxErrors(errors) {
                    ajaxErrorsList.innerHTML = '';
                    ajaxErrorsDiv.classList.remove('hidden');
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errors[key].forEach(error => {
                                const li = document.createElement('li');
                                li.textContent = error;
                                ajaxErrorsList.appendChild(li);
                            });
                        }
                    }
                }
                // Fungsi untuk menyembunyikan error AJAX
                function hideAjaxErrors() {
                    ajaxErrorsDiv.classList.add('hidden');
                    ajaxErrorsList.innerHTML = '';
                }

                // Event listener untuk tombol Proses Pembayaran
                placeOrderBtn.addEventListener('click', async function() {
                    hideAjaxErrors(); // Sembunyikan error sebelumnya

                    if (Object.keys(cart).length === 0) {
                        showAjaxErrors({
                            cart: ['Keranjang pesanan kosong!']
                        });
                        return;
                    }

                    const totalText = cartTotalSpan.innerText;
                    const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '').replace(/,/g,
                        '.'));
                    const paid = parseFloat(paidAmountInput.value) || 0;

                    if (paid < total) {
                        showAjaxErrors({
                            paid_amount: ['Jumlah pembayaran kurang dari total tagihan.']
                        });
                        return;
                    }

                    const orderItems = Object.values(cart).map(item => ({
                        menu_item_id: item.menu_item_id,
                        quantity: item.quantity
                    }));

                    const orderData = {
                        items: Object.values(cart).map(item => ({
                            menu_item_id: item.menu_item_id,
                            quantity: item.quantity
                        })),
                        paid_amount: parseFloat(paidAmountInput.value) || 0,
                        order_type: orderTypeSelect.value,
                        _token: '{{ csrf_token() }}'
                    };

                    try {
                        const response = await fetch('{{ route('kasir.placeOrder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(orderData)
                        });

                        const result = await response.json(); // Coba parsing JSON

                        if (response.ok) {
                            // --- LOGIKA BARU: TAMPILKAN MODAL ---
                            document.getElementById('modal-total-amount').textContent =
                            `Rp ${result.total}`;
                            document.getElementById('modal-paid-amount').textContent =
                                `Rp ${Number(orderData.paid_amount).toLocaleString('id-ID')}`;
                            document.getElementById('modal-change-amount').textContent =
                                `Rp ${result.change}`;

                            // Buat URL untuk tombol print
                            const printUrl = `{{ url('/kasir/orders') }}/${result.order_id}/receipt`;
                            printReceiptBtn.href = printUrl;

                            successModal.classList.remove('hidden');
                        } else {
                            // Jika ada error validasi atau error backend lainnya
                            if (response.status === 422 && result.errors) { // Error validasi
                                showAjaxErrors(result.errors);
                            } else if (result.message) { // Error lain dari backend
                                showAjaxErrors({
                                    server: [result.message]
                                });
                            } else { // Error tak terduga
                                showAjaxErrors({
                                    server: ['Terjadi kesalahan yang tidak diketahui.']
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAjaxErrors({
                            network: ['Terjadi kesalahan koneksi atau server. Mohon coba lagi.']
                        });
                    }
                });

                // Event listener untuk tombol tutup modal
                closeModalBtn.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                    // Reset semuanya untuk transaksi baru
                    cart = {};
                    paidAmountInput.value = '';
                    updateCartDisplay();
                });

                // Event listener untuk perubahan jenis pesanan (sembunyikan/tampilkan nomor meja)
                orderTypeSelect.addEventListener('change', function() {
                    if (this.value === 'Makan di Tempat') {
                        tableNumberGroup.style.display = 'block';
                    } else {
                        tableNumberGroup.style.display = 'none';
                    }
                });
                // Panggil saat awal untuk menyesuaikan tampilan
                orderTypeSelect.dispatchEvent(new Event('change'));

                // Filter menu items berdasarkan kategori
                categoryFilterBtns.forEach(button => {
                    button.addEventListener('click', function() {
                        // Hapus highlight dari semua tombol
                        categoryFilterBtns.forEach(btn => {
                            btn.classList.remove('bg-indigo-600', 'text-white',
                                'hover:bg-indigo-700');
                            btn.classList.add('bg-gray-200', 'text-gray-700',
                                'hover:bg-gray-300');
                        });

                        // Tambahkan highlight ke tombol yang diklik
                        this.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                        this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');


                        const selectedCategoryId = this.dataset.categoryId;
                        document.querySelectorAll('.menu-item-card').forEach(item => {
                            if (selectedCategoryId === 'all' || item.dataset.categoryId ==
                                selectedCategoryId) { // Gunakan == untuk perbandingan
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });

                // Aktifkan tombol "Semua" saat pertama kali dimuat
                document.querySelector('.category-filter-btn[data-category-id="all"]').click();

                updateCartDisplay(); // Inisialisasi tampilan keranjang saat halaman dimuat
            });
        </script>
    </x-app-layout>
