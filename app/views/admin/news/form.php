<?php
/** ฟอร์มเพิ่ม/แก้ไขข่าว */
$isEdit = $news !== null;
$action = $isEdit ? url('admin/news/update/' . $news['id']) : url('admin/news/store');
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="mb-3">
          <label class="form-label">หัวข้อข่าว *</label>
          <input type="text" name="title" class="form-control" data-slug-source
                 value="<?= e($news['title'] ?? old('title')) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Slug (URL)</label>
          <input type="text" name="slug" class="form-control" data-slug-target
                 value="<?= e($news['slug'] ?? '') ?>" placeholder="สร้างอัตโนมัติจากหัวข้อ">
        </div>
        <div class="mb-3">
          <label class="form-label">สรุปย่อ</label>
          <textarea name="excerpt" class="form-control" rows="2"><?= e($news['excerpt'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">เนื้อหา *</label>
          <textarea name="content" class="form-control" rows="12"><?= e($news['content'] ?? '') ?></textarea>
          <small class="text-muted">รองรับ HTML พื้นฐาน</small>
        </div>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-3"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>SEO</h6>
        <div class="mb-3">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= e($news['meta_title'] ?? '') ?>">
        </div>
        <div class="mb-0">
          <label class="form-label">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="2"><?= e($news['meta_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4">
        <div class="mb-3">
          <label class="form-label">สถานะ</label>
          <select name="status" class="form-select">
            <?php foreach (['published' => 'เผยแพร่', 'draft' => 'ฉบับร่าง', 'pending' => 'รอตรวจ'] as $val => $lb): ?>
              <option value="<?= $val ?>" <?= ($news['status'] ?? 'draft') === $val ? 'selected' : '' ?>><?= $lb ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">หมวดหมู่ *</label>
          <select name="category_id" class="form-select" required>
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($news['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Tags (คั่นด้วย ,)</label>
          <input type="text" name="tags" class="form-control" value="<?= e($news['tags'] ?? '') ?>">
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="is_featured" id="feat" value="1" <?= ($news['is_featured'] ?? 0) ? 'checked' : '' ?>>
          <label class="form-check-label" for="feat">ข่าวแนะนำ (Featured)</label>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="allow_comment" id="cmt" value="1" <?= ($news['allow_comment'] ?? 1) ? 'checked' : '' ?>>
          <label class="form-check-label" for="cmt">อนุญาตแสดงความคิดเห็น</label>
        </div>
      </div>

      <div class="card p-4 mt-3">
        <label class="form-label">รูปปก</label>
        <img src="<?= upload_url($news['cover_image'] ?? '') ?>" id="coverPreview" class="img-fluid rounded mb-2" style="max-height:180px;object-fit:cover;width:100%">
        <input type="file" name="cover_image" class="form-control" accept="image/*" data-preview="#coverPreview">
      </div>

      <div class="card p-3 mt-3">
        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/news') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
