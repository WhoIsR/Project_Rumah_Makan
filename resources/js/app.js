// resources/js/app.js
import './bootstrap'; // Biarkan ini jika ada (biasanya untuk Echo, dll)
import { createApp } from 'vue';

// Import komponen Vue utama untuk landing page customer
import CustomerLandingPage from './components/CustomerLandingPage.vue';

// Buat aplikasi Vue dan mount ke elemen dengan id #app-customer
// Kita akan buat elemen ini nanti di file Blade
const app = createApp({});
app.component('customer-landing-page', CustomerLandingPage);
app.mount('#app-customer'); // Pastikan ID ini unik dan akan kita buat di Blade
