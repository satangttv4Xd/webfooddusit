<?php
/** ฟอร์มดาวน์โหลด */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/downloads/update/' . $item['id']) : url('admin/downloads/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อไฟล์ *</label>
          <input type="text" name="title" class="form-control" value="<?= e($item['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">คำอธิบาย</label>
          <textarea name="description" class="form-control" rows="2"><?= e($item['description'] ?? '') ?></textarea></div>
        <div class="row g-3 mb-3">
          <div class="col-md-6"><label class="form-label">หมวดหมู่</label>
            <input type="text" name="category" class="form-control" value="<?= e($item['category'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select></div>
        </div>
        <div class="mb-3"><label class="form-label">ไฟล์ <?= $isEdit ? '(เว้นว่างหากไม่เปลี่ยน)' : '*' ?></label>
          <input type="file" name="file" class="form-control" <?= $isEdit ? '' : 'required' ?>>
          <?php if ($isEdit && $item['file_path']): ?><small class="text-muted">ไฟล์ปัจจุบัน: <?= e(basename($item['file_path'])) ?></small><?php endif; ?>
          <small class="d-block text-muted">รองรับ: <?= ALLOWED_DOC_TYPES ?> (สูงสุด <?= MAX_UPLOAD_SIZE/1024/1024 ?> MB)</small></div>
        <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/downloads') ?>" class="btn btn-link">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
