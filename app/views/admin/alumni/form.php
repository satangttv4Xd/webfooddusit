<?php
/** ฟอร์มศิษย์เก่า */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/alumni/update/' . $item['id']) : url('admin/alumni/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อ-นามสกุล *</label>
          <input type="text" name="full_name" class="form-control" value="<?= e($item['full_name'] ?? '') ?>" required></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">อาชีพปัจจุบัน</label>
            <input type="text" name="current_job" class="form-control" placeholder="เช่น Executive Chef" value="<?= e($item['current_job'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">องค์กร / สถานที่ทำงาน</label>
            <input type="text" name="organization" class="form-control" value="<?= e($item['organization'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">หลักสูตรที่จบ</label>
            <input type="text" name="program" class="form-control" value="<?= e($item['program'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">รุ่นปี / ปีที่จบ</label>
            <input type="text" name="graduate_year" class="form-control" placeholder="เช่น 2560" value="<?= e($item['graduate_year'] ?? '') ?>"></div>
        </div>
        <div class="mb-0 mt-3"><label class="form-label">คำกล่าว / ความประทับใจ</label>
          <textarea name="quote" class="form-control" rows="3"><?= e($item['quote'] ?? '') ?></textarea></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-4">
        <label class="form-label">รูปภาพ</label>
        <img src="<?= upload_url($item['photo'] ?? '') ?>" id="imgPreview" class="rounded-circle mb-2 mx-auto d-block" width="120" height="120" style="object-fit:cover">
        <input type="file" name="photo" class="form-control" accept="image/*" data-preview="#imgPreview">
      </div>
      <div class="card p-4 mt-3">
        <div class="form-check mb-3">
          <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="feat" <?= !empty($item['is_featured']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="feat">แสดงเป็นศิษย์เก่าดีเด่น (หน้าแรก)</label>
        </div>
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
        <a href="<?= url('admin/alumni') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
