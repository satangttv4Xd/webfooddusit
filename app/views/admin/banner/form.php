<?php
/** ฟอร์มแบนเนอร์ */
$isEdit = $banner !== null;
$action = $isEdit ? url('admin/banner/update/' . $banner['id']) : url('admin/banner/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">หัวข้อ</label>
          <input type="text" name="title" class="form-control" value="<?= e($banner['title'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">คำบรรยาย</label>
          <textarea name="subtitle" class="form-control" rows="2"><?= e($banner['subtitle'] ?? '') ?></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">ลิงก์ปลายทาง</label>
            <input type="text" name="link_url" class="form-control" placeholder="เช่น programs" value="<?= e($banner['link_url'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ข้อความปุ่ม</label>
            <input type="text" name="button_text" class="form-control" value="<?= e($banner['button_text'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ลำดับ</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($banner['sort_order'] ?? 0) ?>"></div>
          <div class="col-md-6"><label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($banner['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($banner['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select></div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card p-4">
        <label class="form-label">รูปแบนเนอร์ <?= $isEdit ? '' : '*' ?> (แนะนำ 1600×780)</label>
        <img src="<?= upload_url($banner['image'] ?? '') ?>" id="imgPreview" class="img-fluid rounded mb-2" style="max-height:200px;object-fit:cover;width:100%">
        <input type="file" name="image" class="form-control" accept="image/*" data-preview="#imgPreview" <?= $isEdit ? '' : 'required' ?>>
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/banner') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
