<?php /** รายการแบนเนอร์ */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">แบนเนอร์แสดงบนหน้าแรก (Hero Slider)</span>
  <a href="<?= url('admin/banner/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มแบนเนอร์</a>
</div>
<div class="row g-3">
  <?php foreach ($banners as $b): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card overflow-hidden h-100">
        <img src="<?= upload_url($b['image']) ?>" style="height:160px;object-fit:cover" alt="">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h6 class="mb-1"><?= e($b['title'] ?: '(ไม่มีหัวข้อ)') ?></h6>
            <?= status_badge($b['status']) ?>
          </div>
          <p class="small text-muted mb-2"><?= e(excerpt($b['subtitle'] ?? '', 60)) ?></p>
          <small class="text-muted">ลำดับ: <?= $b['sort_order'] ?></small>
          <div class="mt-2 text-end">
            <a href="<?= url('admin/banner/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
            <a href="<?= url('admin/banner/delete/' . $b['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบแบนเนอร์นี้?"><i class="fa-solid fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($banners)): ?><div class="col-12 text-center text-muted py-4">ยังไม่มีแบนเนอร์</div><?php endif; ?>
</div>
