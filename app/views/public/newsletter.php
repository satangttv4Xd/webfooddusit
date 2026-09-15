<?php
/** จดหมายข่าว — ดึงจากตาราง documents (จัดการผ่าน /admin/documents) */
?>
<?= page_header('จดหมายข่าว', ['รอบรั้วโรงเรียนการเรือน' => '', 'จดหมายข่าว' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Newsletter</span>
      <h2>จดหมายข่าว</h2>
      <div class="title-underline"></div>
      <p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php if (!empty($items)): ?>
      <div class="row g-4">
        <?php foreach ($items as $d): ?>
          <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
            <article class="card-modern h-100">
              <?php if (!empty($d['cover_image'])): ?>
                <div class="card-img-wrap" style="aspect-ratio:3/4">
                  <img src="<?= upload_url($d['cover_image']) ?>" alt="<?= e($d['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                </div>
              <?php else: ?>
                <div class="card-img-wrap d-flex align-items-center justify-content-center bg-light" style="aspect-ratio:3/4">
                  <i class="fa-solid fa-file-pdf fa-3x text-muted"></i>
                </div>
              <?php endif; ?>
              <div class="card-body text-center">
                <h6 class="mb-1"><?= e($d['title']) ?></h6>
                <?php if (!empty($d['doc_date'])): ?><div class="card-meta small mb-2"><?= thai_date($d['doc_date']) ?></div><?php endif; ?>
                <?php if (!empty($d['file_path'])): ?>
                  <a href="<?= upload_url($d['file_path']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-primary-custom">View</a>
                <?php endif; ?>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-5 text-muted" data-aos="fade-up">
        <i class="fa-solid fa-file-circle-exclamation fa-3x mb-3"></i>
        <p>ยังไม่มีจดหมายข่าวเผยแพร่ในขณะนี้</p>
      </div>
    <?php endif; ?>
  </div>
</section>
