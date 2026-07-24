<?php
/** ฟอร์มเพิ่ม/แก้ไขอัลบั้ม */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/gallery/update/' . $item['id']) : url('admin/gallery/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3">
          <label class="form-label">ชื่ออัลบั้ม *</label>
          <input type="text" name="name" class="form-control" data-slug-source
                 value="<?= e($item['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Slug (URL)</label>
          <input type="text" name="slug" class="form-control" data-slug-target
                 value="<?= e($item['slug'] ?? '') ?>" placeholder="สร้างอัตโนมัติจากชื่ออัลบั้ม">
        </div>
        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <textarea name="description" class="form-control" rows="3"><?= e($item['description'] ?? '') ?></textarea>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">วันที่จัดกิจกรรม</label>
            <input type="date" name="event_date" class="form-control" value="<?= e($item['event_date'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">รูปปกอัลบั้ม</label>
          <div><img src="<?= upload_url($item['cover_image'] ?? '') ?>" id="coverPreview" class="img-fluid rounded mb-2" style="max-height:180px;object-fit:cover"></div>
          <input type="file" name="cover_image" class="form-control" accept="image/*" data-preview="#coverPreview">
        </div>
        <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/gallery') ?>" class="btn btn-link">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
