/* =========================================================
   APP ENTRY — Import toàn bộ JS
   ========================================================= */

import './bootstrap';

// ============================================================
// CSS của các thư viện
// ============================================================
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'aos/dist/aos.css';
import 'glightbox/dist/css/glightbox.min.css';

// ============================================================
// Alpine.js — phải import trước khi khởi tạo module
// ============================================================
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ============================================================
// Modules tùy chỉnh
// ============================================================
import { initHeader } from './modules/header';
import { initSlider } from './modules/slider';
import { initGallery } from './modules/gallery';
import { initScroll } from './modules/scroll';

// ============================================================
// Khởi tạo sau khi DOM sẵn sàng
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initSlider();
  initGallery();
  initScroll();
});