<?php
/** ฟอร์ม FAQ */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/faq/update/' . $item['id']) : url('admin/faq/store');
?>
<form method="post" action="<?= $action ?>">
  <?= Security::csrfField() ?>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">คำถาม *</label>
          <input type="text" name="question" class="form-control" value="<?= e($item['question'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">คำตอบ</label>
          <textarea name="answer" class="form-control" rows="5"><?= e($item['answer'] ?? '') ?></textarea></div>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">หมวดหมู่</label>
            <input type="text" name="category" class="form-control" value="<?= e($item['category'] ?? '') ?>"></div>
          <div class="col-md-4"><label class="form-label">ลำดับ</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($item['sort_order'] ?? 0) ?>"></div>
          <div class="col-md-4"><label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select></div>
        </div>
        <div class="mt-4"><button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
          <a href="<?= url('admin/faq') ?>" class="btn btn-link">ยกเลิก</a></div>
      </div>
    </div>
  </div>
</form>
