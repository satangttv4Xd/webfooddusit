<?php /** อัลบั้มแกลเลอรี */ ?>
<?= page_header('แกลเลอรี', ['แกลเลอรี' => '']) ?>

<section class="section">
  <div class="container">
    <?php if (empty($albums)): ?>
      <p class="text-center text-muted py-5">ยังไม่มีอัลบั้ม</p>
    <?php endif; ?>
    <div class="row g-4">
      <?php foreach ($albums as $al): ?>
        <div class="col-md-6 col-lg-4" data-aos="zoom-in">
          <a href="<?= url('gallery/album/' . $al['slug']) ?>" class="card-modern d-block text-reset">
            <div class="card-img-wrap position-relative">
              <img src="<?= upload_url($al['cover_image']) ?>" alt="<?= e($al['name']) ?>" loading="lazy">
              <span class="level-tag"><i class="fa-solid fa-images me-1"></i><?= $al['item_count'] ?> รูป</span>
            </div>
            <div class="card-body">
              <h5><?= e($al['name']) ?></h5>
              <div class="card-meta"><i class="fa-regular fa-calendar me-1"></i><?= thai_date($al['event_date']) ?></div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
