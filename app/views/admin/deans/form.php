<?php
/** ฟอร์มทำเนียบคณบดี */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/deans/update/' . $item['id']) : url('admin/deans/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อ-นามสกุล *</label>
          <input type="text" name="full_name" class="form-control" placeholder="เช่น ผศ.ดร.กนกกานต์ วีระกุล" value="<?= e($item['full_name'] ?? '') ?>" required></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">ตำแหน่ง</label>
            <input type="text" name="position" class="form-control" value="<?= e($item['position'] ?? 'คณบดี') ?>"></div>
          <div class="col-md-6"><label class="form-label">ช่วงเวลาการดำรงตำแหน่ง</label>
            <input type="text" name="term" class="form-control" placeholder="เช่น 28 มิถุนายน 2562 – ปัจจุบัน" value="<?= e($item['term'] ?? '') ?>"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-4">
        <label class="form-label">รูปภาพ (แนะนำสี่เหลี่ยมจัตุรัส)</label>
        <img src="<?= upload_url($item['photo'] ?? '') ?>" id="imgPreview" class="rounded-circle mb-2 mx-auto d-block" width="120" height="120" style="object-fit:cover">
        <input type="file" name="photo" class="form-control" accept="image/*" data-preview="#imgPreview">
      </div>
      <div class="card p-4 mt-3">
        <div class="row g-3">
          <div class="col-6"><label class="form-label">ลำดับ</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($item['sort_order'] ?? 0) ?>"></div>
          <div class="col-6"><label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select></div>
        </div>
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/deans') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
