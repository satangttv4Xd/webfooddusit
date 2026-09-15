<?php /** หน้าแรก */ ?>

<!-- ============ HERO CAROUSEL ============ -->
<section class="hero">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover">
    <?php if (count($banner) > 1): ?>
      <div class="carousel-indicators">
        <?php foreach ($banner as $i => $b): ?>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>"
                  class="<?= $i === 0 ? 'active' : '' ?>" aria-label="สไลด์ <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="carousel-inner">
      <?php if (empty($banner)): ?>
        <div class="carousel-item active">
          <img src="<?= asset('images/og-default.svg') ?>" alt="โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต" class="hero-img">
        </div>
      <?php endif; ?>
      <?php foreach ($banner as $i => $b): ?>
        <?php
          $title = trim($b['title'] ?? '');
          $subtitle = trim($b['subtitle'] ?? '');
          $btnText = trim($b['button_text'] ?? '');
          $linkUrl = trim($b['link_url'] ?? '');
          $hasCaption = ($title !== '' || $subtitle !== '');
          $hasLink = ($linkUrl !== '');
        ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <?php if (!$hasCaption && $hasLink): ?>
            <!-- แบนเนอร์กราฟิกล้วนที่มีลิงก์: สามารถกดได้ทั้งภาพ -->
            <a href="<?= url($linkUrl) ?>" class="hero-slide-link" title="<?= e($title ?: 'แบนเนอร์') ?>">
              <img src="<?= upload_url($b['image']) ?>" alt="<?= e($title ?: 'แบนเนอร์') ?>" class="hero-img" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
            </a>
          <?php else: ?>
            <!-- แบนเนอร์กราฟิกปกติ หรือ แบนเนอร์ที่มีข้อความซ้อน -->
            <img src="<?= upload_url($b['image']) ?>" alt="<?= e($title ?: 'แบนเนอร์') ?>" class="hero-img" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
            
            <?php if ($hasCaption): ?>
              <div class="hero-overlay"></div>
              <div class="hero-caption">
                <div class="container">
                  <div class="col-lg-8 col-xl-7" data-aos="fade-up">
                    <span class="hero-badge">โรงเรียนการเรือน</span>
                    <?php if ($title !== ''): ?>
                      <h1><?= e($title) ?></h1>
                    <?php endif; ?>
                    <?php if ($subtitle !== ''): ?>
                      <p class="mb-4"><?= e($subtitle) ?></p>
                    <?php endif; ?>
                    <?php if ($hasLink): ?>
                      <a href="<?= url($linkUrl) ?>" class="btn btn-gold btn-lg">
                        <?= e($btnText ?: 'อ่านเพิ่มเติม') ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (count($banner) > 1): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="สไลด์ก่อนหน้า">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="สไลด์ถัดไป">
        <span class="carousel-control-next-icon"></span>
      </button>
    <?php endif; ?>
  </div>
</section>

<!-- ============ QUICK FEATURES ============ -->
<section class="section pb-0">
  <div class="container">
    <div class="row g-4">
      <?php
      $features = [
        ['fa-graduation-cap', 'หลักสูตรมาตรฐาน', 'หลักสูตรที่ได้รับการรับรอง ทันสมัย ตอบโจทย์ตลาดแรงงาน'],
        ['fa-utensils', 'ครัวมืออาชีพ', 'ห้องปฏิบัติการและครัวมาตรฐานสากลพร้อมอุปกรณ์ครบครัน'],
        ['fa-user-tie', 'คณาจารย์เชี่ยวชาญ', 'เรียนกับอาจารย์และเชฟผู้มีประสบการณ์ระดับประเทศ'],
        ['fa-briefcase', 'พร้อมสู่อาชีพ', 'เครือข่ายสถานประกอบการชั้นนำรองรับการฝึกงานและทำงาน'],
      ];
      foreach ($features as $i => $f): ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <div class="card-modern p-4 h-100 text-center">
            <div class="feature-icon mx-auto"><i class="fa-solid <?= $f[0] ?>"></i></div>
            <h5><?= $f[1] ?></h5>
            <p class="card-meta mb-0"><?= $f[2] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FEATURED PROGRAMS ============ -->
