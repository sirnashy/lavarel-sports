/**
 * SportStream — Main JavaScript
 */
(function () {
  'use strict';

  // ── Navbar scroll effect
  const nav = document.getElementById('main-nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
  }

  // ── Smooth tooltip init
  const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  if (tooltips.length && typeof bootstrap !== 'undefined') {
    tooltips.forEach(el => new bootstrap.Tooltip(el));
  }

  // ── Match card lazy loading images
  if ('IntersectionObserver' in window) {
    const imgs = document.querySelectorAll('img[loading="lazy"]');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) { img.src = img.dataset.src; }
          io.unobserve(img);
        }
      });
    });
    imgs.forEach(img => io.observe(img));
  }

  // ── Mobile sidebar toggle
  document.getElementById('sidebar-toggle')?.addEventListener('click', function () {
    const sidebar = document.getElementById('admin-sidebar');
    if (sidebar) sidebar.classList.toggle('show');
  });

  // ── Auto-dismiss alerts
  setTimeout(() => {
    document.querySelectorAll('.alert.fade.show').forEach(el => {
      const alert = bootstrap.Alert.getOrCreateInstance(el);
      alert?.close();
    });
  }, 5000);

  // ── Active link detection
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-link[href]').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
    }
  });

})();