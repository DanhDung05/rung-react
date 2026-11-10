/* =========================================================
   SLIDER MODULE — Khởi tạo Swiper cho các element [data-swiper]
   ========================================================= */

import Swiper from 'swiper';
import { Autoplay, Navigation, Pagination, EffectFade } from 'swiper/modules';

export function initSlider() {
  const sliders = document.querySelectorAll('[data-swiper]');
  if (!sliders.length) return;

  sliders.forEach((el) => {
    const type = el.dataset.swiper || 'default';

    const configs = {
      // Hero slider — fade effect + counter
      hero: {
        modules: [Autoplay, Navigation, Pagination, EffectFade],
        effect: 'fade',
        fadeEffect: { crossFade: true },
        loop: true,
        speed: 1000,
        autoplay: { delay: 6000, disableOnInteraction: false },
        pagination: {
          el: el.parentElement.querySelector('.swiper-pagination'),
          type: 'fraction',
          clickable: true,
        },
        navigation: {
          nextEl: el.parentElement.querySelector('.swiper-button-next'),
          prevEl: el.parentElement.querySelector('.swiper-button-prev'),
        },
        on: {
          init: function (swiper) {
            updateHeroProgress(swiper);
          },
          slideChange: function (swiper) {
            updateHeroProgress(swiper);
          },
          autoplayTimeLeft(s, time, progress) {
            const progressBar = el.parentElement.querySelector('.swiper-progress-bar');
            if (progressBar) {
              progressBar.style.height = `${(1 - progress) * 100}%`;
            }
          }
        }
      },

      // ... (Giữ nguyên phần gallery và default như cũ) ...
      gallery: {
        modules: [Autoplay, Navigation, Pagination],
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        slidesPerView: 1,
        spaceBetween: 16,
        breakpoints: {
          640:  { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        },
        pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        navigation: { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') },
      },

      default: {
        modules: [Navigation, Pagination],
        loop: false,
        slidesPerView: 1,
        spaceBetween: 16,
        pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        navigation: { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') },
      },
    };

    new Swiper(el, configs[type] || configs.default);
  });
}

// Helper cập nhật thanh tiến trình khi chuyển slide
function updateHeroProgress(swiper) {
  const progressBar = swiper.el.parentElement.querySelector('.swiper-progress-bar');
  if (progressBar) {
    progressBar.style.height = '0%';
  }
}