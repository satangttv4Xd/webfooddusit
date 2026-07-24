<?php
/** ฟอร์มเพิ่ม/แก้ไขหน้าเนื้อหา */
$isEdit = $item !== null;
$action = $isEdit ? url('admin/pages/update/' . $item['id']) : url('admin/pages/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3">
          <label class="form-label">ชื่อหน้า *</label>
          <input type="text" name="title" class="form-control" data-slug-source
                 value="<?= e($item['title'] ?? old('title')) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Slug (URL)</label>
          <input type="text" name="slug" class="form-control" data-slug-target
                 value="<?= e($item['slug'] ?? '') ?>" placeholder="สร้างอัตโนมัติจากชื่อหน้า">
          <?php if ($isEdit): ?><small class="text-muted">URL: <?= url('page/' . $item['slug']) ?></small><?php endif; ?>
        </div>
        <div class="mb-0">
          <label class="form-label">เนื้อหา *</label>
          <textarea name="content" class="form-control" rows="16"><?= e($item['content'] ?? '') ?></textarea>
          <small class="text-muted">รองรับ HTML พื้นฐาน</small>
        </div>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-3"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>SEO</h6>
        <div class="mb-3">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= e($item['meta_title'] ?? '') ?>">
        </div>
        <div class="mb-0">
          <label class="form-label">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="2"><?= e($item['meta_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4">
        <div class="mb-3">
          <label class="form-label">สถานะ</label>
          <select name="status" class="form-select">
            <?php foreach (['published' => 'เผยแพร่', 'draft' => 'ฉบับร่าง', 'inactive' => 'ปิดใช้งาน'] as $val => $lb): ?>
              <option value="<?= $val ?>" <?= ($item['status'] ?? 'published') === $val ? 'selected' : '' ?>><?= $lb ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-0">
          <label class="form-label">เทมเพลต</label>
          <input type="text" name="template" class="form-control" value="<?= e($item['template'] ?? 'default') ?>">
          <small class="text-muted">ค่าเริ่มต้น: default</small>
        </div>
      </div>

      <div class="card p-4 mt-3">
        <label class="form-label">รูปปก</label>
        <img src="<?= upload_url($item['cover_image'] ?? '') ?>" id="coverPreview" class="img-fluid rounded mb-2" style="max-height:180px;object-fit:cover;width:100%">
        <input type="file" name="cover_image" class="form-control" accept="image/*" data-preview="#coverPreview">
      </div>

      <div class="card p-3 mt-3">
        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/pages') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