<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Programs</span>
      <h2>หลักสูตรแนะนำ</h2>
      <div class="title-underline"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($featured as $i => $p): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <div class="card-modern program-card">
            <span class="level-tag"><?= e(Program::levelLabel($p['level'])) ?></span>
            <div class="card-img-wrap">
              <img src="<?= upload_url($p['cover_image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
            </div>
            <div class="card-body">
              <h5><?= e($p['name']) ?></h5>
              <p class="card-meta"><?= excerpt($p['overview'] ?? '', 90) ?></p>
              <a href="<?= url('programs/detail/' . $p['slug']) ?>" class="btn btn-outline-gold btn-sm mt-2">
                รายละเอียด <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="<?= url('curriculum') ?>" class="btn btn-primary-custom">ดูหลักสูตรทั้งหมด</a>
    </div>
  </div>
</section>

<!-- ============ STATISTICS ============ -->
<section class="stats-section">
  <div class="container">
    <div class="row g-4">
      <?php
      $statItems = [
        ['fa-book-open', $stats['programs'], 'หลักสูตร'],
        ['fa-newspaper', $stats['news'], 'ข่าวสาร'],
        ['fa-calendar-check', $stats['events'], 'กิจกรรม'],
        ['fa-user-graduate', $stats['alumni'], 'ศิษย์เก่า'],
      ];
      foreach ($statItems as $s): ?>
        <div class="col-6 col-lg-3">
          <div class="stat-box">
            <div class="stat-icon"><i class="fa-solid <?= $s[0] ?>"></i></div>
            <div class="stat-num" data-counter="<?= (int)$s[1] ?>">0</div>
            <div class="stat-label"><?= $s[2] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ LATEST NEWS ============ -->
<section class="section section-accent">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">News & Updates</span>
      <h2>ข่าวสารล่าสุด</h2>
      <div class="title-underline"></div>
    </div>
    <div class="row g-4">
      <?php foreach (array_slice($news, 0, 6) as $i => $n): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
          <article class="card-modern">
            <a href="<?= url('news/detail/' . $n['slug']) ?>" class="card-img-wrap d-block">
              <img src="<?= upload_url($n['cover_image']) ?>" alt="<?= e($n['title']) ?>" loading="lazy">
            </a>
            <div class="card-body">
              <span class="card-cat"><?= e($n['category_name']) ?></span>
              <h5><a href="<?= url('news/detail/' . $n['slug']) ?>" class="text-reset stretched-link"><?= e($n['title']) ?></a></h5>
              <p class="card-meta mb-2"><?= excerpt($n['excerpt'] ?? $n['content'], 85) ?></p>
              <div class="card-meta"><i class="fa-regular fa-calendar me-1"></i><?= thai_date($n['published_at']) ?>
                <span class="ms-2"><i class="fa-regular fa-eye me-1"></i><?= number_format($n['views']) ?></span>
              </div>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="<?= url('news') ?>" class="btn btn-primary-custom">ดูข่าวทั้งหมด</a>
    </div>
  </div>
</section>

<!-- ============ UPCOMING EVENTS + ANNOUNCEMENTS ============ -->
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-7">
        <div class="section-title text-start" data-aos="fade-up">
          <span class="eyebrow">Events</span>
          <h2>กิจกรรมที่กำลังจะมาถึง</h2>
        </div>
        <?php if (empty($events)): ?>
          <p class="text-muted">ยังไม่มีกิจกรรมในขณะนี้</p>
        <?php endif; ?>
        <?php foreach ($events as $ev): ?>
          <div class="card-modern p-3 mb-3" data-aos="fade-up">
            <div class="event-item">
              <div class="event-date">
                <div>
                  <div class="day"><?= date('j', strtotime($ev['start_date'])) ?></div>
                  <div class="month"><?= thai_month($ev['start_date']) ?></div>
                </div>
              </div>
              <div class="flex-grow-1">
                <h5 class="mb-1"><a href="<?= url('events/detail/' . $ev['slug']) ?>" class="text-reset"><?= e($ev['title']) ?></a></h5>
                <div class="card-meta"><i class="fa-solid fa-location-dot me-1"></i><?= e($ev['location']) ?></div>
                <div class="card-meta"><i class="fa-regular fa-clock me-1"></i><?= thai_date($ev['start_date'], true) ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-5">
        <div class="section-title text-start" data-aos="fade-up">
          <span class="eyebrow">Announcements</span>
          <h2>ประกาศ</h2>
        </div>
        <div class="card-modern p-3" data-aos="fade-up">
          <ul class="list-unstyled mb-0">
            <?php if (empty($announce)): ?>
              <li class="text-muted p-2">ยังไม่มีประกาศ</li>
            <?php endif; ?>
            <?php foreach ($announce as $a): ?>
              <li class="d-flex gap-2 py-2 border-bottom">
                <i class="fa-solid fa-bullhorn text-gold mt-1"></i>
                <div>
                  <div class="fw-500"><?= e($a['title']) ?></div>
                  <small class="text-muted"><?= thai_date($a['published_at'] ?? $a['created_at']) ?></small>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ GALLERY PREVIEW ============ -->
