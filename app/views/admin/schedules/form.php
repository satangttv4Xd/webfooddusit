<?php
/** ฟอร์มลงงาน / แก้ไขงานในปฏิทิน */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/schedules/update/' . $item['id']) : url('admin/schedules/store');
$fmt = fn($d) => $d ? date('Y-m-d\TH:i', strtotime($d)) : '';

$startVal = '';
if ($isEdit) {
    $startVal = $fmt($item['start_date'] ?? '');
} elseif (!empty($defaultDate)) {
    $startVal = date('Y-m-d\T09:00', strtotime($defaultDate));
} else {
    $startVal = date('Y-m-d\T09:00');
}
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-plus text-gold me-2"></i><?= $isEdit ? 'แก้ไขงานในปฏิทิน' : 'ลงงานใหม่ในปฏิทิน' ?></h5>
    <span class="text-muted small">กำหนดวันเวลา สถานที่ และรายละเอียดภารกิจ/การปฏิบัติงาน</span>
  </div>
  <a href="<?= url('admin/schedules') ?>" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> กลับไปปฏิทิน
  </a>
</div>

<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-3 p-4">
        <div class="mb-3">
          <label class="form-label fw-bold">ชื่องาน / ภารกิจปฏิบัติงาน <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control form-control-lg" placeholder="เช่น การประชุมกรรมการประจำโรงเรียน, ตรวจประเมินคุณภาพฯ" value="<?= e($item['title'] ?? '') ?>" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">รายละเอียดงาน / กำหนดการย่อ</label>
          <textarea name="description" class="form-control" rows="6" placeholder="ระบุวาระการประชุม หรือรายละเอียดภารกิจที่ต้องปฏิบัติ..."><?= e($item['description'] ?? '') ?></textarea>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">สถานที่ / ห้องประชุม / ลิงก์ออนไลน์</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-location-dot text-danger"></i></span>
              <input type="text" name="location" class="form-control" placeholder="เช่น ห้องประชุม 1119 หรือ MS Teams" value="<?= e($item['location'] ?? '') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">ผู้รับผิดชอบ / หน่วยงาน / ผู้เข้าร่วม</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-user-tag text-primary"></i></span>
              <input type="text" name="assigned_to" class="form-control" placeholder="เช่น ฝ่ายวิชาการ, คณาจารย์สาขาอาหาร" value="<?= e($item['assigned_to'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-3 p-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-regular fa-clock me-1 text-gold"></i>วันและเวลากำหนดการ</h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">วัน-เวลาเริ่มต้น <span class="text-danger">*</span></label>
          <input type="datetime-local" name="start_date" class="form-control" value="<?= $startVal ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">วัน-เวลาสิ้นสุด (ถ้ามี)</label>
          <input type="datetime-local" name="end_date" class="form-control" value="<?= $fmt($item['end_date'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">แถบสีงานบนปฏิทิน</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" name="color" id="scheduleColor" class="form-control form-control-color" value="<?= e($item['color'] ?? '#0d6efd') ?>" title="เลือกสีของงาน">
            <span class="small text-muted">เลือกสีเพื่อให้สังเกตได้ง่ายบนปฏิทิน</span>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">รูปภาพประกอบงาน (ถ้ามี)</label>
          <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
          
          <div id="imagePreviewContainer" class="mb-2 position-relative" style="<?= empty($item['image']) ? 'display: none;' : '' ?>">
            <img src="<?= !empty($item['image']) ? upload_url($item['image']) : '' ?>" id="scheduleImgPreview" class="img-fluid rounded border shadow-sm" style="max-height: 160px; width: 100%; object-fit: cover;">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-pill px-2 py-1 shadow" id="btnRemoveImage" onclick="removeSelectedImage()" title="เอารูปภาพนี้ออก">
              <i class="fa-solid fa-trash-can me-1"></i> เอารูปออก
            </button>
          </div>

          <input type="file" name="image" id="scheduleFileInput" class="form-control form-control-sm" accept="image/*" onchange="previewScheduleImage(this)">
          <div class="form-text small text-muted">รองรับ JPG, PNG, WebP (สูงสุด 20 MB)</div>
        </div>

        <script>
        function previewScheduleImage(input) {
          const preview = document.getElementById('scheduleImgPreview');
          const container = document.getElementById('imagePreviewContainer');
          const removeFlag = document.getElementById('removeImageFlag');
          
          if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
              preview.src = e.target.result;
              container.style.display = 'block';
              if (removeFlag) removeFlag.value = '0';
            }
            reader.readAsDataURL(input.files[0]);
          }
        }

        function removeSelectedImage() {
          const fileInput = document.getElementById('scheduleFileInput');
          const preview = document.getElementById('scheduleImgPreview');
          const container = document.getElementById('imagePreviewContainer');
          const removeFlag = document.getElementById('removeImageFlag');

          if (fileInput) fileInput.value = '';
          if (preview) preview.src = '';
          if (container) container.style.display = 'none';
          if (removeFlag) removeFlag.value = '1';
        }
        </script>

        <div class="pt-3 border-top mt-3">
          <button type="submit" class="btn btn-primary w-100 mb-2">
            <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกลงปฏิทิน
          </button>
          <a href="<?= url('admin/schedules') ?>" class="btn btn-light w-100 text-muted">ยกเลิก</a>
        </div>
      </div>
    </div>
  </div>
</form>
