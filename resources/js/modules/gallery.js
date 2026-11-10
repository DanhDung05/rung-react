/* =========================================================
   GALLERY MODULE — Khởi tạo GLightbox cho [data-gallery]
   ========================================================= */

import GLightbox from 'glightbox';

export function initGallery() {
  const galleries = document.querySelectorAll('[data-gallery]');
  if (!galleries.length) return;

  galleries.forEach((el) => {
    // Nếu element là container có nhiều ảnh
    const links = el.querySelectorAll('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".webp"]');

    if (links.length) {
      GLightbox({
        selector: `#${el.id} a[href$=".jpg"], #${el.id} a[href$=".jpeg"], #${el.id} a[href$=".png"], #${el.id} a[href$=".webp"]`,
        touchNavigation: true,
        loop: true,
        zoomable: true,
        draggable: true,
      });
    }
  });
}