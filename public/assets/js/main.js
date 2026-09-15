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

    // ---------- เมนูหลักเปิดด้วยการชี้เมาส์ (hover) เฉพาะจอใหญ่ ----------
    var hoverMql = window.matchMedia('(min-width: 992px)');
    var mainDropdowns = [];
    document.querySelectorAll('.main-nav .navbar-nav > .nav-item.dropdown').forEach(function (item) {
      var toggle = item.querySelector('[data-bs-toggle="dropdown"]');
      if (!toggle || typeof bootstrap === 'undefined') return;
      var dd = bootstrap.Dropdown.getOrCreateInstance(toggle);
      var closeTimer;
      item.addEventListener('mouseenter', function () {
        if (!hoverMql.matches) return;
        clearTimeout(closeTimer);
        // ปิดเมนูหลักอื่นที่เปิดค้างก่อน (Bootstrap ไม่ปิดให้เองเมื่อสั่ง show() จากโค้ด)
        mainDropdowns.forEach(function (o) { if (o.dd !== dd) o.dd.hide(); });
        dd.show();
      });
      item.addEventListener('mouseleave', function () {
        if (!hoverMql.matches) return;
        closeTimer = setTimeout(function () { dd.hide(); }, 150);
      });
      mainDropdowns.push({ item: item, dd: dd });
    });

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

    // ---------- ผู้ช่วยตอบคำถาม (Chatbot) ----------
    (function initChat() {
      var toggle = document.getElementById('chat-toggle');
      var box    = document.getElementById('chat-box');
      var body   = document.getElementById('chat-body');
      var form   = document.getElementById('chat-form');
      var input  = document.getElementById('chat-text');
      if (!toggle || !box || !body || !form || !input) return;

      function openChat(open) {
        box.classList.toggle('open', open);
        toggle.classList.toggle('hidden', open);
        if (open) input.focus();
      }
      toggle.addEventListener('click', function () { openChat(true); });
      document.getElementById('chat-close')?.addEventListener('click', function () { openChat(false); });

      /** เพิ่มข้อความลงกล่องแชท */
      function addMsg(text, who) {
        var el = document.createElement('div');
        el.className = 'chat-msg ' + who;
        el.textContent = text;
        body.appendChild(el);
        body.scrollTop = body.scrollHeight;
        return el;
      }

      /** เพิ่มลิงก์แนะนำ */
      function addLinks(links) {
        if (!links || !links.length) return;
        var wrap = document.createElement('div');
        wrap.className = 'chat-links';
        links.forEach(function (l) {
          var a = document.createElement('a');
          a.href = l.url;
          a.textContent = l.title;
          if (/^https?:\/\//i.test(l.url) && l.url.indexOf(location.host) === -1) {
            a.target = '_blank';
            a.rel = 'noopener';
          }
          wrap.appendChild(a);
        });
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
      }

      /** ส่งคำถามไปยังเซิร์ฟเวอร์ */
      function send(text) {
        text = (text || '').trim();
        if (!text) return;
        addMsg(text, 'user');
        input.value = '';

        var typing = addMsg('กำลังค้นหาข้อมูล...', 'bot typing');

        fetch(window.CHAT_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: 'message=' + encodeURIComponent(text)
        })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            typing.remove();
            addMsg(data.reply || 'ขออภัยครับ เกิดข้อผิดพลาด', 'bot');
            addLinks(data.links);
          })
          .catch(function () {
            typing.remove();
            addMsg('ขออภัยครับ ไม่สามารถเชื่อมต่อได้ กรุณาลองใหม่อีกครั้ง', 'bot');
          });
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        send(input.value);
      });

      // ปุ่มคำถามยอดนิยม
      body.addEventListener('click', function (e) {
        var chip = e.target.closest('.chat-chip');
        if (chip) send(chip.dataset.q);
      });

      // ปิดด้วยปุ่ม Esc
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && box.classList.contains('open')) openChat(false);
      });
    })();

    // ---------- เช็คเนื้อหาใหม่เป็นระยะ แล้วรีโหลดหน้าอัตโนมัติเฉพาะตอนมีการเปลี่ยนแปลงจริง ----------
    // ไม่ใช่ real-time เป๊ะ ๆ (เช็คทุก 30 วิ) แต่ผู้ใช้ไม่ต้องกด F5 เอง เมื่อแอดมินเพิ่ม/แก้/ลบเนื้อหา
    (function () {
      var pingUrl = window.PING_URL;
      if (!pingUrl) return;

      var POLL_MS = 30000;
      var baseline = null;

      function isUserBusy() {
        var el = document.activeElement;
        var typing = el && ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName);
        var modalOpen = document.querySelector('.modal.show') !== null;
        return typing || modalOpen;
      }

      function check() {
        fetch(pingUrl, { cache: 'no-store' })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            var ts = data && data.ts;
            if (!ts) return;
            if (baseline === null) { baseline = ts; return; } // ครั้งแรก: จำไว้เป็นฐาน ยังไม่รีโหลด
            if (ts > baseline) {
              if (isUserBusy()) return; // รอรอบถัดไป ไม่รบกวนตอนกำลังพิมพ์/เปิด modal อยู่
              var note = document.createElement('div');
              note.textContent = 'มีข้อมูลใหม่ กำลังอัปเดตหน้า...';
              note.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);'
                + 'background:var(--primary,#0F4C81);color:#fff;padding:.6rem 1.2rem;border-radius:30px;'
                + 'font-size:.9rem;z-index:2000;box-shadow:0 8px 30px rgba(0,0,0,.2)';
              document.body.appendChild(note);
              setTimeout(function () { location.reload(); }, 700);
            }
          })
          .catch(function () { /* เงียบ ๆ ถ้าเช็คไม่สำเร็จ รอรอบถัดไป */ });
      }

      check(); // เช็คทันทีตอนโหลดหน้า เพื่อจับเวลาตั้งต้นให้ถูกจุด (ไม่ต้องรอรอบแรกของ setInterval)

      setInterval(check, POLL_MS);
    })();

  });
})();
