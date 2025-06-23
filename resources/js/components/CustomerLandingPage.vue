<template>
  <div class="min-h-screen bg-stone-100 font-sans antialiased">

    

    <main>
      <!-- HERO SECTION -->
      <section class="h-[90vh] bg-cover bg-center bg-fixed relative overflow-hidden flex items-center justify-center text-center text-white" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1770&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/60 z-0"></div>

        <!-- Teks Berjalan Diagonal 1 -->
        <div class="marquee-container marquee-diagonal-1">
          <div class="marquee-text">
            <span>★ RESEP WARISAN</span><span>★ SAMBALNYA NAMPOL</span><span>★ DIJAMIN ENAK</span>
            <span>★ RESEP WARISAN</span><span>★ SAMBALNYA NAMPOL</span><span>★ DIJAMIN ENAK</span>
          </div>
        </div>
        <!-- Teks Berjalan Diagonal 2 -->
        <div class="marquee-container marquee-diagonal-2">
          <div class="marquee-text reverse">
            <span>★ MENU ANDALAN</span><span>★ BAHAN SEGAR</span><span>★ HARGA PAS</span>
            <span>★ MENU ANDALAN</span><span>★ BAHAN SEGAR</span><span>★ HARGA PAS</span>
          </div>
        </div>

        <div class="relative z-10">
          <h2 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-tight" style="font-family: 'Playfair Display', serif; text-shadow: 0 3px 10px rgba(0,0,0,0.5);">Sajian Otentik,<br>Untuk Momen Terbaik.</h2>
          <p class="mt-4 text-lg md:text-xl text-stone-300 max-w-xl mx-auto">Setiap resep adalah perjalanan rasa, setiap hidangan adalah perayaan. Selamat datang di rumah.</p>
        </div>
      </section>

      <!-- KONTEN UTAMA DENGAN LATAR BELAKANG PUTIH -->
      <div class="bg-white relative z-20">

        <!-- KEUNGGULAN KAMI -->
        <section id="keunggulan" class="py-20 md:py-24 border-b border-stone-200 scroll-mt-20">
          <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-12 text-center">
              <div class="feature-item">
                <div class="icon-wrapper"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg></div>
                <h3 class="text-xl font-bold text-stone-800 mt-4 mb-2" style="font-family: 'Playfair Display', serif;">Resep Warisan</h3>
                <p class="text-stone-600 text-sm leading-relaxed">Rasa otentik yang kami jaga dari generasi ke generasi untuk kenikmatan yang tak lekang oleh waktu.</p>
              </div>
              <div class="feature-item">
                 <div class="icon-wrapper"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg></div>
                <h3 class="text-xl font-bold text-stone-800 mt-4 mb-2" style="font-family: 'Playfair Display', serif;">Bahan Segar Pilihan</h3>
                <p class="text-stone-600 text-sm leading-relaxed">Kami bekerja sama dengan petani lokal untuk menyajikan bahan-bahan segar dan berkualitas terbaik setiap hari.</p>
              </div>
              <div class="feature-item">
                 <div class="icon-wrapper"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg></div>
                <h3 class="text-xl font-bold text-stone-800 mt-4 mb-2" style="font-family: 'Playfair Display', serif;">Suasana Hangat</h3>
                <p class="text-stone-600 text-sm leading-relaxed">Tempat yang sempurna untuk berkumpul, berbagi cerita, dan menciptakan momen indah bersama orang terkasih.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- MENU ANDALAN -->
        <section id="menu" class="py-20 md:py-24 bg-stone-100 scroll-mt-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12 section-header">
                    <h2 class="text-4xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">Menu Andalan Kami</h2>
                    <p class="mt-2 text-stone-600">Hidangan yang paling dicintai oleh para pelanggan setia kami.</p>
                </div>
                <div v-if="isLoading || error" class="text-center py-10">...</div>
                <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="menu in featuredMenus" :key="menu.id" class="menu-card bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="relative">
                            <img :src="menu.image_url" :alt="menu.name" class="w-full h-64 object-cover">
                            <span v-if="!menu.is_available" class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1.5 m-3 rounded-full uppercase tracking-wider">Habis</span>
                        </div>
                        <div class="p-6">
                            <span v-if="menu.category" class="text-xs font-semibold uppercase tracking-wider text-amber-700">{{ menu.category.name }}</span>
                            <h4 class="text-2xl font-bold mt-1 text-stone-800" style="font-family: 'Playfair Display', serif;">{{ menu.name }}</h4>
                            <p class="text-sm text-stone-600 mt-2 h-16">{{ menu.description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <p class="text-xl font-bold text-stone-900">{{ formatPrice(menu.price) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONI -->
        <section id="testimoni" class="py-20 md:py-24 bg-white scroll-mt-20">
            <div class="container mx-auto px-6 text-center">
                 <div class="section-header mb-12">
                    <h2 class="text-4xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">Kata Mereka</h2>
                    <p class="mt-2 text-stone-600">Kami senang bisa menjadi bagian dari momen bahagiamu.</p>
                 </div>
                 <div class="grid md:grid-cols-3 gap-8">
                    <div class="testimonial-card bg-stone-100 p-8 rounded-lg">
                        <p class="text-stone-600 italic">"Sambalnya juara! Bener-bener rasa otentik yang bikin kangen rumah. Tempatnya juga nyaman buat kumpul bareng teman-teman."</p>
                        <p class="mt-6 font-bold text-stone-800">- Budi Santoso</p>
                    </div>
                    <div class="testimonial-card bg-stone-100 p-8 rounded-lg md:transform md:scale-110 md:shadow-xl z-10">
                        <p class="text-stone-600 italic">"Ayam Gepreknya the best! Krispi di luar, empuk di dalam. Selalu jadi pilihan utama buat makan siang. Highly recommended!"</p>
                        <p class="mt-6 font-bold text-stone-800">- Citra Lestari</p>
                    </div>
                    <div class="testimonial-card bg-stone-100 p-8 rounded-lg">
                        <p class="text-stone-600 italic">"Pertama kali coba Sop Buntutnya dan langsung jatuh cinta. Kuahnya kaya rempah dan dagingnya lembut banget. Pasti balik lagi!"</p>
                        <p class="mt-6 font-bold text-stone-800">- Doni Firmansyah</p>
                    </div>
                 </div>
            </div>
        </section>

        <!-- LOKASI -->
        <section id="lokasi" class="py-20 md:py-24 bg-stone-800 text-white scroll-mt-20">
             <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold" style="font-family: 'Playfair Display', serif;">Temukan Kami</h2>
                <p class="mt-2 text-stone-300 max-w-xl mx-auto">Kami tunggu kedatangan Anda untuk menikmati sajian terbaik dari dapur kami.</p>
                <div class="mt-8">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666421683416!2d106.8249641750033!3d-6.17539239380977!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sNational%20Monument!5e0!3m2!1sen!2sid!4v1686150170094!5m2!1sen!2sid" width="100%" height="450" class="rounded-lg shadow-lg filter grayscale-[1]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
             </div>
        </section>

      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import { nextTick } from 'vue';

export default {
  data() {
    return {
      allMenus: [],
      isLoading: true,
      error: null,
      observer: null,
    };
  },

  computed: {
    // Kita hanya tampilkan 3 menu sebagai andalan, yang ada fotonya
    featuredMenus() {
        return this.allMenus.filter(menu => menu.image_url).slice(0, 3);
    }
  },

  methods: {
    async fetchMenus() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/public/menu');
        this.allMenus = response.data;
      } catch (err) {
        this.error = "Maaf, kami tidak bisa memuat menu saat ini.";
      } finally {
        this.isLoading = false;
        this.setupObservers();
      }
    },
    formatPrice(price) {
      return "Rp " + new Intl.NumberFormat('id-ID').format(price);
    },
    // Fungsi ini akan menjalankan semua animasi saat scroll
    setupObservers() {
      nextTick(() => {
        const animatedElements = document.querySelectorAll('.feature-item, .section-header, .menu-card, .testimonial-card');
        if (this.observer) this.observer.disconnect();

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    this.observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: "0px 0px -100px 0px" }); // Animasi berjalan sedikit sebelum elemen benar-benar terlihat

        animatedElements.forEach(el => {
            el.classList.remove('visible');
            this.observer.observe(el);
        });
      });
    }
  },

  mounted() {
    this.fetchMenus();

    // Menambahkan font secara dinamis
    const fontLink = document.createElement('link');
    fontLink.href = "https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;700&display=swap";
    fontLink.rel = 'stylesheet';
    document.head.appendChild(fontLink);
  }
};
</script>

