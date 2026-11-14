<?php
/** ฟอร์มแบนเนอร์ พร้อมระบบ Crop ภาพตามสัดส่วนที่เป๊ะ */
$isEdit = $banner !== null;
$action = $isEdit ? url('admin/banner/update/' . $banner['id']) : url('admin/banner/store');
?>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
  .crop-preview-box {
    width: 100%;
    aspect-ratio: 2.4 / 1;
    background: #0f172a;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 2px dashed #cbd5e1;
  }
  .crop-preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .crop-modal-img-container {
    max-height: 60vh;
    background: #0b1120;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  .crop-modal-img-container img {
    max-width: 100%;
    max-height: 60vh;
    display: block;
  }
  .ratio-badge {
    display: inline-block;
    background: #e0e7ff;
    color: #3730a3;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
  }
</style>

<form id="bannerForm" method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <!-- ซ่อนข้อมูล Base64 ของรูปภาพหลัง Crop -->
  <input type="hidden" name="cropped_image" id="croppedImageData" value="">

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
        <label class="form-label fw-bold mb-1">รูปแบนเนอร์ <?= $isEdit ? '' : '*' ?></label>
        <div class="small text-danger fw-semibold mb-2">
          * ขนาด 1920 × 800 px (สัดส่วน 2.4 : 1)
        </div>

        <!-- กล่องตัวอย่างภาพก่อนบันทึก -->
        <div class="crop-preview-box mb-3" id="previewContainer">
          <?php if (!empty($banner['image'])): ?>
            <img src="<?= upload_url($banner['image']) ?>" id="imgPreview" alt="ตัวอย่างภาพแบนเนอร์">
          <?php else: ?>
            <div id="emptyPreviewPlaceholder" class="text-center text-muted p-4">
              <i class="fa-regular fa-image fa-3x mb-2 d-block opacity-50"></i>
              <span>ยังไม่มีรูปภาพตัวอย่าง</span>
            </div>
            <img src="" id="imgPreview" alt="ตัวอย่างภาพแบนเนอร์" style="display:none;">
          <?php endif; ?>
        </div>

        <div class="d-flex gap-2">
          <label class="btn btn-outline-primary flex-grow-1 mb-0">
            <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> <?= $isEdit ? 'เปลี่ยนรูปภาพ...' : 'เลือกรูปภาพ...' ?>
            <input type="file" id="bannerFileInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
          </label>
          <button type="button" class="btn btn-outline-secondary" id="reCropBtn" style="display:none;" title="ตัดรูปภาพใหม่">
            <i class="fa-solid fa-crop"></i> ตัดใหม่
          </button>
        </div>
        <div id="cropStatusAlert" class="form-text text-success mt-2" style="display:none;">
          <i class="fa-solid fa-circle-check me-1"></i> ตัดสัดส่วนภาพขนาด 1920×800 เรียบร้อยแล้ว พร้อมบันทึก
        </div>
      </div>

      <div class="card p-3 mt-3">
        <button type="submit" id="saveBannerBtn" class="btn btn-primary w-100">
          <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกข้อมูล
        </button>
        <a href="<?= url('admin/banner') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>

