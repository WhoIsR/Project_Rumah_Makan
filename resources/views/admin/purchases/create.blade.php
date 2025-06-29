<x-app-layout>
    <style>
        /* Terapkan hanya pada layar dengan lebar maksimal 767px (mobile & tablet kecil) */
        @media (max-width: 767px) {
            .responsive-table thead {
                display: none;
                /* Sembunyikan header tabel */
            }

            .responsive-table tbody,
            .responsive-table tr,
            .responsive-table td {
                display: block;
                /* Ubah elemen tabel menjadi block agar bisa ditumpuk */
                width: 100%;
            }

            .responsive-table tr {
                margin-bottom: 1.5rem;
                /* Beri jarak antar "kartu" bahan */
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
            }

            .responsive-table td {
                padding-left: 50%;
                /* Buat ruang di kiri untuk label */
                position: relative;
                text-align: right;
                /* Ratakan konten ke kanan */
                border: none;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .responsive-table td:before {
                content: attr(data-label);
                /* Ambil teks dari atribut data-label */
                position: absolute;
                left: 0.75rem;
                width: 45%;
                padding-right: 0.75rem;
                font-weight: 600;
                text-align: left;
                /* Ratakan label ke kiri */
                white-space: nowrap;
            }

            /* Khusus untuk kolom aksi agar tombolnya di tengah */
            .responsive-table .action-cell {
                text-align: center;
                padding-left: 0;
            }
        }
    </style>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg mx-4 sm:mx-0">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Buat Transaksi Pembelian Baru') }}
                    </h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Oops! Ada beberapa kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchase-form">
                        @csrf
                        <input type="hidden" name="supplier_id" id="supplier_id_hidden">

                        {{-- PERBAIKAN: Grid dibuat lebih responsif --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="supplier_location" class="block font-medium text-sm text-gray-700">Lokasi
                                    Supplier</label>
                                <select id="supplier_location"
                                    class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Lokasi --</option>
                                </select>
                            </div>
                            <div>
                                <label for="supplier_contact" class="block font-medium text-sm text-gray-700">Nama
                                    Pedagang</label>
                                <select id="supplier_contact"
                                    class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm" required
                                    disabled>
                                    <option value="">-- Pilih Pedagang --</option>
                                </select>
                            </div>
                            <div>
                                <label for="transaction_date" class="block font-medium text-sm text-gray-700">Tanggal
                                    Transaksi</label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label for="invoice_number" class="block font-medium text-sm text-gray-700">Nomor
                                    Faktur</label>
                                <input type="text" name="invoice_number" id="invoice_number"
                                    value="{{ $invoiceNumber }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 my-6"></div>

                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Detail Bahan Baku</h3>
                            <button type="button" id="open-ingredient-modal-btn"
                                class="mt-2 sm:mt-0 w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                                + Bahan Baru
                            </button>
                        </div>

                        {{-- Notifikasi Sukses --}}
                        <div id="success-notification"
                            class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300 ease-out opacity-0">
                            Bahan baku berhasil ditambahkan!
                        </div>

                        <div class="overflow-x-auto">
                            {{-- PERBAIKAN: Tambahkan class 'responsive-table' --}}
                            <table class="min-w-full responsive-table" id="ingredients-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            style="width: 35%;">Bahan Baku</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            style="width: 25%;">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            style="width: 20%;">Harga Satuan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            style="width: 15%;">Subtotal</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            style="width: 5%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="md:divide-y md:divide-gray-200">
                                    {{-- Baris akan ditambahkan oleh JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                            <button type="button" id="add-ingredient-btn"
                                class="bg-green-500 hover:bg-green-600 sm:w-auto text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                                + Tambah Baris
                            </button>
                        </div>

                        {{-- Total Keseluruhan --}}
                        <div class="flex justify-end mt-4 border-t pt-4">
                            <div class="w-full md:w-1/3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-medium text-gray-700">Total Keseluruhan</span>
                                    <span id="grand-total" class="text-lg font-bold text-gray-900">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block font-medium text-sm text-gray-700">Catatan
                                (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="p-3 mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.purchases.index') }}"
                                class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">Batal</a>
                            <button type="submit"
                                class="ml-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK MENAMBAH BAHAN BAKU BARU --}}
    <div id="add-ingredient-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Bahan Baku Baru</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="new-ingredient-form" onsubmit="return false;">
                        <div class="mb-4 text-left">
                            <label for="new_ingredient_name" class="text-sm text-gray-600">Nama Bahan Baku</label>
                            <input type="text" id="new_ingredient_name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4 text-left">
                            <label for="new_ingredient_unit" class="text-sm text-gray-600">Satuan Dasar</label>
                            <select id="new_ingredient_unit"
                                class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($units as $unit)
                                    @if (is_null($unit->base_unit_id))
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div id="modal-error" class="text-red-500 text-sm mb-4 hidden"></div>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="save-new-ingredient-btn"
                        class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600">
                        Simpan
                    </button>
                    <button id="close-ingredient-modal-btn"
                        class="mt-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // === VARIABEL GLOBAL & DATA ===
                let allSuppliers = @json($suppliers);
                let ingredientsData = @json($ingredients);
                const unitsData = @json($units);
                let ingredientIndex = 0;

                // === ELEMEN-ELEMEN PENTING ===
                const purchaseForm = document.getElementById('purchase-form');
                const supplierLocationDropdown = document.getElementById('supplier_location');
                const supplierContactDropdown = document.getElementById('supplier_contact');
                const hiddenSupplierIdInput = document.getElementById('supplier_id_hidden');
                const modal = document.getElementById('add-ingredient-modal');
                const openModalBtn = document.getElementById('open-ingredient-modal-btn');
                const closeModalBtn = document.getElementById('close-ingredient-modal-btn');
                const saveNewIngredientBtn = document.getElementById('save-new-ingredient-btn');
                const newIngredientForm = document.getElementById('new-ingredient-form');
                const modalError = document.getElementById('modal-error');
                const successNotification = document.getElementById('success-notification'); // Elemen notifikasi baru

                // === LOGIKA DROPDOWN DINAMIS ===
                const uniqueLocations = [...new Set(allSuppliers.map(s => s.name))];
                uniqueLocations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location;
                    option.textContent = location;
                    supplierLocationDropdown.appendChild(option);
                });

                supplierLocationDropdown.addEventListener('change', function() {
                    const selectedLocation = this.value;
                    supplierContactDropdown.innerHTML = '<option value="">-- Pilih Pedagang --</option>';
                    hiddenSupplierIdInput.value = '';
                    supplierContactDropdown.disabled = true;

                    if (selectedLocation) {
                        const contactsInLocation = allSuppliers.filter(s => s.name === selectedLocation);
                        if (contactsInLocation.length > 0) {
                            contactsInLocation.forEach(contact => {
                                const option = document.createElement('option');
                                option.value = contact.id;
                                option.textContent = contact.contact_person;
                                supplierContactDropdown.appendChild(option);
                            });
                            supplierContactDropdown.disabled = false;
                        }
                    }
                });

                supplierContactDropdown.addEventListener('change', function() {
                    hiddenSupplierIdInput.value = this.value;
                });

                // === LOGIKA MODAL BAHAN BAKU BARU ===
                openModalBtn.addEventListener('click', () => modal.classList.remove('hidden'));
                closeModalBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    newIngredientForm.reset();
                    modalError.classList.add('hidden');
                });

                saveNewIngredientBtn.addEventListener('click', async () => {
                    const name = document.getElementById('new_ingredient_name').value;
                    const base_unit_id = document.getElementById('new_ingredient_unit').value;
                    modalError.classList.add('hidden');

                    if (!name || !base_unit_id) {
                        modalError.textContent = 'Nama dan Satuan Dasar wajib diisi.';
                        modalError.classList.remove('hidden');
                        return;
                    }

                    try {
                        const response = await fetch("{{ route('admin.ingredients.store.ajax') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                name,
                                base_unit_id
                            })
                        });

                        const newIngredient = await response.json();

                        if (!response.ok) {
                            let errorMsg = 'Terjadi kesalahan.';
                            if (newIngredient.errors) {
                                errorMsg = Object.values(newIngredient.errors).flat().join(' ');
                            }
                            throw new Error(errorMsg);
                        }

                        ingredientsData.push(newIngredient);

                        document.querySelectorAll('.ingredient-select').forEach(select => {
                            const newOption = document.createElement('option');
                            newOption.value = newIngredient.id;
                            newOption.textContent =
                                `${newIngredient.name} (${newIngredient.base_unit ? newIngredient.base_unit.symbol : ''})`;
                            select.appendChild(newOption);
                        });

                        newIngredientForm.reset();
                        modal.classList.add('hidden');

                        // Tampilkan notifikasi sukses
                        successNotification.classList.remove('hidden');
                        setTimeout(() => {
                            successNotification.classList.remove('opacity-0');
                        }, 10); // Sedikit delay untuk transisi

                        setTimeout(() => {
                            successNotification.classList.add('opacity-0');
                            successNotification.addEventListener('transitionend',
                                function handler() {
                                    successNotification.classList.add('hidden');
                                    successNotification.removeEventListener('transitionend',
                                        handler);
                                });
                        }, 3000); // Notifikasi akan hilang setelah 3 detik

                    } catch (error) {
                        modalError.textContent = error.message;
                        modalError.classList.remove('hidden');
                    }
                });

                // === LOGIKA MENAMBAH/MENGURANGI BARIS BAHAN BAKU ===
                const addIngredientRow = (detail = null) => {
                    const tableBody = document.querySelector('#ingredients-table tbody');
                    const newRow = document.createElement('tr');
                    ingredientIndex++;
                    newRow.dataset.index = ingredientIndex;

                    const ingredientOptions = ingredientsData.map(ing => {
                        const isSelected = detail && detail.ingredient_id == ing.id ? 'selected' : '';
                        return `<option value="${ing.id}" ${isSelected}>${ing.name} (${ing.base_unit ? ing.base_unit.symbol : ''})</option>`;
                    }).join('');

                    const unitOptions = unitsData.map(unit => {
                        const isSelected = detail && detail.unit == unit.symbol ? 'selected' : '';
                        return `<option value="${unit.symbol}" ${isSelected}>${unit.symbol}</option>`;
                    }).join('');

                    newRow.innerHTML = `
                    <td class="px-2 py-4" data-label="Bahan Baku">
                        <select name="ingredients[${ingredientIndex}][id]" class="ingredient-select p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Bahan --</option>
                            ${ingredientOptions}
                        </select>
                    </td>
                    <td class="px-2 py-4" data-label="Jumlah">
                        <div class="flex items-center rounded-md shadow-sm">
                            <input type="number" name="ingredients[${ingredientIndex}][quantity]" value="${detail ? detail.quantity : ''}" class="quantity block w-full flex-1 rounded-none rounded-l-md border-gray-300 sm:text-sm" step="0.01" min="0.01" required>
                            <select name="ingredients[${ingredientIndex}][unit]" class="unit-select inline-flex items-center px-2 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm" required style="width: auto; min-width: 50px;">
                                ${unitOptions}
                            </select>
                        </div>
                    </td>
                    <td class="px-2 py-4" data-label="Harga Satuan">
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">Rp</span>
                            <input type="number" name="ingredients[${ingredientIndex}][price]" value="${detail ? detail.price_per_unit : ''}" class="price block w-full flex-1 rounded-none rounded-r-md border-gray-300 sm:text-sm" step="1" min="0" required>
                        </div>
                    </td>
                    <td class="px-2 py-4 subtotal text-right text-sm font-medium" data-label="Subtotal">Rp 0</td>
                    <td class="px-2 py-4 action-cell" data-label="">
                        <button type="button" class="remove-row mt-2 sm:mt-0 w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">Hapus</button>
                    </td>
                `;
                    tableBody.appendChild(newRow);
                    updateRowSubtotal(newRow);
                };

                document.getElementById('add-ingredient-btn').addEventListener('click', () => addIngredientRow());

                document.querySelector('#ingredients-table tbody').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row')) {
                        e.target.closest('tr').remove();
                        updateGrandTotal();
                    }
                });

                document.querySelector('#ingredients-table tbody').addEventListener('input', function(e) {
                    if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
                        const row = e.target.closest('tr');
                        updateRowSubtotal(row);
                    }
                });

                const updateRowSubtotal = (row) => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;
                    const subtotal = quantity * price;
                    row.querySelector('.subtotal').textContent = formatCurrency(subtotal);
                    updateGrandTotal();
                };

                const updateGrandTotal = () => {
                    let total = 0;
                    document.querySelectorAll('#ingredients-table tbody tr').forEach(row => {
                        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                        const price = parseFloat(row.querySelector('.price').value) || 0;
                        total += quantity * price;
                    });
                    document.getElementById('grand-total').textContent = formatCurrency(total);
                };

                const formatCurrency = (amount) => {
                    return 'Rp ' + amount.toLocaleString('id-ID');
                };

                // === BLOK VALIDASI FORM SEBELUM SUBMIT ===
                purchaseForm.addEventListener('submit', function(e) {
                    // Cek 1: Apakah supplier (pedagang) sudah dipilih.
                    const supplierId = hiddenSupplierIdInput.value;
                    if (!supplierId) {
                        alert('Silakan pilih Lokasi Supplier dan Nama Pedagang terlebih dahulu.');
                        e.preventDefault(); // Hentikan pengiriman form
                        return;
                    }

                    // Cek 2: Apakah minimal ada satu baris bahan baku.
                    const ingredientRows = document.querySelectorAll('#ingredients-table tbody tr');
                    if (ingredientRows.length === 0) {
                        alert('Silakan tambahkan minimal satu bahan baku ke dalam transaksi.');
                        e.preventDefault(); // Hentikan pengiriman form
                        return;
                    }

                    // Cek 3: Apakah semua baris bahan baku sudah diisi lengkap.
                    let allRowsValid = true;
                    ingredientRows.forEach((row, index) => {
                        const ingredientSelect = row.querySelector('.ingredient-select');
                        const quantityInput = row.querySelector('.quantity');
                        const priceInput = row.querySelector('.price');

                        if (!ingredientSelect.value || !quantityInput.value || !priceInput.value) {
                            allRowsValid = false;
                        }
                    });

                    if (!allRowsValid) {
                        alert(
                            'Pastikan semua baris bahan baku telah diisi dengan lengkap (Bahan, Jumlah, dan Harga).'
                            );
                        e.preventDefault(); // Hentikan pengiriman form
                        return;
                    }
                });


                // === INISIALISASI FORM SAAT HALAMAN DILOAD ===
                if (document.querySelectorAll('#ingredients-table tbody tr').length === 0) {
                    addIngredientRow();
                }
            });
        </script>
    @endpush

</x-app-layout>
