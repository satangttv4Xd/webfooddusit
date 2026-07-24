<?php /** ภาพในอัลบั้ม */ ?>
<?= page_header($album['name'], ['แกลเลอรี' => 'gallery', $album['name'] => '']) ?>

<section class="section">
  <div class="container">
    <?php if ($album['description']): ?>
      <p class="text-center text-muted mb-4"><?= e($album['description']) ?></p>
    <?php endif; ?>

    <div class="row g-3">
      <?php foreach ($items as $it): ?>
        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
          <?php if ($it['type'] === 'video' && $it['video_url']): ?>
            <a href="<?= e($it['video_url']) ?>" target="_blank" class="gallery-item">
              <img src="<?= upload_url($it['file_path']) ?>" alt="<?= e($it['title']) ?>" loading="lazy">
              <div class="overlay"><i class="fa-solid fa-play"></i></div>
            </a>
          <?php else: ?>
            <a href="<?= upload_url($it['file_path']) ?>" target="_blank" class="gallery-item">
              <img src="<?= upload_url($it['file_path']) ?>" alt="<?= e($it['title']) ?>" loading="lazy">
              <div class="overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($items)): ?>
      <p class="text-center text-muted py-5">ยังไม่มีรูปในอัลบั้มนี้</p>
    <?php endif; ?>
  </div>
</section>
