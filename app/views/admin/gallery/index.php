<?php /** รายการอัลบั้มแกลเลอรี (admin) */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">อัลบั้มภาพทั้งหมด</span>
  <a href="<?= url('admin/gallery/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มอัลบั้ม</a>
</div>

<div class="row g-3">
  <?php foreach ($pager['data'] as $a): ?>
    <div class="col-sm-6 col-lg-4 col-xxl-3">
      <div class="card h-100">
        <div class="ratio ratio-4x3">
          <img src="<?= upload_url($a['cover_image']) ?>" class="card-img-top" style="object-fit:cover" alt="">
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <h6 class="mb-1"><?= e(excerpt($a['name'], 40)) ?></h6>
            <?= status_badge($a['status']) ?>
          </div>
          <div class="small text-muted">
            <i class="fa-solid fa-images me-1"></i><?= (int)($counts[$a['id']] ?? 0) ?> รายการ
            <?php if ($a['event_date']): ?> &middot; <?= thai_date($a['event_date']) ?><?php endif; ?>
          </div>
        </div>
        <div class="card-footer bg-transparent d-flex gap-1">
          <a href="<?= url('admin/gallery/items/' . $a['id']) ?>" class="btn btn-sm btn-primary flex-fill"><i class="fa-solid fa-images me-1"></i> รูปภาพ</a>
          <a href="<?= url('admin/gallery/edit/' . $a['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <a href="<?= url('admin/gallery/delete/' . $a['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ต้องการลบอัลบั้มนี้?"><i class="fa-solid fa-trash"></i></a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($pager['data'])): ?>
    <div class="col-12"><div class="table-card text-center text-muted py-5">ยังไม่มีอัลบั้ม</div></div>
  <?php endif; ?>
</div>

<div class="mt-3"><?= pagination($pager, 'admin/gallery') ?></div>
