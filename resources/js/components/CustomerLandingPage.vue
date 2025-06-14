<template>
  <div class="min-h-screen bg-amber-50">
    <!-- ======================================================= -->
    <!-- ================ BAGIAN HEADER & NAVIGASI =============== -->
    <!-- ======================================================= -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-50">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-amber-600">Resto Enak</h1>
        <nav class="hidden md:flex space-x-8 items-center">
          <a href="#makanan-utama" class="text-gray-600 hover:text-amber-500 transition-colors duration-300 font-medium">Makanan</a>
          <a href="#minuman" class="text-gray-600 hover:text-amber-500 transition-colors duration-300 font-medium">Minuman</a>
          <a href="#lokasi" class="text-gray-600 hover:text-amber-500 transition-colors duration-300 font-medium">Lokasi Kami</a>
        </nav>
      </div>
    </header>

    <!-- ======================================================= -->
    <!-- ===================== BAGIAN UTAMA ====================== -->
    <!-- ======================================================= -->
    <main>
      <!-- Bagian Hero / Sambutan Awal -->
      <section class="text-white py-24 px-6 text-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10">
          <h2 class="text-4xl md:text-6xl font-bold mb-4 tracking-tight">Hidangan Lezat, Momen Hangat</h2>
          <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto text-amber-100">Setiap masakan kami dibuat dengan cinta dan bahan-bahan segar pilihan. Selamat menikmati!</p>
          <a href="#menu-container" class="bg-amber-500 text-white font-bold py-3 px-10 rounded-full hover:bg-amber-400 transition-all duration-300 text-lg shadow-xl transform hover:scale-105">
            Lihat Semua Menu
          </a>
        </div>
      </section>

      <!-- Bagian Kontainer Menu -->
      <div id="menu-container" class="container mx-auto p-6 md:p-10">

        <!-- Kondisi Loading -->
        <div v-if="isLoading" class="flex flex-col justify-center items-center py-20">
          <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-amber-500 mb-4"></div>
          <p class="text-xl text-gray-600 font-semibold">Memuat menu...</p>
        </div>

        <!-- Kondisi Error -->
        <div v-else-if="error" class="text-center py-10 bg-red-100 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
          <p class="text-2xl font-semibold text-red-800 mb-2">🚫 Oops! Terjadi Kesalahan</p>
          <p class="text-red-600 mb-4">{{ error }}</p>
          <button @click="fetchMenus" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-5 rounded-lg transition-colors">
            Coba Lagi
          </button>
        </div>

        <!-- Tampilan Menu Jika Berhasil -->
        <div v-else>
          <!-- Kategori Makanan Utama -->
          <section id="makanan-utama" class="mb-20 scroll-mt-24">
            <h2 class="text-4xl font-bold text-gray-800 mb-10 text-center">🍲 Makanan Utama</h2>
            <div v-if="makanan.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
              <div v-for="menu in makanan" :key="menu.id"
                   class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-2">
                <img :src="menu.image_url" :alt="menu.name" class="w-full h-60 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/FFFBEB/F59E0B?text=Gambar+Segera';">
                <div class="p-6 flex flex-col flex-grow">
                  <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ menu.name }}</h3>
                  <p class="text-gray-600 text-sm mb-5 flex-grow">{{ menu.description || 'Deskripsi tidak tersedia.' }}</p>
                  <p class="text-3xl font-bold text-amber-600 mb-5">{{ formatPrice(menu.price) }}</p>
                  <button :disabled="!menu.is_available"
                          :class="['w-full text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300 text-lg', menu.is_available ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-300 text-red-800 cursor-not-allowed']">
                    {{ menu.is_available ? 'Pesan Sekarang' : 'Stok Habis' }}
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-10 text-gray-500">
              <p>Menu makanan belum tersedia saat ini.</p>
            </div>
          </section>

          <!-- Kategori Minuman -->
          <section id="minuman" class="mb-20 scroll-mt-24">
            <h2 class="text-4xl font-bold text-gray-800 mb-10 text-center">🍹 Minuman Segar</h2>
            <div v-if="minuman.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
               <div v-for="menu in minuman" :key="menu.id"
                   class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-2">
                <img :src="menu.image_url" :alt="menu.name" class="w-full h-60 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/FFFBEB/F59E0B?text=Gambar+Segera';">
                <div class="p-6 flex flex-col flex-grow">
                  <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ menu.name }}</h3>
                  <p class="text-gray-600 text-sm mb-5 flex-grow">{{ menu.description || 'Deskripsi tidak tersedia.' }}</p>
                  <p class="text-3xl font-bold text-amber-600 mb-5">{{ formatPrice(menu.price) }}</p>
                  <button :disabled="!menu.is_available"
                          :class="['w-full text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300 text-lg', menu.is_available ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-300 text-red-800 cursor-not-allowed']">
                    {{ menu.is_available ? 'Pesan Sekarang' : 'Stok Habis' }}
                  </button>
                </div>
              </div>
            </div>
             <div v-else class="text-center py-10 text-gray-500">
              <p>Menu minuman belum tersedia saat ini.</p>
            </div>
          </section>
        </div>
      </div>

      <!-- Bagian Lokasi -->
      <section id="lokasi" class="bg-white py-20 px-6 scroll-mt-24">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Kunjungi Kami</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Kami tunggu kedatangan Anda di lokasi kami yang nyaman dan strategis!</p>
            <p class="text-xl font-semibold">Jl. Kuliner Bahagia No. 123, Kota Enak</p>
            <p class="text-gray-600 mt-1">Buka Setiap Hari: 10:00 - 22:00</p>
            <div class="mt-10 max-w-5xl mx-auto">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666421683416!2d106.8249641750033!3d-6.17539239380977!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sNational%20Monument!5e0!3m2!1sen!2sid!4v1686150170094!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-2xl shadow-2xl"></iframe>
            </div>
        </div>
      </section>
    </main>

    <!-- ======================================================= -->
    <!-- ======================== FOOTER ========================= -->
    <!-- ======================================================= -->
    <footer class="bg-gray-800 text-white text-center py-10">
        <p>&copy; {{ new Date().getFullYear() }} Resto Enak. Dibuat dengan Penuh Rasa.</p>
    </footer>
  </div>
