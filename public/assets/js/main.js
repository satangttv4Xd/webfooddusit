/* ============================================================
   สคริปต์หลักฝั่งผู้ใช้งาน (Public)
   ============================================================ */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    // ---------- AOS Animation ----------
    if (window.AOS) {
      AOS.init({ duration: 800, once: true, offset: 80, easing: 'ease-out-cubic' });
    }

    // ---------- Navbar เปลี่ยนเงาเมื่อเลื่อน ----------
    const nav = document.querySelector('.main-nav');
    const progress = document.getElementById('top-progress');
    const backTop = document.getElementById('back-to-top');

    window.addEventListener('scroll', function () {
      const scrollTop = window.scrollY;
      if (nav) nav.classList.toggle('scrolled', scrollTop > 50);
      if (backTop) backTop.classList.toggle('show', scrollTop > 400);

      // แถบความคืบหน้าการอ่าน
      if (progress) {
        const h = document.documentElement;
        const pct = (scrollTop / (h.scrollHeight - h.clientHeight)) * 100;
        progress.style.width = pct + '%';
      }
    });

    // ---------- Back to top ----------
    if (backTop) {
      backTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // ---------- Counter animation (Statistics) ----------
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length) {
      const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.4 });
      counters.forEach(function (c) { io.observe(c); });
    }

    function animateCounter(el) {
      const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
      const duration = 1600;
      const start = performance.now();
      function step(now) {
        const p = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.floor(eased * target).toLocaleString('th-TH');
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }

    // ---------- Dark / Light mode ----------
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      html.setAttribute('data-bs-theme', savedTheme);
      updateThemeIcon(savedTheme);
    }
    if (themeToggle) {
      themeToggle.addEventListener('click', function () {
        const current = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', current);
        localStorage.setItem('theme', current);
        updateThemeIcon(current);
      });
    }
    function updateThemeIcon(theme) {
      if (!themeToggle) return;
      themeToggle.innerHTML = theme === 'dark'
        ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
    }

    // ---------- Cookie consent ----------
    const cookie = document.getElementById('cookie-consent');
    const cookieAccept = document.getElementById('cookie-accept');
    if (cookie && !localStorage.getItem('cookie_accepted')) {
      setTimeout(function () { cookie.classList.add('show'); }, 1500);
    }
    if (cookieAccept) {
      cookieAccept.addEventListener('click', function () {
        localStorage.setItem('cookie_accepted', '1');
        cookie.classList.remove('show');
      });
    }

    // ---------- ลากเมาส์เพื่อเลื่อนสไลด์ (Hero Carousel) ----------
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel && window.bootstrap) {
      const bsCarousel = bootstrap.Carousel.getOrCreateInstance(heroCarousel);
      const THRESHOLD = 60;       // ระยะลากขั้นต่ำ (px) ถึงจะเปลี่ยนสไลด์
      let dragStartX = null;
      let dragging = false;

      heroCarousel.style.cursor = 'grab';
      // กันภาพถูกลากเป็น ghost image
      heroCarousel.querySelectorAll('img').forEach(function (img) {
        img.setAttribute('draggable', 'false');
      });
      heroCarousel.addEventListener('dragstart', function (e) { e.preventDefault(); });

      heroCarousel.addEventListener('pointerdown', function (e) {
        if (e.pointerType === 'touch') return; // ปล่อยให้ Bootstrap จัดการปัดนิ้ว
        dragStartX = e.clientX;
        dragging = true;
        heroCarousel.style.cursor = 'grabbing';
      });

      window.addEventListener('pointerup', function (e) {
        if (!dragging || dragStartX === null) return;
        const delta = e.clientX - dragStartX;
        dragging = false;
        dragStartX = null;
        heroCarousel.style.cursor = 'grab';
        if (Math.abs(delta) > THRESHOLD) {
          if (delta < 0) bsCarousel.next();
          else bsCarousel.prev();
        }
      });
    }

    // ---------- เมนูย่อยซ้อน (submenu) เปิดด้วยการคลิก ----------
    document.querySelectorAll('.main-nav .dropdown-submenu > .dropdown-toggle').forEach(function (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var submenu = this.nextElementSibling;
        if (!submenu) return;
        // ปิดเมนูย่อยอื่นในระดับเดียวกัน
        var siblings = this.parentElement.parentElement.querySelectorAll('.dropdown-menu.show');
        siblings.forEach(function (s) { if (s !== submenu) s.classList.remove('show'); });
        submenu.classList.toggle('show');
      });
    });
    // ปิดเมนูย่อยเมื่อ dropdown หลักถูกปิด
    document.querySelectorAll('.main-nav .dropdown').forEach(function (dd) {
      dd.addEventListener('hidden.bs.dropdown', function () {
        this.querySelectorAll('.dropdown-menu.show').forEach(function (s) { s.classList.remove('show'); });
      });
    });

    // ---------- Flash message เป็น SweetAlert (ถ้ามี data) ----------
    // (จัดการผ่าน alert Bootstrap ในหน้าอยู่แล้ว)

  });
})();