<!-- Modal สำหรับ Crop รูปภาพ -->
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="cropModalLabel">
          <i class="fa-solid fa-crop me-2 text-warning"></i>เลือกตำแหน่งรูปภาพแบนเนอร์ (ขนาด 1920 × 800 px)
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="crop-modal-img-container">
          <img id="imageToCrop" src="" alt="ภาพสำหรับตัด">
        </div>
        <div class="p-2 px-3 bg-light border-top d-flex flex-wrap align-items-center justify-content-end gap-2">
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary" id="btnZoomIn" title="ซูมเข้า"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
            <button type="button" class="btn btn-outline-secondary" id="btnZoomOut" title="ซูมออก"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
            <button type="button" class="btn btn-outline-secondary" id="btnRotateLeft" title="หมุนซ้าย"><i class="fa-solid fa-rotate-left"></i></button>
            <button type="button" class="btn btn-outline-secondary" id="btnRotateRight" title="หมุนขวา"><i class="fa-solid fa-rotate-right"></i></button>
            <button type="button" class="btn btn-outline-secondary" id="btnResetCrop" title="รีเซ็ต"><i class="fa-solid fa-arrow-rotate-left"></i> รีเซ็ต</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-success px-4" id="btnApplyCrop">
          <i class="fa-solid fa-check me-1"></i> ยืนยันใช้รูปนี้ (1920×800)
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Cropper.js Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fileInput = document.getElementById('bannerFileInput');
  const cropModalEl = document.getElementById('cropModal');
  const cropModal = new bootstrap.Modal(cropModalEl);
  const imageToCrop = document.getElementById('imageToCrop');
  const btnApplyCrop = document.getElementById('btnApplyCrop');
  const croppedImageData = document.getElementById('croppedImageData');
  const imgPreview = document.getElementById('imgPreview');
  const emptyPlaceholder = document.getElementById('emptyPreviewPlaceholder');
  const cropStatusAlert = document.getElementById('cropStatusAlert');
  const reCropBtn = document.getElementById('reCropBtn');
  const bannerForm = document.getElementById('bannerForm');

  let cropper = null;
  let rawImageSourceUrl = null;
  const TARGET_WIDTH = 1920;
  const TARGET_HEIGHT = 800;
  const ASPECT_RATIO = TARGET_WIDTH / TARGET_HEIGHT; // 2.4

  // เมื่อผู้ใช้เลือกไฟล์ภาพ
  fileInput.addEventListener('change', function (e) {
    const files = e.target.files;
    if (!files || files.length === 0) return;

    const file = files[0];
    if (!file.type.match(/^image\/(jpeg|png|webp)$/i)) {
      alert('กรุณาเลือกไฟล์รูปภาพที่เป็น JPG, PNG หรือ WebP เท่านั้น');
      fileInput.value = '';
      return;
    }

    if (rawImageSourceUrl) {
      URL.revokeObjectURL(rawImageSourceUrl);
    }
    rawImageSourceUrl = URL.createObjectURL(file);
    imageToCrop.src = rawImageSourceUrl;
    cropModal.show();
  });

  // เมื่อเปิด Modal ให้สร้าง instance ของ Cropper
  cropModalEl.addEventListener('shown.bs.modal', function () {
    if (cropper) {
      cropper.destroy();
    }
    cropper = new Cropper(imageToCrop, {
      aspectRatio: ASPECT_RATIO,
      viewMode: 1, // บังคับให้อยู่ในขอบเขตภาพ
      dragMode: 'move',
      autoCropArea: 1, // ครอบพื้นที่ให้ใหญ่สุดเท่าที่เป็นไปได้ในภาพ
      responsive: true,
      restore: false,
      guides: true,
      center: true,
      highlight: false,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false,
    });
  });

  // เมื่อปิด Modal ทำลาย cropper เพื่อเคลียร์หน่วยความจำ
  cropModalEl.addEventListener('hidden.bs.modal', function () {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    fileInput.value = '';
  });

  // ปุ่มคอนโทรลต่างๆ
  document.getElementById('btnZoomIn').addEventListener('click', function () {
    if (cropper) cropper.zoom(0.1);
  });
  document.getElementById('btnZoomOut').addEventListener('click', function () {
    if (cropper) cropper.zoom(-0.1);
  });
  document.getElementById('btnRotateLeft').addEventListener('click', function () {
    if (cropper) cropper.rotate(-90);
  });
  document.getElementById('btnRotateRight').addEventListener('click', function () {
    if (cropper) cropper.rotate(90);
  });
  document.getElementById('btnResetCrop').addEventListener('click', function () {
    if (cropper) cropper.reset();
  });

  // ปุ่มกดตัดใหม่ (เปิด Modal จากรูปเดิมที่เคยเลือกไว้)
  reCropBtn.addEventListener('click', function () {
    if (rawImageSourceUrl) {
      imageToCrop.src = rawImageSourceUrl;
      cropModal.show();
    } else {
      fileInput.click();
    }
  });

  // เมื่อกดยืนยันตัดรูป
  btnApplyCrop.addEventListener('click', function () {
    if (!cropper) return;

    // ทำการ render ออกมาเป็น canvas ที่ขนาดเป๊ะๆ 1920x800
    const canvas = cropper.getCroppedCanvas({
      width: TARGET_WIDTH,
      height: TARGET_HEIGHT,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    if (!canvas) {
      alert('ไม่สามารถตัดรูปภาพได้ กรุณาลองใหม่อีกครั้ง');
      return;
    }

    // แปลงเป็น base64 (JPEG คุณภาพสูง 0.92)
    const base64 = canvas.toDataURL('image/jpeg', 0.92);
    croppedImageData.value = base64;

    // อัปเดต preview
    imgPreview.src = base64;
    imgPreview.style.display = 'block';
    if (emptyPlaceholder) {
      emptyPlaceholder.style.display = 'none';
    }

    // แสดงปุ่มและข้อความสถานะ
    reCropBtn.style.display = 'inline-block';
    cropStatusAlert.style.display = 'block';

    // ปิด Modal
    cropModal.hide();
  });

  // ตรวจสอบก่อนส่งฟอร์ม (กรณีเพิ่มใหม่ ต้องมีรูปภาพหรือ cropped_image)
  bannerForm.addEventListener('submit', function (e) {
    const isEdit = <?= $isEdit ? 'true' : 'false' ?>;
    const hasCroppedData = croppedImageData.value.trim() !== '';

    if (!isEdit && !hasCroppedData) {
      e.preventDefault();
      alert('กรุณาเลือกรูปภาพและกดตัดรูปภาพให้เรียบร้อยก่อนบันทึก');
      fileInput.click();
      return false;
    }
  });
});
</script>
