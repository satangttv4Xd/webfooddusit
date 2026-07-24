<?php /** ศิษย์เก่า */ ?>
<?= page_header('ศิษย์เก่า', ['ศิษย์เก่า' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Success Stories</span>
      <h2>ศิษย์เก่าของเรา</h2>
      <div class="title-underline"></div>
      <p>ความภาคภูมิใจและเส้นทางความสำเร็จของศิษย์เก่าโรงเรียนการเรือน</p>
    </div>

    <?php if (empty($alumni)): ?>
      <div class="alert alert-warning text-center">ยังไม่มีข้อมูลศิษย์เก่า</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($alumni as $a): ?>
          <div class="col-md-6 col-lg-4" data-aos="fade-up">
            <div class="card-modern p-4 text-center h-100">
              <img src="<?= upload_url($a['photo']) ?>" class="rounded-circle mb-3 mx-auto" width="110" height="110"
                   style="object-fit:cover" alt="<?= e($a['full_name']) ?>">
              <h5 class="mb-1"><?= e($a['full_name']) ?></h5>
              <?php if (!empty($a['current_job'])): ?>
                <div class="card-cat mb-1"><?= e($a['current_job']) ?><?= !empty($a['organization']) ? ' · ' . e($a['organization']) : '' ?></div>
              <?php endif; ?>
              <div class="small text-muted mb-2">
                <?php if (!empty($a['program'])): ?><?= e($a['program']) ?><?php endif; ?>
                <?php if (!empty($a['graduate_year'])): ?> · รุ่นปี <?= e($a['graduate_year']) ?><?php endif; ?>
              </div>
              <?php if (!empty($a['quote'])): ?>
                <p class="card-meta fst-italic mb-0">"<?= excerpt($a['quote'], 120) ?>"</p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
