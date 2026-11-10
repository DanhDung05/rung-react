/* =========================================================
   HEADER MODULE — Sticky header + mobile menu toggle
   ========================================================= */

export function initHeader() {
  const header = document.querySelector('[data-header]');
  if (!header) return;

  // Sticky header khi scroll
  const onScroll = () => {
    if (window.scrollY > 50) {
      header.classList.add('header-scrolled');
    } else {
      header.classList.remove('header-scrolled');
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Mobile menu toggle (dùng Alpine.js hoặc JS thuần)
  const menuToggle = document.querySelector('[data-mobile-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('hidden');
      menuToggle.setAttribute('aria-expanded', String(!isOpen));
    });
  }
}