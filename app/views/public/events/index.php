<?php /** รายการกิจกรรม */ ?>
<?= page_header('ปฏิทินกิจกรรม', ['กิจกรรม' => '']) ?>

<section class="section">
  <div class="container">
    <?php if (empty($events)): ?>
      <p class="text-center text-muted py-5">ยังไม่มีกิจกรรม</p>
    <?php endif; ?>
    <div class="row g-4">
      <?php foreach ($events as $ev): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <div class="card-modern h-100">
            <a href="<?= url('events/detail/' . $ev['slug']) ?>" class="card-img-wrap d-block position-relative">
              <img src="<?= upload_url($ev['cover_image']) ?>" alt="<?= e($ev['title']) ?>" loading="lazy">
              <span class="level-tag"><i class="fa-regular fa-calendar me-1"></i><?= thai_date($ev['start_date']) ?></span>
            </a>
            <div class="card-body">
              <h5><a href="<?= url('events/detail/' . $ev['slug']) ?>" class="text-reset"><?= e($ev['title']) ?></a></h5>
              <div class="card-meta mb-1"><i class="fa-solid fa-location-dot me-1"></i><?= e($ev['location']) ?></div>
              <div class="card-meta mb-2"><i class="fa-regular fa-clock me-1"></i><?= thai_date($ev['start_date'], true) ?></div>
              <p class="card-meta"><?= excerpt(strip_tags($ev['description'] ?? ''), 80) ?></p>
              <?php if ($ev['allow_register']): ?>
                <span class="badge bg-success">เปิดลงทะเบียน</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
