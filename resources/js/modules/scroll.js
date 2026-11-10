/* =========================================================
   SCROLL MODULE — Khởi tạo AOS (Animate On Scroll)
   ========================================================= */

import AOS from 'aos';

export function initScroll() {
  AOS.init({
    duration: 600,
    easing: 'ease-in-out',
    once: true,
    offset: 80,
    delay: 0,
    mirror: false,
    anchorPlacement: 'top-bottom',
  });

  // Re-init AOS khi có thay đổi DOM (cho AJAX)
  window.addEventListener('load', () => {
    AOS.refresh();
  });
}