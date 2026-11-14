<?php
/** ฟอร์มบทบาท (Role) + เลือกสิทธิ์ */
$isEdit = $item !== null;
$isSuper = $isEdit && $item['slug'] === 'super_admin';
$action = $isEdit ? url('admin/roles/update/' . $item['id']) : url('admin/roles/store');

// ชื่อโมดูลภาษาไทย (fallback เป็นชื่อ slug ถ้าไม่มีในแมป)
$moduleLabels = [
    'news' => 'ข่าว', 'programs' => 'หลักสูตร', 'events' => 'กิจกรรม', 'schedules' => 'ปฏิทินงาน',
    'gallery' => 'แกลเลอรี', 'downloads' => 'ดาวน์โหลด', 'banner' => 'แบนเนอร์',
    'pages' => 'หน้าเนื้อหา', 'users' => 'ผู้ใช้งาน', 'roles' => 'บทบาท/สิทธิ์',
    'settings' => 'ตั้งค่า', 'contact' => 'ข้อความติดต่อ',
];
$checked = $checked ?? [];
?>
<form method="post" action="<?= $action ?>">
  <?= Security::csrfField() ?>
  <div class="row g-4">
    <!-- ข้อมูลบทบาท -->
    <div class="col-lg-4">
      <div class="card p-4">
        <h6 class="mb-3"><i class="fa-solid fa-id-badge me-1"></i> ข้อมูลบทบาท</h6>
        <div class="mb-3">
          <label class="form-label">ชื่อบทบาท *</label>
          <input type="text" name="name" class="form-control" value="<?= e($item['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">รหัส (slug) *</label>
          <input type="text" name="slug" class="form-control" value="<?= e($item['slug'] ?? '') ?>"
                 placeholder="เช่น content_editor" <?= $isSuper ? 'readonly' : 'required' ?>>
          <small class="text-muted">a-z, 0-9 และ _ เท่านั้น<?= $isSuper ? ' — บทบาทระบบ แก้ไขไม่ได้' : '' ?></small>
        </div>
        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <textarea name="description" class="form-control" rows="3"><?= e($item['description'] ?? '') ?></textarea>
        </div>
        <div class="mb-1">
          <label class="form-label">สถานะ</label>
          <select name="status" class="form-select">
            <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
            <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
          </select>
        </div>
      </div>
    </div>

    <!-- สิทธิ์การใช้งาน -->
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><i class="fa-solid fa-key me-1"></i> สิทธิ์การใช้งาน (Permissions)</h6>
          <?php if (!$isSuper): ?>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" id="checkAll">
              <label class="form-check-label small" for="checkAll">เลือกทั้งหมด</label>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($isSuper): ?>
          <div class="alert alert-secondary mb-0"><i class="fa-solid fa-circle-info me-1"></i>
            บทบาท <strong>ผู้ดูแลระบบสูงสุด</strong> เข้าถึงได้ทุกสิทธิ์โดยอัตโนมัติ ไม่จำเป็นต้องกำหนด</div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($permissions as $module => $perms): ?>
              <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                  <div class="fw-500 mb-2 text-primary">
                    <i class="fa-solid fa-folder me-1"></i> <?= e($moduleLabels[$module] ?? $module) ?>
                  </div>
                  <?php foreach ($perms as $p): ?>
                    <div class="form-check">
                      <input class="form-check-input perm-check" type="checkbox" name="permissions[]"
                             value="<?= (int)$p['id'] ?>" id="perm<?= (int)$p['id'] ?>"
                             <?= in_array((int)$p['id'], $checked, true) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="perm<?= (int)$p['id'] ?>">
                        <?= e($p['name']) ?> <small class="text-muted">(<?= e($p['slug']) ?>)</small>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
    <a href="<?= url('admin/roles') ?>" class="btn btn-link">ยกเลิก</a>
  </div>
</form>

<script>
document.getElementById('checkAll')?.addEventListener('change', function () {
  document.querySelectorAll('.perm-check').forEach(cb => { cb.checked = this.checked; });
});
</script>
