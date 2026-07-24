<?php
/** ฟอร์มบุคลากร/อาจารย์ */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/staff/update/' . $item['id']) : url('admin/staff/store');
$curType = $item['type'] ?? 'teacher';
$curDept = (int)($item['department_id'] ?? 0);
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อ-นามสกุล *</label>
          <input type="text" name="full_name" class="form-control" value="<?= e($item['full_name'] ?? '') ?>" required></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">ตำแหน่ง</label>
            <input type="text" name="position" class="form-control" placeholder="เช่น อาจารย์ประจำสาขา" value="<?= e($item['position'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ประเภท *</label>
            <select name="type" class="form-select">
              <?php foreach (Staff::TYPES as $val => $label): ?>
                <option value="<?= $val ?>" <?= $curType === $val ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="col-md-6"><label class="form-label">หน่วยงาน / สาขา</label>
            <select name="department_id" class="form-select">
              <option value="">— ไม่ระบุ —</option>
              <?php foreach ($departments as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $curDept === (int)$d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="col-md-6"><label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" value="<?= e($item['email'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">โทรศัพท์</label>
            <input type="text" name="phone" class="form-control" value="<?= e($item['phone'] ?? '') ?>"></div>
        </div>
        <div class="mb-3 mt-3"><label class="form-label">ความเชี่ยวชาญ</label>
          <textarea name="expertise" class="form-control" rows="3" placeholder="เช่น การประกอบอาหารไทย ขนมไทย"><?= e($item['expertise'] ?? '') ?></textarea></div>
        <div class="mb-0"><label class="form-label">ประวัติ / คำอธิบาย</label>
          <textarea name="bio" class="form-control" rows="4"><?= e($item['bio'] ?? '') ?></textarea></div>
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
        <a href="<?= url('admin/staff') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
