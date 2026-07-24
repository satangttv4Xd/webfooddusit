<?php /** ทำเนียบคณบดี */ ?>
<?= page_header('ทำเนียบคณบดี', ['รอบรั้วโรงเรียนการเรือน' => '', 'ทำเนียบคณบดี' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Deans</span>
      <h2>ทำเนียบคณบดี</h2>
      <div class="title-underline"></div>
      <p>รายนามคณบดีโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php if (empty($deans)): ?>
      <div class="alert alert-warning text-center">ยังไม่มีข้อมูลคณบดี</div>
    <?php else: ?>
      <div class="row g-4 justify-content-center">
        <?php foreach ($deans as $d): ?>
          <div class="col-sm-6 col-lg-4" data-aos="fade-up">
            <div class="card-modern text-center p-4 h-100">
              <img src="<?= upload_url($d['photo']) ?>" class="rounded-circle mb-3 mx-auto" width="150" height="150"
                   style="object-fit:cover" alt="<?= e($d['full_name']) ?>">
              <h5 class="mb-1"><?= e($d['full_name']) ?></h5>
              <div class="card-cat mb-2"><?= e($d['position']) ?></div>
              <?php if (!empty($d['term'])): ?>
                <p class="card-meta mb-0"><i class="fa-regular fa-calendar me-1"></i><?= e($d['term']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
