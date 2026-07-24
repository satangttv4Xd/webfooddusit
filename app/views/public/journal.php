<?php /** วารสารวัฒนธรรมอาหารไทย */ ?>
<?= page_header('วารสารวัฒนธรรมอาหารไทย', ['วารสารวัฒนธรรมอาหารไทย' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Journal of Thai Food Culture</span>
      <h2>วารสารวัฒนธรรมอาหารไทย</h2>
      <div class="title-underline"></div>
      <p>ISSN: 2774-0544 (Online) &nbsp;•&nbsp; โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <div class="row g-4">
      <?php foreach ($issues as $it): ?>
        <div class="col-lg-6" data-aos="fade-up">
          <div class="card-modern p-3 h-100">
            <div class="row g-3 align-items-start">
              <div class="col-sm-4">
                <div class="journal-cover">
                  <img src="<?= upload_url('') ?>" alt="ปกวารสาร ปีที่ <?= $it['vol'] ?> ฉบับที่ <?= $it['no'] ?>"
                       class="img-fluid rounded shadow-sm" loading="lazy">
                </div>
              </div>
              <div class="col-sm-8">
                <span class="card-cat text-uppercase">Vol. <?= $it['vol'] ?> No. <?= $it['no'] ?> (<?= $it['year'] ?>)</span>
                <h5 class="mt-1 mb-2"><?= e($it['period']) ?></h5>
                <p class="card-meta"><?= excerpt($it['desc'], 200) ?></p>
                <a href="<?= url($it['link']) ?>" class="btn btn-primary-custom btn-sm mt-1"
                   <?= $it['link'] !== '#' ? 'target="_blank" rel="noopener"' : '' ?>>
                  <i class="fa-solid fa-book-open me-1"></i> อ่านวารสาร
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="text-center text-muted small mt-4 mb-0">
      * ข้อมูลตัวอย่างสำหรับการแสดงผล — ระบบวารสารจริงจะเชื่อมต่อภายหลัง
    </p>
  </div>
</section>
