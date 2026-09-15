<?php /** จัดการรูป/วิดีโอในอัลบั้ม (admin) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <div>
    <a href="<?= url('admin/gallery') ?>" class="btn btn-sm btn-link px-0"><i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าอัลบั้ม</a>
    <div class="small text-muted">อัลบั้ม: <strong><?= e($album['name']) ?></strong> &middot; <?= count($items) ?> รายการ</div>
  </div>
  <a href="<?= url('admin/gallery/edit/' . $album['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen me-1"></i> แก้ไขอัลบั้ม</a>
</div>

<div class="card p-4 mb-4">
  <h6 class="mb-3"><i class="fa-solid fa-cloud-arrow-up me-2"></i>เพิ่มรูปภาพ / วิดีโอ</h6>
  <form method="post" action="<?= url('admin/gallery/upload/' . $album['id']) ?>" enctype="multipart/form-data" id="galleryUploadForm">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label">อัปโหลดรูปภาพ (เลือกได้หลายไฟล์)</label>
      <input type="file" name="images[]" id="galleryImagesInput" class="form-control" accept="image/*" multiple>
      <small class="text-muted">รองรับ: <?= ALLOWED_IMAGE_TYPES ?> (สูงสุด <?= MAX_UPLOAD_SIZE/1024/1024 ?> MB ต่อไฟล์)</small>
    </div>
    <div id="galleryCaptionList" class="mb-3"></div>
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label">หรือลิงก์วิดีโอ (YouTube)</label>
        <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
      </div>
      <div class="col-md-4">
        <label class="form-label">ชื่อวิดีโอ</label>
        <input type="text" name="video_title" class="form-control" placeholder="ชื่อวิดีโอ">
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-plus me-1"></i> เพิ่ม</button>
      </div>
    </div>
  </form>
</div>

<div class="row g-3">
  <?php foreach ($items as $it): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <div class="ratio ratio-4x3">
          <?php if ($it['type'] === 'video'): ?>
            <div class="d-flex align-items-center justify-content-center bg-dark text-white">
              <i class="fa-brands fa-youtube fa-2x"></i>
            </div>
          <?php else: ?>
            <img src="<?= upload_url($it['file_path']) ?>" style="object-fit:cover" alt="">
          <?php endif; ?>
        </div>
        <div class="card-body p-2">
          <div class="small text-truncate"><?= e($it['title'] ?: '-') ?></div>
          <?php if ($it['type'] === 'video'): ?>
            <a href="<?= e($it['video_url']) ?>" target="_blank" class="small text-decoration-none"><i class="fa-solid fa-link"></i> ลิงก์</a>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-transparent p-2 text-end">
          <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#itemEditModal"
                  data-id="<?= $it['id'] ?>" data-title="<?= e($it['title'] ?? '') ?>"><i class="fa-solid fa-pen"></i></button>
          <a href="<?= url('admin/gallery/deleteItem/' . $it['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ต้องการลบรายการนี้?"><i class="fa-solid fa-trash"></i></a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($items)): ?>
    <div class="col-12"><div class="table-card text-center text-muted py-5">ยังไม่มีรูปภาพในอัลบั้มนี้</div></div>
  <?php endif; ?>
</div>

<!-- Modal แก้ไขคำอธิบายรูป/วิดีโอ -->
<div class="modal fade" id="itemEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" id="itemEditForm">
        <?= Security::csrfField() ?>
        <div class="modal-header">
          <h5 class="modal-title">แก้ไขคำอธิบาย</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">คำอธิบายภาพ/วิดีโอ</label>
          <input type="text" name="title" id="itemEditTitle" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">ยกเลิก</button>
          <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ---------- แสดงช่องกรอกคำอธิบายให้ตรงกับไฟล์ที่เลือกอัปโหลด ----------
  var imagesInput = document.getElementById('galleryImagesInput');
  var captionList = document.getElementById('galleryCaptionList');
  if (imagesInput && captionList) {
    imagesInput.addEventListener('change', function () {
      captionList.innerHTML = '';
      Array.from(this.files).forEach(function (file, i) {
        var wrap = document.createElement('div');
        wrap.className = 'input-group input-group-sm mb-1';
        var label = document.createElement('span');
        label.className = 'input-group-text text-truncate';
        label.style.maxWidth = '160px';
        label.textContent = file.name;
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'captions[]';
        input.className = 'form-control';
        input.placeholder = 'คำอธิบายภาพ (ไม่ใส่ก็ได้)';
        wrap.appendChild(label);
        wrap.appendChild(input);
        captionList.appendChild(wrap);
      });
    });
  }

  // ---------- แก้ไขคำอธิบายรูป/วิดีโอที่มีอยู่แล้ว ----------
  var editModal = document.getElementById('itemEditModal');
  var editForm  = document.getElementById('itemEditForm');
  var editTitle = document.getElementById('itemEditTitle');
  if (editModal) {
    editModal.addEventListener('show.bs.modal', function (ev) {
      var btn = ev.relatedTarget;
      editForm.action = '<?= url('admin/gallery/updateItem/') ?>' + btn.dataset.id;
      editTitle.value = btn.dataset.title || '';
    });
  }
});
</script>
