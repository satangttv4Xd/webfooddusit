<?php /** หน้าเข้าสู่ระบบ */ ?>
<div class="login-card">
  <div class="text-center mb-4">
    <img src="<?= asset('images/sdu-logo.png') ?>" alt="โลโก้มหาวิทยาลัยสวนดุสิต" style="height:80px;width:auto;margin-bottom:.5rem">
    <h4 class="mb-1" style="color:var(--primary)">เข้าสู่ระบบ</h4>
    <p class="text-muted small mb-0">ระบบจัดการเว็บไซต์โรงเรียนการเรือน</p>
  </div>

  <form method="post" action="<?= url('admin/auth/authenticate') ?>">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small fw-500">ชื่อผู้ใช้ หรือ อีเมล</label>
      <div class="input-icon">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="login" class="form-control" placeholder="username" required autofocus>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small fw-500">รหัสผ่าน</label>
      <div class="input-icon">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" required>
      </div>
      <div id="capsLockWarn" class="text-warning small mt-1 d-none">
        <i class="fa-solid fa-triangle-exclamation me-1"></i>เปิด Caps Lock อยู่
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">จดจำฉันไว้</label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2">
      <i class="fa-solid fa-right-to-bracket me-1"></i> เข้าสู่ระบบ
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="<?= url('') ?>" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>กลับสู่เว็บไซต์</a>
  </div>

  <div class="alert alert-light border mt-4 mb-0 small">
    <strong>บัญชีทดสอบ:</strong><br>
    superadmin / admin123 &nbsp;•&nbsp; editor / editor123
  </div>
</div>

<script>
  // แจ้งเตือนเมื่อเปิด Caps Lock ขณะพิมพ์รหัสผ่าน
  (function () {
    var pwd = document.getElementById('loginPassword');
    var warn = document.getElementById('capsLockWarn');
    if (!pwd || !warn) return;
    function toggleCaps(e) {
      var on = e.getModifierState && e.getModifierState('CapsLock');
      warn.classList.toggle('d-none', !on);
    }
    pwd.addEventListener('keydown', toggleCaps);
    pwd.addEventListener('keyup', toggleCaps);
    // ซ่อนเมื่อออกจากช่อง
    pwd.addEventListener('blur', function () { warn.classList.add('d-none'); });
  })();
</script>
