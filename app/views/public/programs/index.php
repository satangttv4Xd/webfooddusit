<?php /** รายการหลักสูตร */ ?>
<?= page_header('หลักสูตร', ['หลักสูตร' => '']) ?>

<section class="section">
  <div class="container">
    <?php
    $groups = [
      'bachelor' => ['ปริญญาตรี', $bachelor],
      'master'   => ['ปริญญาโท', $master],
      'doctoral' => ['ปริญญาเอก', $doctoral],
      'training' => ['หลักสูตรอบรม', $training],
    ];
    foreach ($groups as $key => [$label, $items]):
      if (empty($items)) continue; ?>
      <div class="mb-5">
        <div class="d-flex align-items-center mb-4" data-aos="fade-up">
          <span class="feature-icon me-3 mb-0"><i class="fa-solid fa-graduation-cap"></i></span>
          <div>
            <h3 class="mb-0" style="color:var(--primary)"><?= $label ?></h3>
            <small class="text-muted"><?= count($items) ?> หลักสูตร</small>
          </div>
        </div>
        <div class="row g-4">
          <?php foreach ($items as $p): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
              <div class="card-modern program-card h-100">
                <span class="level-tag"><?= $label ?></span>
                <div class="card-img-wrap"><img src="<?= upload_url($p['cover_image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"></div>
                <div class="card-body d-flex flex-column">
                  <h5><?= e($p['name']) ?></h5>
                  <p class="card-meta flex-grow-1"><?= excerpt($p['overview'] ?? '', 90) ?></p>
                  <div class="d-flex flex-wrap gap-2 mb-3 small text-muted">
                    <?php if ($p['duration']): ?><span><i class="fa-regular fa-clock me-1"></i><?= e($p['duration']) ?></span><?php endif; ?>
                    <?php if ($p['credits']): ?><span><i class="fa-solid fa-book me-1"></i><?= e($p['credits']) ?></span><?php endif; ?>
                  </div>
                  <a href="<?= url('programs/detail/' . $p['slug']) ?>" class="btn btn-primary-custom btn-sm">ดูรายละเอียด</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($bachelor) && empty($master) && empty($doctoral) && empty($training)): ?>
      <p class="text-center text-muted py-5">ยังไม่มีข้อมูลหลักสูตร</p>
    <?php endif; ?>
  </div>
</section>
