<?php /** หน้ารวมหลักสูตร */ ?>
<?= page_header('หลักสูตร', ['หลักสูตร' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Programs</span>
      <h2>หลักสูตรที่เปิดสอน</h2>
      <div class="title-underline"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($curricula as $slug => $name): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <a href="<?= url('curriculum/' . $slug) ?>" class="card-modern p-4 h-100 d-block text-decoration-none">
            <div class="feature-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <h5 class="mb-2" style="color:var(--primary)"><?= e($name) ?></h5>
            <span class="text-gold small fw-500">ดูรายละเอียด <i class="fa-solid fa-arrow-right ms-1"></i></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
