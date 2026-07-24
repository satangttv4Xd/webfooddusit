<?php
/** ฟอร์มผู้ใช้ */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/users/update/' . $item['id']) : url('admin/users/store');
?>
<form method="post" action="<?= $action ?>">
  <?= Security::csrfField() ?>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">ชื่อ-นามสกุล *</label>
            <input type="text" name="full_name" class="form-control" value="<?= e($item['full_name'] ?? '') ?>" required></div>
          <div class="col-md-6"><label class="form-label">ตำแหน่ง</label>
            <input type="text" name="position" class="form-control" value="<?= e($item['position'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ชื่อผู้ใช้ (username) *</label>
            <input type="text" name="username" class="form-control" value="<?= e($item['username'] ?? '') ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
            <?php if ($isEdit): ?><small class="text-muted">ไม่สามารถเปลี่ยน username ได้</small><?php endif; ?></div>
          <div class="col-md-6"><label class="form-label">อีเมล *</label>
            <input type="email" name="email" class="form-control" value="<?= e($item['email'] ?? '') ?>" required></div>
          <div class="col-md-6"><label class="form-label">เบอร์โทร</label>
            <input type="text" name="phone" class="form-control" value="<?= e($item['phone'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">รหัสผ่าน <?= $isEdit ? '(เว้นว่างหากไม่เปลี่ยน)' : '*' ?></label>
            <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>></div>
          <div class="col-md-6"><label class="form-label">บทบาท (Role) *</label>
            <select name="role_id" class="form-select" required>
              <?php foreach ($roles as $r): ?><option value="<?= $r['id'] ?>" <?= ($item['role_id'] ?? '') == $r['id'] ? 'selected' : '' ?>><?= e($r['name']) ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-md-6"><label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select></div>
        </div>
        <div class="mt-4">
          <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
          <a href="<?= url('admin/users') ?>" class="btn btn-link">ยกเลิก</a>
        </div>
      </div>
    </div>
  </div>
</form>