</template>

<script>
// Import Axios untuk mengambil data dari API
import axios from 'axios';

export default {
  // data() adalah tempat menyimpan semua "variabel" atau state komponen
  data() {
    return {
      allMenus: [],    // Array untuk menyimpan semua data menu dari Laravel
      isLoading: true, // Status untuk menampilkan animasi loading
      error: null,     // Untuk menyimpan pesan error jika terjadi masalah
    };
  },

  // computed adalah tempat untuk "variabel" turunan yang nilainya
  // dihitung berdasarkan data()
  computed: {
    makanan() {
      // Filter menu yang termasuk kategori makanan
      // (Ini contoh sederhana, idealnya ada field 'category' dari backend)
      return this.allMenus.filter(menu =>
          !menu.name.toLowerCase().includes('teh') &&
          !menu.name.toLowerCase().includes('kopi') &&
          !menu.name.toLowerCase().includes('jus') &&
          !menu.name.toLowerCase().includes('es')
      );
    },
    minuman() {
       // Filter menu yang termasuk kategori minuman
      return this.allMenus.filter(menu =>
          menu.name.toLowerCase().includes('teh') ||
          menu.name.toLowerCase().includes('kopi') ||
          menu.name.toLowerCase().includes('jus') ||
          menu.name.toLowerCase().includes('es')
      );
    }
  },

  // methods adalah tempat untuk semua fungsi yang akan kita gunakan
  methods: {
    // Fungsi untuk mengambil data menu dari API Laravel
    async fetchMenus() {
      this.isLoading = true; // Mulai loading
      this.error = null;     // Hapus error lama
      try {
        // Menggunakan Axios untuk request GET ke API kita
        const response = await axios.get('/api/public/menu');
        // Simpan data yang berhasil didapat ke variabel allMenus
        this.allMenus = response.data;
      } catch (err) {
        // Jika terjadi error, catat di console dan tampilkan pesan ke user
        console.error("Gagal mengambil data menu:", err);
        this.error = "Maaf, kami tidak bisa memuat menu saat ini. Coba lagi nanti ya.";
      } finally {
        // Apapun hasilnya (sukses atau gagal), hentikan loading
        this.isLoading = false;
      }
    },
    // Fungsi untuk format harga ke Rupiah
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
    }
  },

  // mounted() adalah fungsi yang dijalankan otomatis oleh Vue
  // TEPAT SETELAH komponen ini selesai "dipasang" ke halaman
  mounted() {
    // Jadi, saat halaman pertama kali dibuka, kita langsung panggil fungsi fetchMenus()
    this.fetchMenus();
  }
};
</script>

<style scoped>
/* Styling khusus untuk komponen ini jika diperlukan */
.scroll-mt-24 {
  scroll-margin-top: 96px; /* Sesuaikan dengan tinggi header sticky Anda */
}
</style>
