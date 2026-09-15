<?php
/** ฟอร์มเอกสาร/รายงาน */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/documents/update/' . $item['id']) : url('admin/documents/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">ชื่อเอกสาร *</label>
          <input type="text" name="title" class="form-control" placeholder="เช่น รายงานผลการดำเนินงาน ครั้งที่ 5/2569" value="<?= e($item['title'] ?? '') ?>" required></div>
        <div class="row g-3 mb-3">
          <div class="col-md-4"><label class="form-label">เลขที่เอกสาร</label>
            <input type="text" name="doc_number" class="form-control" placeholder="เช่น 5/2569" value="<?= e($item['doc_number'] ?? '') ?>"></div>
          <div class="col-md-4"><label class="form-label">หมวดหมู่ *</label>
            <select name="category" class="form-select" required>
              <option value="">-- เลือกหมวดหมู่ --</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>" <?= ($item['category'] ?? '') === $c ? 'selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
            <small class="d-block text-muted">หมวดหมู่กำหนดว่าเอกสารจะไปแสดงในหน้าใด เช่น "รายงานผลการดำเนินงาน" หรือ "จดหมายข่าว"</small></div>
          <div class="col-md-4"><label class="form-label">วันที่เอกสาร</label>
            <input type="date" name="doc_date" class="form-control" value="<?= e($item['doc_date'] ?? '') ?>"></div>
        </div>
        <div class="mb-0"><label class="form-label">ไฟล์ PDF <?= $isEdit ? '(เว้นว่างหากไม่เปลี่ยน)' : '*' ?></label>
          <input type="file" name="file" id="fileInput" class="form-control" accept="application/pdf" <?= $isEdit ? '' : 'required' ?>>
          <?php if ($isEdit && $item['file_path']): ?><small class="text-muted">ไฟล์ปัจจุบัน: <?= e(basename($item['file_path'])) ?></small><?php endif; ?>
          <small class="d-block text-muted">รองรับ: <?= ALLOWED_DOC_TYPES ?> (สูงสุด <?= MAX_UPLOAD_SIZE/1024/1024 ?> MB)</small></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-4">
        <label class="form-label">รูปหน้าปก</label>
        <div class="form-text mb-2">เลือกไฟล์ PDF แล้วระบบจะดึงหน้าแรกมาทำเป็นรูปหน้าปกให้อัตโนมัติ หรือจะอัปโหลดรูปเองแทนก็ได้</div>
        <img src="<?= upload_url($item['cover_image'] ?? '') ?>" id="imgPreview" class="rounded mb-2 mx-auto d-block w-100" style="aspect-ratio:3/4;object-fit:cover">
        <div id="coverStatus" class="small text-muted mb-2"></div>
        <input type="file" name="cover_image" id="coverInput" class="form-control" accept="image/*" data-preview="#imgPreview">
      </div>
      <div class="card p-4 mt-3">
        <label class="form-label">สถานะ</label>
        <select name="status" class="form-select">
          <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
          <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
        </select>
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/documents') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
(function () {
  var fileInput   = document.getElementById('fileInput');
  var coverInput  = document.getElementById('coverInput');
  var statusEl    = document.getElementById('coverStatus');
  if (!fileInput || !coverInput || typeof pdfjsLib === 'undefined') return;

  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

  fileInput.addEventListener('change', function () {
    var file = this.files[0];
    if (!file || file.type !== 'application/pdf') return;

    statusEl.textContent = 'กำลังสร้างรูปหน้าปกจากหน้าแรกของ PDF...';

    file.arrayBuffer()
      .then(function (buf) { return pdfjsLib.getDocument({ data: buf }).promise; })
      .then(function (pdf) { return pdf.getPage(1); })
      .then(function (page) {
        var viewport = page.getViewport({ scale: 2 });
        var canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        return page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise
          .then(function () { return canvas; });
      })
      .then(function (canvas) {
        return new Promise(function (resolve) { canvas.toBlob(resolve, 'image/png'); });
      })
      .then(function (blob) {
        var coverName = file.name.replace(/\.pdf$/i, '') + '-cover.png';
        var coverFile = new File([blob], coverName, { type: 'image/png' });
        var dt = new DataTransfer();
        dt.items.add(coverFile);
        coverInput.files = dt.files;
        coverInput.dispatchEvent(new Event('change')); // ให้ตัว preview เดิมของระบบอัปเดตรูปให้
        statusEl.textContent = 'สร้างรูปหน้าปกจากหน้าแรกของ PDF เรียบร้อยแล้ว (เปลี่ยนเป็นรูปอื่นได้ถ้าต้องการ)';
      })
      .catch(function (err) {
        console.error(err);
        statusEl.textContent = 'ไม่สามารถสร้างรูปหน้าปกอัตโนมัติได้ กรุณาอัปโหลดรูปหน้าปกเอง';
      });
  });
})();
</script>
