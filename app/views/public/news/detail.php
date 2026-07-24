<?php /** รายละเอียดข่าว */ ?>
<?= page_header($news['category_name'], ['ข่าวสาร' => 'news', excerpt($news['title'], 40) => '']) ?>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8">
        <article data-aos="fade-up">
          <span class="card-cat"><?= e($news['category_name']) ?></span>
          <h1 class="h2 mt-2" style="color:var(--primary)"><?= e($news['title']) ?></h1>
          <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
            <span><i class="fa-regular fa-calendar me-1"></i><?= thai_date($news['published_at'], true) ?></span>
            <span><i class="fa-regular fa-eye me-1"></i><?= number_format($news['views']) ?> ครั้ง</span>
          </div>

          <img src="<?= upload_url($news['cover_image']) ?>" class="img-fluid rounded-4 shadow mb-4 w-100" alt="<?= e($news['title']) ?>">

          <div class="article-content"><?= $news['content'] ?></div>

          <!-- Tags -->
          <?php if ($news['tags']): ?>
            <div class="mt-4">
              <?php foreach (explode(',', $news['tags']) as $tag): if (trim($tag) === '') continue; ?>
                <a href="<?= url('search?q=' . urlencode(trim($tag))) ?>" class="badge bg-light text-muted text-decoration-none me-1">#<?= e(trim($tag)) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Share -->
          <div class="mt-4 d-flex align-items-center gap-2">
            <span class="text-muted">แชร์:</span>
            <a class="btn btn-sm btn-outline-primary" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . 'news/detail/' . $news['slug']) ?>"><i class="fa-brands fa-facebook-f"></i></a>
            <a class="btn btn-sm btn-outline-info" target="_blank" href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . 'news/detail/' . $news['slug']) ?>&text=<?= urlencode($news['title']) ?>"><i class="fa-brands fa-x-twitter"></i></a>
            <a class="btn btn-sm btn-outline-success" target="_blank" href="https://social-plugins.line.me/lineit/share?url=<?= urlencode(BASE_URL . 'news/detail/' . $news['slug']) ?>"><i class="fa-brands fa-line"></i></a>
          </div>
        </article>

        <!-- Comments -->
        <?php if ($news['allow_comment']): ?>
          <div class="mt-5" id="comments">
            <h4 class="mb-4">ความคิดเห็น (<?= count($comments) ?>)</h4>
            <?php foreach ($comments as $cm): ?>
              <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                <div class="feature-icon mb-0" style="width:44px;height:44px;font-size:1rem"><i class="fa-solid fa-user"></i></div>
                <div><strong><?= e($cm['name']) ?></strong>
                  <small class="text-muted ms-2"><?= thai_date($cm['created_at']) ?></small>
                  <p class="mb-0 mt-1"><?= nl2br(e($cm['comment'])) ?></p></div>
              </div>
            <?php endforeach; ?>

            <div class="card-modern p-4 mt-4">
              <h6 class="mb-3">แสดงความคิดเห็น</h6>
              <form method="post" action="<?= url('news/comment') ?>">
                <?= Security::csrfField() ?>
                <input type="hidden" name="news_id" value="<?= (int)$news['id'] ?>">
                <input type="hidden" name="slug" value="<?= e($news['slug']) ?>">
                <div class="row g-3">
                  <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="ชื่อของคุณ *" required></div>
                  <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="อีเมล (ไม่บังคับ)"></div>
                  <div class="col-12"><textarea name="comment" class="form-control" rows="3" placeholder="ความคิดเห็น *" required></textarea></div>
                  <div class="col-12"><button class="btn btn-primary-custom">ส่งความคิดเห็น</button></div>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar: related + popular -->
      <div class="col-lg-4">
        <?php if (!empty($related)): ?>
          <div class="card-modern p-4 mb-4">
            <h6 class="mb-3">ข่าวที่เกี่ยวข้อง</h6>
            <?php foreach ($related as $r): ?>
              <a href="<?= url('news/detail/' . $r['slug']) ?>" class="d-flex gap-2 mb-3 text-reset">
                <img src="<?= upload_url($r['cover_image']) ?>" width="70" height="55" style="object-fit:cover;border-radius:8px" alt="">
                <div><small class="fw-500 d-block"><?= excerpt($r['title'], 55) ?></small>
                  <small class="text-muted"><?= thai_date($r['published_at']) ?></small></div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
