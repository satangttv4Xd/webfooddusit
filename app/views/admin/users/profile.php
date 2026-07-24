<?php /** โปรไฟล์ของฉัน */ ?>
<form method="post" action="<?= url('admin/users/saveProfile') ?>">
  <?= Security::csrfField() ?>
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card p-4 text-center mb-3">
        <span class="avatar-circle mx-auto mb-2" style="width:80px;height:80px;font-size:2rem"><?= mb_substr($item['full_name'], 0, 1) ?></span>
        <h5 class="mb-0"><?= e($item['full_name']) ?></h5>
        <small class="text-muted"><?= e($item['position'] ?: 'ผู้ใช้งานระบบ') ?></small>
      </div>
      <div class="card p-4">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" name="full_name" class="form-control" value="<?= e($item['full_name']) ?>" required></div>
          <div class="col-md-6"><label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" value="<?= e($item['email']) ?>" required></div>
          <div class="col-md-6"><label class="form-label">เบอร์โทร</label>
            <input type="text" name="phone" class="form-control" value="<?= e($item['phone']) ?>"></div>
          <div class="col-md-6"><label class="form-label">รหัสผ่านใหม่ (เว้นว่างหากไม่เปลี่ยน)</label>
            <input type="password" name="password" class="form-control"></div>
        </div>
        <div class="mt-4"><button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึกโปรไฟล์</button></div>
      </div>
    </div>
  </div>
</form>
