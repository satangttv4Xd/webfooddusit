<?php /** รายการแบนเนอร์ */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="mb-0 fw-bold">จัดการแบนเนอร์หน้าแรก</h5>
    <span class="text-muted small">แบนเนอร์แสดงในสไลด์หน้าแรก (Hero Carousel)</span>
  </div>
  <a href="<?= url('admin/banner/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มแบนเนอร์</a>
</div>
<div class="row g-3">
  <?php foreach ($banners as $b): ?>
    <?php 
      $hasText = !empty(trim($b['title'] ?? '')) || !empty(trim($b['subtitle'] ?? ''));
    ?>
    <div class="col-md-6 col-lg-4">
      <div class="card overflow-hidden h-100 shadow-sm border">
        <div style="height:170px;background:#081d33;position:relative" class="overflow-hidden">
          <img src="<?= upload_url($b['image']) ?>" style="width:100%;height:100%;object-fit:cover" alt="">
          <div style="position:absolute;top:8px;left:8px">
            <?php if ($hasText): ?>
              <span class="badge bg-primary"><i class="fa-solid fa-font me-1"></i> มีข้อความซ้อน</span>
            <?php else: ?>
              <span class="badge bg-dark"><i class="fa-solid fa-image me-1"></i> ภาพกราฟิกล้วน</span>
            <?php endif; ?>
          </div>
          <div style="position:absolute;top:8px;right:8px">
            <?= status_badge($b['status']) ?>
          </div>
        </div>
        <div class="card-body d-flex flex-column">
          <h6 class="mb-1 fw-bold"><?= e($b['title'] ?: '(ภาพกราฟิกไม่มีหัวข้อซ้อน)') ?></h6>
          <?php if (!empty($b['subtitle'])): ?>
            <p class="small text-muted mb-2"><?= e(excerpt($b['subtitle'], 60)) ?></p>
          <?php endif; ?>
          <?php if (!empty($b['link_url'])): ?>
            <div class="small text-truncate text-muted mb-2">
              <i class="fa-solid fa-link text-gold me-1"></i> <?= e($b['link_url']) ?>
            </div>
          <?php endif; ?>
          <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">ลำดับ: <strong><?= $b['sort_order'] ?></strong></small>
            <div>
              <a href="<?= url('admin/banner/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/banner/delete/' . $b['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบแบนเนอร์นี้?" title="ลบ"><i class="fa-solid fa-trash"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($banners)): ?><div class="col-12 text-center text-muted py-4">ยังไม่มีแบนเนอร์</div><?php endif; ?>
</div>
