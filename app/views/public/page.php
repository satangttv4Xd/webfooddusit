<?php /** หน้าเนื้อหาทั่วไป */ ?>
<?= page_header($page['title'], [$page['title'] => '']) ?>
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9" data-aos="fade-up">
        <?php if ($page['cover_image']): ?>
          <img src="<?= upload_url($page['cover_image']) ?>" class="img-fluid rounded-4 shadow mb-4 w-100" alt="<?= e($page['title']) ?>">
        <?php endif; ?>
        <div class="article-content"><?= $page['content'] ?></div>
      </div>
    </div>
  </div>
</section>
