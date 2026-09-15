<?php
/** หน้าทุนการศึกษา (แยกหน้า) */
$sh = $scholarship;
?>
<?= page_header('ทุนการศึกษา', [
    'หลักสูตร'    => 'curriculum',
    $name        => 'curriculum/' . $slug,
    'ทุนการศึกษา' => '',
]) ?>

<section class="section">
  <div class="container">

    <div class="mb-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>

    <h4 class="mb-1"><i class="fa-solid fa-award me-2" style="color:var(--gold)"></i>ทุนการศึกษา</h4>
    <p class="text-muted mb-3"><?= e($name) ?></p>
    <hr class="divider-gold mb-4" style="max-width:120px">

    <?php if (!empty($sh['pdf'])): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <a href="<?= upload_url($sh['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">
          <i class="fa-solid fa-file-pdf me-1"></i> เปิดดูเอกสารทุนการศึกษา (PDF)
        </a>
        <a href="<?= upload_url($sh['pdf']) ?>" download class="btn btn-outline-gold">
          <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($sh['items'])): ?>
      <?php foreach ($sh['items'] as $i => $it): ?>
        <div class="card-modern p-4 mb-4" data-aos="fade-up">
          <div class="d-flex align-items-start gap-3">
            <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-award"></i></div>
            <div class="w-100">
              <h5 class="mb-2" style="color:var(--primary)"><?= e($it['name']) ?></h5>
              <?php if (!empty($it['desc'])): ?><p class="mb-3"><?= e($it['desc']) ?></p><?php endif; ?>
              <?php if (!empty($it['quals'])): ?>
                <div class="fw-semibold mb-1">คุณสมบัติผู้สมัคร</div>
                <ul class="mb-0 ps-3">
                  <?php foreach ($it['quals'] as $q): ?>
                    <li class="mb-1"><?= e($q) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($sh['note'])): ?>
      <div class="alert alert-light border" role="alert"><i class="fa-solid fa-circle-info me-1 text-muted"></i><?= e($sh['note']) ?></div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>
  </div>
</section>
