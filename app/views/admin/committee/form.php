<?php
/** ฟอร์มคณะกรรมการประจำโรงเรียนการเรือน */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/committee/update/' . $item['id']) : url('admin/committee/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อ-นามสกุล *</label>
          <input type="text" name="full_name" class="form-control" placeholder="เช่น ผศ.ดร.กนกกานต์ วีระกุล" value="<?= e($item['full_name'] ?? '') ?>" required></div>
        <div class="row g-3">
          <div class="col-md-7"><label class="form-label">ตำแหน่ง</label>
            <input type="text" name="position" class="form-control" placeholder="เช่น ประธานหลักสูตร..." value="<?= e($item['position'] ?? '') ?>"></div>
          <div class="col-md-5"><label class="form-label">กลุ่มบทบาท *</label>
            <select name="group_name" class="form-select" required>
              <?php foreach ($groups as $g): ?>
                <option value="<?= e($g) ?>" <?= ($item['group_name'] ?? $groups[0]) === $g ? 'selected' : '' ?>><?= e($g) ?></option>
              <?php endforeach; ?>
            </select></div>
        </div>
        <div class="mb-0 mt-3"><label class="form-label">ประวัติ / ผลงาน <small class="text-muted">(แสดงเมื่อกดดูรายละเอียดที่หน้าเว็บ)</small></label>
          <textarea name="bio" class="form-control" rows="6"><?= e($item['bio'] ?? '') ?></textarea></div>
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
        <a href="<?= url('admin/committee') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
