<?php /** รายการข่าว */ ?>
<?= page_header('ข่าวสารและกิจกรรม', ['ข่าวสาร' => '']) ?>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8">
        <!-- ค้นหา -->
        <form class="mb-4" method="get" action="<?= url('news') ?>">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="ค้นหาข่าว..." value="<?= e($keyword) ?>">
            <?php if ($catSlug): ?><input type="hidden" name="cat" value="<?= e($catSlug) ?>"><?php endif; ?>
            <button class="btn btn-primary-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
        </form>

        <?php if (empty($pager['data'])): ?>
          <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-newspaper fa-3x mb-3"></i><p>ไม่พบข่าวที่ค้นหา</p>
          </div>
        <?php endif; ?>

        <div class="row g-4">
          <?php foreach ($pager['data'] as $n): ?>
            <div class="col-md-6" data-aos="fade-up">
              <article class="card-modern h-100">
                <a href="<?= url('news/detail/' . $n['slug']) ?>" class="card-img-wrap d-block">
                  <img src="<?= upload_url($n['cover_image']) ?>" alt="<?= e($n['title']) ?>" loading="lazy">
                </a>
                <div class="card-body">
                  <span class="card-cat"><?= e($n['category_name']) ?></span>
                  <h5><a href="<?= url('news/detail/' . $n['slug']) ?>" class="text-reset"><?= e($n['title']) ?></a></h5>
                  <p class="card-meta"><?= excerpt($n['excerpt'] ?? $n['content'], 100) ?></p>
                  <div class="card-meta"><i class="fa-regular fa-calendar me-1"></i><?= thai_date($n['published_at']) ?></div>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-5"><?= pagination($pager, 'news') ?></div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card-modern p-4 mb-4">
          <h6 class="mb-3">หมวดหมู่</h6>
          <ul class="list-unstyled mb-0">
            <li class="py-1"><a href="<?= url('news') ?>" class="text-reset d-flex justify-content-between <?= !$catSlug ? 'fw-bold text-gold' : '' ?>">
              <span>ทั้งหมด</span></a></li>
            <?php foreach ($categories as $c): ?>
              <li class="py-1 border-top pt-2">
                <a href="<?= url('news?cat=' . $c['slug']) ?>" class="text-reset d-flex justify-content-between <?= $catSlug === $c['slug'] ? 'fw-bold text-gold' : '' ?>">
                  <span><?= e($c['name']) ?></span><span class="badge bg-light text-muted"><?= $c['news_count'] ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="card-modern p-4">
          <h6 class="mb-3">ข่าวยอดนิยม</h6>
          <?php foreach ($popular as $p): ?>
            <a href="<?= url('news/detail/' . $p['slug']) ?>" class="d-flex gap-2 mb-3 text-reset">
              <img src="<?= upload_url($p['cover_image']) ?>" width="70" height="55" style="object-fit:cover;border-radius:8px" alt="">
              <div><small class="fw-500 d-block"><?= excerpt($p['title'], 55) ?></small>
                <small class="text-muted"><i class="fa-regular fa-eye me-1"></i><?= number_format($p['views']) ?></small></div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