<style scoped>
/* ======================================================= */
/* =========== TEMPAT SEMUA KEAJAIBAN ANIMASI ============== */
/* ======================================================= */

/* Animasi untuk Teks Berjalan Menyilang */
@keyframes scroll-left {
    from { transform: translateX(0%); }
    to { transform: translateX(-50%); }
}
.marquee-container {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 2px;
    font-size: clamp(2.5rem, 6vw, 5rem);
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.1);
    white-space: nowrap;
    position: absolute;
    width: 150%;
    z-index: 15;
}
.marquee-text {
    display: flex;
    width: max-content;
    animation: scroll-left 80s linear infinite;
}
.marquee-text span { margin: 0 2rem; }
.marquee-text.reverse { animation-direction: reverse; }

/* Posisi & Rotasi */
.marquee-diagonal-1 {
    top: 15%;
    left: -25%;
    transform: rotate(-8deg);
}
.marquee-diagonal-2 {
    bottom: 15%;
    left: -25%;
    transform: rotate(8deg);
}

/* Animasi Elemen saat Scroll */
.feature-item, .section-header, .menu-card, .testimonial-card {
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 0.8s cubic-bezier(0.165, 0.84, 0.44, 1), transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.feature-item.visible, .section-header.visible, .menu-card.visible, .testimonial-card.visible {
    opacity: 1;
    transform: translateY(0);
}
/* Menambahkan delay animasi agar muncul berurutan */
.feature-item:nth-child(2), .testimonial-card:nth-child(2) { transition-delay: 0.1s; }
.feature-item:nth-child(3), .testimonial-card:nth-child(3) { transition-delay: 0.2s; }
.menu-card:nth-child(2) { transition-delay: 0.1s; }
.menu-card:nth-child(3) { transition-delay: 0.2s; }

/* Ikon untuk Keunggulan */
.icon-wrapper {
    @apply w-16 h-16 bg-amber-100 text-amber-600 rounded-full inline-flex items-center justify-center;
}
</style>
