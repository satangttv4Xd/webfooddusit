/* ============================================================
   สคริปต์ระบบหลังบ้าน (Admin)
   ============================================================ */
(function () {
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {

    // ---------- Toggle sidebar (มือถือ) ----------
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
      toggle.addEventListener('click', function () { sidebar.classList.toggle('show'); });
      document.addEventListener('click', function (e) {
        if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('show');
        }
      });
    }

    // ---------- Dark mode ----------
    const themeToggle = document.getElementById('adminThemeToggle');
    const html = document.documentElement;
    const saved = localStorage.getItem('admin-theme');
    if (saved) html.setAttribute('data-bs-theme', saved);
    if (themeToggle) {
      themeToggle.addEventListener('click', function () {
        const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('admin-theme', next);
      });
    }

    // ---------- ยืนยันก่อนลบ (SweetAlert2) ----------
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        const url = el.getAttribute('href') || el.dataset.url;
        const msg = el.dataset.confirm || 'คุณต้องการดำเนินการนี้หรือไม่?';
        Swal.fire({
          title: 'ยืนยันการทำรายการ',
          text: msg,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc2626',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'ยืนยัน',
          cancelButtonText: 'ยกเลิก',
        }).then(function (result) {
          if (result.isConfirmed) window.location.href = url;
        });
      });
    });

    // ---------- Preview รูปก่อนอัปโหลด ----------
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
      input.addEventListener('change', function () {
        const target = document.querySelector(input.dataset.preview);
        if (target && input.files[0] && input.files[0].type.startsWith('image')) {
          target.src = URL.createObjectURL(input.files[0]);
          target.style.display = 'block';
        }
      });
    });

    // ---------- สร้าง slug อัตโนมัติจากช่องชื่อ ----------
    const titleInput = document.querySelector('[data-slug-source]');
    const slugInput = document.querySelector('[data-slug-target]');
    if (titleInput && slugInput) {
      titleInput.addEventListener('input', function () {
        if (slugInput.dataset.touched) return;
        slugInput.value = titleInput.value.trim().toLowerCase()
          .replace(/[^\p{L}\p{N}\s-]/gu, '').replace(/\s+/g, '-');
      });
      slugInput.addEventListener('input', function () { slugInput.dataset.touched = '1'; });
    }

  });
})();
