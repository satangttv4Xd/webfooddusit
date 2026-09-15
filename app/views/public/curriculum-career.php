<?php
/** หน้าโอกาสในการประกอบอาชีพ (แยกหน้า) */
$cr = $career;
?>
<?= page_header('โอกาสในการประกอบอาชีพ', [
    'หลักสูตร'            => 'curriculum',
    $name                => 'curriculum/' . $slug,
    'โอกาสในการประกอบอาชีพ' => '',
]) ?>

<section class="section">
  <div class="container">

    <div class="mb-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>

    <h4 class="mb-1"><i class="fa-solid fa-briefcase me-2" style="color:var(--gold)"></i>โอกาสในการประกอบอาชีพ</h4>
    <p class="text-muted mb-3"><?= e($name) ?></p>
    <hr class="divider-gold mb-4" style="max-width:120px">

    <?php if (!empty($cr['pdf'])): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <a href="<?= upload_url($cr['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">
          <i class="fa-solid fa-file-pdf me-1"></i> เปิดดูเอกสารอาชีพ (PDF)
        </a>
        <a href="<?= upload_url($cr['pdf']) ?>" download class="btn btn-outline-gold">
          <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($cr['items'])): ?>
      <div class="row g-3">
        <?php foreach ($cr['items'] as $i => $item): ?>
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 80 ?>">
            <div class="card-modern p-3 h-100">
              <div class="d-flex align-items-start gap-3">
                <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-briefcase"></i></div>
                <div class="align-self-center"><?= e($item) ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($cr['notes'])): ?>
      <div class="mt-3 text-muted small">
        <?php foreach ($cr['notes'] as $n): ?>
          <div class="mb-1"><?= e($n) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>
  </div>
</section>
