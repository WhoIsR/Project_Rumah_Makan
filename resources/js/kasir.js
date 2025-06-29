document.addEventListener("DOMContentLoaded", function () {
    // --- STATE MANAGEMENT ---
    let currentOrder = {
        type: null, // 'dine-in', 'takeaway' (removed 'delivery')
        table: null, // {id, name}
        // customer: null, // {name, phone, address} - Hapus ini
        items: [], // [{id, name, price, quantity}]
        total: 0,
        id: null, // Untuk menyimpan ID pesanan setelah dibuat
    };
    let menuData = [];
    let tablesData = [];

    // --- UI ELEMENTS ---
    const orderTypeBtns = document.querySelectorAll(".order-type-btn");
    const orderInfo = document.getElementById("current-order-info");
    const payButton = document.getElementById("pay-button");
    const menuGrid = document.getElementById("menu-grid");
    const orderItemsContainer = document.getElementById(
        "order-items-container"
    );
    const tableModal = document.getElementById("table-modal");
    // const deliveryModal = document.getElementById("delivery-modal"); // Hapus ini
    const successModal = document.getElementById("success-modal");

    // --- EVENT LISTENERS ---
    // Jenis Pesanan
    orderTypeBtns.forEach((btn) =>
        btn.addEventListener("click", handleOrderTypeSelect)
    );

    // Modals
    document
        .getElementById("close-table-modal-btn")
        .addEventListener("click", () => tableModal.classList.add("hidden"));
    // Hapus event listener untuk modal delivery
    // document
    //     .getElementById("close-delivery-modal-btn")
    //     .addEventListener("click", () => deliveryModal.classList.add("hidden"));
    // document
    //     .getElementById("delivery-form")
    //     .addEventListener("submit", handleDeliveryFormSubmit);

    // Aksi Keranjang
    orderItemsContainer.addEventListener("click", handleCartAction);

    // Tombol Bayar
    payButton.addEventListener("click", processOrder);

    // --- INITIALIZATION ---
    async function initializePOS() {
        try {
            // Ganti URL ini dengan route API Anda
            const response = await fetch("/api/kasir/data");
            const data = await response.json();
            menuData = data.menuItems;
            tablesData = data.tables;
            renderMenu();
            loadTables();
        } catch (error) {
            console.error("Gagal memuat data awal:", error);
            // Tampilkan error di UI
        }
    }

    // --- FUNCTIONS ---
    function handleOrderTypeSelect(e) {
        const type = e.currentTarget.dataset.type;
        currentOrder.type = type;

        orderTypeBtns.forEach((btn) => {
            btn.classList.remove(
                "bg-indigo-600",
                "text-white",
                "border-indigo-600"
            );
            btn.classList.add("bg-white", "border-gray-300");
        });
        e.currentTarget.classList.add(
            "bg-indigo-600",
            "text-white",
            "border-indigo-600"
        );

        if (type === "dine-in") {
            tableModal.classList.remove("hidden");
            // Pastikan customer info direset jika beralih dari delivery
            currentOrder.customer = null;
        }
        // Hapus kondisi untuk 'delivery'
        // else if (type === "delivery") deliveryModal.classList.remove("hidden");
        else { // Ini berarti 'takeaway'
            orderInfo.textContent = "Pesanan: Bawa Pulang";
            // Pastikan table dan customer info direset jika beralih dari dine-in/delivery
            currentOrder.table = null;
            currentOrder.customer = null;
            updatePayButtonState();
        }
    }

    function loadTables() {
        const grid = document.getElementById("table-grid");
        grid.innerHTML = "";
        tablesData.forEach((table) => {
            const tableBtn = document.createElement("button");
            tableBtn.textContent = table.name;
            tableBtn.dataset.tableId = table.id;
            tableBtn.className = `p-4 rounded-lg font-semibold text-center border-2 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500`;
            if (table.status === "occupied") {
                tableBtn.className +=
                    " bg-gray-300 text-gray-500 cursor-not-allowed";
                tableBtn.disabled = true;
            } else {
                tableBtn.className +=
                    " bg-green-100 border-green-400 hover:bg-green-200 text-green-800";
            }
            tableBtn.addEventListener("click", () => selectTable(table));
            grid.appendChild(tableBtn);
        });
    }

    function selectTable(table) {
        currentOrder.table = { id: table.id, name: table.name };
        orderInfo.textContent = `Pesanan: Meja ${table.name}`;
        tableModal.classList.add("hidden");
        updatePayButtonState();
    }

    // Hapus fungsi handleDeliveryFormSubmit
    // function handleDeliveryFormSubmit(e) {
    //     e.preventDefault();
    //     currentOrder.customer = {
    //         name: document.getElementById("customer_name").value,
    //         phone: document.getElementById("customer_phone").value,
    //         address: document.getElementById("customer_address").value,
    //     };
    //     orderInfo.textContent = `Pesanan: Delivery - ${currentOrder.customer.name}`;
    //     deliveryModal.classList.add("hidden");
    //     updatePayButtonState();
    // }

    function renderMenu() {
        menuGrid.innerHTML = "";
        menuData.forEach((item) => {
            const menuItem = document.createElement("div");
            menuItem.className =
                "border rounded-lg p-2 cursor-pointer hover:shadow-lg transition-shadow bg-white";
            menuItem.innerHTML = `
                    <img src="${
                        item.image_path
                            ? "/storage/" + item.image_path
                            : "https://placehold.co/150x150/e2e8f0/64748b?text=Menu"
                    }" alt="${
                item.name
            }" class="w-full h-24 object-cover rounded-md">
                    <h4 class="font-semibold text-sm mt-2">${item.name}</h4>
                    <p class="text-xs text-gray-600">Rp ${Number(
                        item.price
                    ).toLocaleString("id-ID")}</p>
                `;
            menuItem.addEventListener("click", () => addItemToOrder(item));
            menuGrid.appendChild(menuItem);
        });
    }

    function addItemToOrder(item) {
        const existingItem = currentOrder.items.find((i) => i.id === item.id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            currentOrder.items.push({ ...item, quantity: 1 });
        }
        renderOrderItems();
    }

    function handleCartAction(e) {
        const target = e.target.closest("button");
        if (!target) return;

        const itemId = parseInt(target.dataset.id);
        const action = target.dataset.action;

        const item = currentOrder.items.find((i) => i.id === itemId);
        if (!item) return;

        if (action === "increase") item.quantity++;
        else if (action === "decrease") {
            item.quantity--;
            if (item.quantity === 0) {
                currentOrder.items = currentOrder.items.filter(
                    (i) => i.id !== itemId
                );
            }
        } else if (action === "delete") {
            currentOrder.items = currentOrder.items.filter(
                (i) => i.id !== itemId
            );
        }
        renderOrderItems();
    }

    function renderOrderItems() {
        if (currentOrder.items.length === 0) {
            orderItemsContainer.innerHTML =
                '<p class="text-center text-gray-500">Pilih menu untuk memulai pesanan.</p>';
        } else {
            orderItemsContainer.innerHTML = currentOrder.items
                .map(
                    (item) => `
                    <div class="flex items-center justify-between py-2">
                        <div>
                            <p class="font-semibold text-sm">${item.name}</p>
                            <p class="text-xs text-gray-500">Rp ${Number(
                                item.price
                            ).toLocaleString("id-ID")}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button data-id="${
                                item.id
                            }" data-action="decrease" class="quantity-btn h-6 w-6 rounded-full bg-gray-200 hover:bg-gray-300">-</button>
                            <span class="font-medium text-sm w-5 text-center">${
                                item.quantity
                            }</span>
                            <button data-id="${
                                item.id
                            }" data-action="increase" class="quantity-btn h-6 w-6 rounded-full bg-gray-200 hover:bg-gray-300">+</button>
                            <button data-id="${
                                item.id
                            }" data-action="delete" class="text-red-500 hover:text-red-700 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    </div>
                `
                )
                .join("");
        }
        updateTotals();
        updatePayButtonState();
    }

    function updateTotals() {
        const subtotal = currentOrder.items.reduce(
            (acc, item) => acc + item.price * item.quantity,
            0
        );
        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        document.getElementById(
            "subtotal"
        ).textContent = `Rp ${subtotal.toLocaleString("id-ID")}`;
        document.getElementById("tax").textContent = `Rp ${tax.toLocaleString(
            "id-ID"
        )}`;
        document.getElementById(
            "total"
        ).textContent = `Rp ${total.toLocaleString("id-ID")}`;
        currentOrder.total = total;
    }

    function updatePayButtonState() {
        // PERUBAHAN DI SINI: Hapus kondisi untuk customer (delivery)
        const isOrderTypeSet =
            currentOrder.type === "takeaway" ||
            currentOrder.table !== null; // Hapus || currentOrder.customer !== null;
        const hasItems = currentOrder.items.length > 0;
        payButton.disabled = !(isOrderTypeSet && hasItems);
    }

    async function processOrder() {
        const payload = {
            order_type: currentOrder.type,
            table_id: currentOrder.table ? currentOrder.table.id : null,
            // Hapus customer_name, customer_phone, customer_address dari payload
            // customer_name: currentOrder.customer
            //     ? currentOrder.customer.name
            //     : null,
            // customer_phone: currentOrder.customer
            //     ? currentOrder.customer.phone
            //     : null,
            // customer_address: currentOrder.customer
            //     ? currentOrder.customer.address
            //     : null,
            items: currentOrder.items.map((item) => ({
                id: item.id,
                quantity: item.quantity,
            })),
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };

        try {
            // Ganti dengan route API Anda
            const response = await fetch("/api/kasir/orders", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (response.ok) {
                currentOrder.id = result.order_id; // Simpan ID pesanan untuk nanti
                successModal.classList.remove("hidden");
            } else {
                alert(
                    "Gagal membuat pesanan: " +
                        (result.message || "Error tidak diketahui")
                );
            }
        } catch (error) {
            console.error("Error saat memproses pesanan:", error);
            alert("Terjadi kesalahan koneksi.");
        }
    }

    initializePOS();
});
