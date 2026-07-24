<?php /** ติดต่อเรา */ ?>
<?= page_header('ติดต่อเรา', ['ติดต่อเรา' => '']) ?>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5" data-aos="fade-right">
        <span class="eyebrow text-gold fw-600">Get in touch</span>
        <h2 class="mt-2 mb-4" style="color:var(--primary)">ติดต่อสอบถาม</h2>
        <div class="d-flex gap-3 mb-4">
          <div class="feature-icon mb-0 flex-shrink-0"><i class="fa-solid fa-location-dot"></i></div>
          <div><h6 class="mb-0">ที่อยู่</h6><p class="text-muted mb-0"><?= e(setting('site_address')) ?></p></div>
        </div>
        <div class="d-flex gap-3 mb-4">
          <div class="feature-icon mb-0 flex-shrink-0"><i class="fa-solid fa-phone"></i></div>
          <div><h6 class="mb-0">โทรศัพท์</h6><p class="text-muted mb-0"><?= e(setting('site_phone')) ?></p></div>
        </div>
        <div class="d-flex gap-3 mb-4">
          <div class="feature-icon mb-0 flex-shrink-0"><i class="fa-solid fa-envelope"></i></div>
          <div><h6 class="mb-0">อีเมล</h6><p class="text-muted mb-0"><?= e(setting('site_email')) ?></p></div>
        </div>
        <div class="footer-social">
          <a href="<?= e(setting('facebook_url', '#')) ?>" target="_blank" style="background:var(--primary)"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?= e(setting('youtube_url', '#')) ?>" target="_blank" style="background:var(--primary)"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= e(setting('line_url', '#')) ?>" target="_blank" style="background:var(--primary)"><i class="fa-brands fa-line"></i></a>
        </div>
      </div>

      <div class="col-lg-7" data-aos="fade-left">
        <div class="card-modern p-4 p-lg-5">
          <h5 class="mb-4">ส่งข้อความถึงเรา</h5>
          <form method="post" action="<?= url('contact/send') ?>">
            <?= Security::csrfField() ?>
            <!-- honeypot กันบอท -->
            <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">ชื่อ-นามสกุล *</label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required></div>
              <div class="col-md-6"><label class="form-label">อีเมล *</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required></div>
              <div class="col-md-6"><label class="form-label">เบอร์โทร</label>
                <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>"></div>
              <div class="col-md-6"><label class="form-label">หัวข้อ</label>
                <input type="text" name="subject" class="form-control" value="<?= old('subject') ?>"></div>
              <div class="col-12"><label class="form-label">ข้อความ *</label>
                <textarea name="message" class="form-control" rows="5" required><?= old('message') ?></textarea></div>
              <div class="col-12"><button class="btn btn-primary-custom"><i class="fa-solid fa-paper-plane me-1"></i> ส่งข้อความ</button></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Google Map -->
<section class="pb-5">
  <div class="container">
    <div class="rounded-4 overflow-hidden shadow" data-aos="fade-up">
      <?= setting('google_map', '') ?>
    </div>
  </div>
</section>
