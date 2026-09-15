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
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-pen-nib text-gold me-2"></i>ข้อมูลข้อความ (ไม่บังคับ)</h6>
        <div class="alert alert-light border py-2 px-3 small mb-3">
          <i class="fa-solid fa-lightbulb text-warning me-1"></i> <strong>คำแนะนำ:</strong> หากรูปแบนเนอร์เป็น <strong>ภาพกราฟิกที่มีข้อความและโลโก้อยู่ในรูปแล้ว</strong> ให้เว้นว่าง "หัวข้อ" และ "คำบรรยาย" ไว้ ระบบจะแสดงรูปภาพเต็มชัดเจนโดยไม่ใส่เงาดำทับ
        </div>
        <div class="mb-3">
          <label class="form-label">หัวข้อ (Title)</label>
          <input type="text" name="title" class="form-control" placeholder="เว้นว่างได้ (หากมีข้อความในภาพแล้ว)" value="<?= e($banner['title'] ?? '') ?>">
          <small class="text-muted">หากระบุหัวข้อ ระบบจะนำข้อความนี้ไปซ้อนทับบนภาพแบนเนอร์</small>
        </div>
        <div class="mb-3">
          <label class="form-label">คำบรรยาย (Subtitle)</label>
          <textarea name="subtitle" class="form-control" rows="2" placeholder="เว้นว่างได้"><?= e($banner['subtitle'] ?? '') ?></textarea>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">ลิงก์ปลายทาง (Link URL)</label>
            <input type="text" name="link_url" class="form-control" placeholder="เช่น programs หรือ https://..." value="<?= e($banner['link_url'] ?? '') ?>">
            <small class="text-muted">เมื่อผู้ใช้คลิกที่แบนเนอร์จะเปิดไปยังลิงก์นี้</small>
          </div>
          <div class="col-md-6">
            <label class="form-label">ข้อความปุ่ม</label>
            <input type="text" name="button_text" class="form-control" placeholder="เช่น อ่านเพิ่มเติม (กรณีมีข้อความซ้อน)" value="<?= e($banner['button_text'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">ลำดับการแสดง</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($banner['sort_order'] ?? 0) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($banner['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
              <option value="inactive" <?= ($banner['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card p-4">
        <label class="form-label fw-bold">รูปแบนเนอร์ <?= $isEdit ? '' : '*' ?></label>
        <div class="small text-muted mb-2">
          • สัดส่วนที่แนะนำ: <strong>2.4 : 1</strong> หรือ <strong>16 : 7</strong><br>
          • ขนาดที่เหมาะสม: <strong>1920×800</strong> หรือ <strong>1200×500</strong> พิกเซล
        </div>
        <div class="p-2 border rounded bg-light mb-3 text-center">
          <img src="<?= upload_url($banner['image'] ?? '') ?>" id="imgPreview" class="img-fluid rounded" style="max-height:220px;object-fit:contain;width:100%">
        </div>
        <input type="file" name="image" class="form-control" accept="image/*" data-preview="#imgPreview" <?= $isEdit ? '' : 'required' ?>>
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/banner') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
