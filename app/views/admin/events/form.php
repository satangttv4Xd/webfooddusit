<?php
/** ฟอร์มกิจกรรม */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/events/update/' . $item['id']) : url('admin/events/store');
$fmt = fn($d) => $d ? date('Y-m-d\TH:i', strtotime($d)) : '';
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อกิจกรรม *</label>
          <input type="text" name="title" class="form-control" data-slug-source value="<?= e($item['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" data-slug-target value="<?= e($item['slug'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">รายละเอียด</label>
          <textarea name="description" class="form-control" rows="8"><?= e($item['description'] ?? '') ?></textarea></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">วันเวลาเริ่ม *</label>
          <input type="datetime-local" name="start_date" class="form-control" value="<?= $fmt($item['start_date'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">วันเวลาสิ้นสุด</label>
          <input type="datetime-local" name="end_date" class="form-control" value="<?= $fmt($item['end_date'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">สถานที่</label>
          <input type="text" name="location" class="form-control" value="<?= e($item['location'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">จำนวนรับสูงสุด (0 = ไม่จำกัด)</label>
          <input type="number" name="max_attendees" class="form-control" value="<?= e($item['max_attendees'] ?? 0) ?>"></div>
        <div class="mb-3"><label class="form-label">สถานะ</label>
          <select name="status" class="form-select">
            <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
            <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
          </select></div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="allow_register" id="reg" value="1" <?= ($item['allow_register'] ?? 0) ? 'checked' : '' ?>>
          <label class="form-check-label" for="reg">เปิดให้ลงทะเบียน</label></div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_featured" id="feat" value="1" <?= ($item['is_featured'] ?? 0) ? 'checked' : '' ?>>
          <label class="form-check-label" for="feat">กิจกรรมแนะนำ</label></div>
      </div>
      <div class="card p-4 mt-3">
        <label class="form-label">รูปปก</label>
        <img src="<?= upload_url($item['cover_image'] ?? '') ?>" id="coverPreview" class="img-fluid rounded mb-2" style="max-height:150px;object-fit:cover;width:100%">
        <input type="file" name="cover_image" class="form-control" accept="image/*" data-preview="#coverPreview">
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/events') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