<?php if (!empty($albums)): ?>
<section class="section section-accent">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Gallery</span>
      <h2>ประมวลภาพกิจกรรม</h2>
      <div class="title-underline"></div>
    </div>
    <div class="row g-3">
      <?php foreach ($albums as $al): ?>
        <div class="col-md-4" data-aos="zoom-in">
          <a href="<?= url('gallery/album/' . $al['slug']) ?>" class="gallery-item">
            <img src="<?= upload_url($al['cover_image']) ?>" alt="<?= e($al['name']) ?>" loading="lazy">
            <div class="overlay">
              <div class="text-center">
                <i class="fa-solid fa-images"></i>
                <div class="small mt-2"><?= e($al['name']) ?></div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ TEACHERS ============ -->
<?php if (!empty($teachers)): ?>
<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Team</span>
      <h2>อาจารย์</h2>
      <div class="title-underline"></div>
    </div>
    <div class="swiper teachers-swiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <?php foreach ($teachers as $t): ?>
          <div class="swiper-slide">
            <div class="card-modern p-4 text-center h-100 person-card" role="button" tabindex="0"
                 data-name="<?= e($t['full_name']) ?>"
                 data-position="<?= e($t['position'] ?? '') ?>"
                 data-photo="<?= e(upload_url($t['photo'])) ?>"
                 data-has-photo="<?= !empty($t['photo']) ? '1' : '0' ?>"
                 data-bio="<?= e($t['bio'] ?? '') ?>">
              <img src="<?= upload_url($t['photo']) ?>" class="rounded-circle mb-3" width="90" height="90"
                   style="object-fit:cover" alt="<?= e($t['full_name']) ?>">
              <h6 class="mb-1"><?= e($t['full_name']) ?></h6>
              <div class="card-cat mb-2"><?= e($t['position']) ?></div>
              <?php if (!empty($t['expertise'])): ?>
                <p class="card-meta"><?= excerpt($t['expertise'], 80) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/_person-modal.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  new Swiper('.teachers-swiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    grabCursor: true,
    loop: true,
    speed: 900,
    autoplay: { delay: 3200, disableOnInteraction: false, pauseOnMouseEnter: true },
    breakpoints: {
      576: { slidesPerView: 2 },
      992: { slidesPerView: 3 },
      1200: { slidesPerView: 4 }
    },
    on: {
      click: function (sw, e) {
        var card = e.target.closest('.person-card');
        var modalEl = document.getElementById('personModal');
        if (!card || !modalEl) return;
        bootstrap.Modal.getOrCreateInstance(modalEl).show(card);
      }
    }
  });
});
</script>
<?php endif; ?>

<!-- ============ FEATURED ALUMNI ============ -->
<?php if (!empty($alumni)): ?>
<section class="section section-accent">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Success Stories</span>
      <h2>ศิษย์เก่าดีเด่น</h2>
      <div class="title-underline"></div>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($alumni as $a): ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up">
          <div class="card-modern p-4 text-center h-100">
            <img src="<?= upload_url($a['photo']) ?>" class="rounded-circle mb-3" width="90" height="90"
                 style="object-fit:cover" alt="<?= e($a['full_name']) ?>">
            <h6 class="mb-1"><?= e($a['full_name']) ?></h6>
            <div class="card-cat mb-2"><?= e($a['current_job']) ?></div>
            <?php if (!empty($a['quote'])): ?>
              <p class="card-meta fst-italic"><?= excerpt($a['quote'], 80) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="<?= url('alumni') ?>" class="btn btn-primary-custom">ดูศิษย์เก่าทั้งหมด</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ CTA + MAP ============ -->
<section class="section">
  <div class="container">
    <div class="glass-card p-5 text-center" data-aos="zoom-in"
         style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff">
      <h2 class="mb-3" style="color:#fff">พร้อมเริ่มต้นเส้นทางสายอาชีพกับเรา?</h2>
      <p class="mb-4" style="color:rgba(255,255,255,.9)">สมัครเรียนวันนี้ เพื่ออนาคตด้านการประกอบอาหารและการบริการ</p>
      <a href="<?= url('curriculum') ?>" class="btn btn-gold btn-lg me-2">ดูหลักสูตร</a>
      <a href="<?= url('contact') ?>" class="btn btn-outline-light btn-lg">ติดต่อสอบถาม</a>
    </div>
  </div>
</section>
