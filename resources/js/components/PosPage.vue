<template>
  <div class="flex h-[calc(100vh-65px)] bg-stone-100 font-sans">

    <div class="w-3/5 p-4 flex flex-col">
      <div class="mb-4 flex-shrink-0">
        <input type="text" v-model="searchQuery" placeholder="Cari menu... (Contoh: Ayam, Es Teh)" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-sm">
      </div>
      <div class="flex-grow overflow-y-auto pr-2">
        <div v-if="isLoading" class="text-center py-10 text-stone-500">Memuat menu...</div>
        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div v-for="menu in filteredMenus" :key="menu.id"
               @click="addToCart(menu)"
               class="menu-item border rounded-lg p-3 text-center cursor-pointer transition-all duration-200 hover:shadow-lg hover:border-amber-500 hover:-translate-y-1"
               :class="{'opacity-40 cursor-not-allowed bg-stone-200': !menu.is_available, 'border-amber-500 ring-2 ring-amber-500': is_in_cart(menu)}">
            <img :src="menu.image_url" class="w-full h-24 object-cover rounded-md mb-2" onerror="this.onerror=null;this.src='https://placehold.co/150x100/e5e7eb/9ca3af?text=Foto';">
            <h5 class="text-sm font-bold text-stone-800 h-10 flex items-center justify-center">{{ menu.name }}</h5>
            <p class="text-xs text-stone-500">Rp {{ formatPrice(menu.price) }}</p>
            <span v-if="!menu.is_available" class="text-xs font-semibold text-red-600">Habis</span>
          </div>
        </div>
      </div>
    </div>

    <div class="w-2/5 bg-white p-6 flex flex-col shadow-lg border-l">
      <h2 class="text-2xl font-bold border-b border-stone-200 pb-4 mb-4 text-stone-800">Pesanan #{{ orderNumber }}</h2>

      <div class="flex-grow overflow-y-auto -mr-6 pr-6">
        <div v-if="cart.length === 0" class="text-center text-stone-400 pt-24">
          <p class="font-semibold">Keranjang Kosong</p>
          <p class="text-sm">Klik menu di sebelah kiri untuk menambah pesanan.</p>
        </div>
        <transition-group name="cart-item" tag="div" v-else>
          <div v-for="item in cart" :key="item.id" class="flex items-center justify-between mb-4 bg-stone-50 p-3 rounded-lg">
            <div class="flex-grow pr-3">
              <p class="font-bold text-sm text-stone-900">{{ item.name }}</p>
              <p class="text-xs text-stone-600">@ Rp {{ formatPrice(item.price) }}</p>
            </div>
            <div class="flex items-center flex-shrink-0">
              <button @click="decreaseQuantity(item)" class="w-7 h-7 bg-stone-200 rounded-full text-lg font-bold hover:bg-red-500 hover:text-white transition-colors">-</button>
              <span class="w-10 text-center font-bold">{{ item.quantity }}</span>
              <button @click="increaseQuantity(item)" class="w-7 h-7 bg-stone-200 rounded-full text-lg font-bold hover:bg-green-500 hover:text-white transition-colors">+</button>
            </div>
            <p class="w-24 text-right font-bold text-sm text-stone-900">Rp {{ formatPrice(item.price * item.quantity) }}</p>
          </div>
        </transition-group>
      </div>

      <div class="border-t-2 border-dashed pt-6 mt-auto flex-shrink-0">
        <div class="flex justify-between mb-2 text-sm">
          <span class="text-stone-600">Subtotal</span>
          <span class="font-semibold text-stone-800">Rp {{ formatPrice(totalPrice) }}</span>
        </div>
        <div class="flex justify-between mb-4 text-sm">
          <span class="text-stone-600">Pajak (11%)</span>
          <span class="font-semibold text-stone-800">Rp {{ formatPrice(taxAmount) }}</span>
        </div>
        <div class="flex justify-between items-center text-xl font-bold text-amber-600 border-t border-stone-200 pt-4">
          <span>TOTAL</span>
          <span>Rp {{ formatPrice(grandTotal) }}</span>
        </div>
        <button class="w-full mt-6 bg-green-500 text-white font-bold py-4 rounded-lg hover:bg-green-600 transition-colors text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="cart.length === 0">
          PROSES PEMBAYARAN
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      allMenus: [],
      isLoading: true,
      searchQuery: '',
      cart: [],
      orderNumber: Math.floor(Math.random() * 9000) + 1000,
    };
  },

  computed: {
    filteredMenus() {
      if (!this.searchQuery) return this.allMenus;
      return this.allMenus.filter(menu => menu.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },
    totalPrice() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    taxAmount() {
      return this.totalPrice * 0.11;
    },
    grandTotal() {
        return this.totalPrice + this.taxAmount;
    }
  },

  methods: {
    async fetchMenus() {
      this.isLoading = true;
      try {
        const response = await axios.get('/api/public/menu');
        this.allMenus = response.data;
      } catch (err) {
        alert("Gagal memuat data menu. Pastikan API berjalan.");
      } finally {
        this.isLoading = false;
      }
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    addToCart(menu) {
      if (!menu.is_available) return;
      const cartItem = this.cart.find(item => item.id === menu.id);
      if (cartItem) {
        cartItem.quantity++;
      } else {
        this.cart.push({ ...menu, quantity: 1 });
      }
    },
    increaseQuantity(item) { item.quantity++; },
    decreaseQuantity(item) {
      item.quantity--;
      if (item.quantity === 0) {
        this.cart = this.cart.filter(cartItem => cartItem.id !== item.id);
      }
    },
    is_in_cart(menu) {
        return this.cart.some(item => item.id === menu.id);
    }
  },

  mounted() {
    this.fetchMenus();
  }
};
</script>

<style scoped>
.cart-item-enter-active, .cart-item-leave-active {
  transition: all 0.3s ease;
}
.cart-item-enter-from, .cart-item-leave-to {
  opacity: 0;
  transform: translateX(20px);
}
</style>
