<?php /** เกี่ยวกับเรา */ ?>
<?= page_header('เกี่ยวกับเรา', ['เกี่ยวกับเรา' => '']) ?>

<section class="section">
  <div class="container">
    <!-- ประวัติ -->
    <div class="row align-items-center g-5 mb-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="eyebrow text-gold fw-600">About Us</span>
        <h2 class="mt-2 mb-3" style="color:var(--primary)"><?= e($history['title'] ?? 'ประวัติความเป็นมา') ?></h2>
        <hr class="divider-gold mb-3">
        <div class="article-content"><?= $history['content'] ?? '<p>-</p>' ?></div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <img src="<?= upload_url($history['cover_image'] ?? '') ?>" class="img-fluid rounded-4 shadow" alt="ประวัติ">
      </div>
    </div>

    <!-- วิสัยทัศน์/พันธกิจ -->
    <div class="glass-card p-4 p-lg-5 mb-5" data-aos="fade-up" style="background:var(--accent)">
      <div class="article-content"><?= $vision['content'] ?? '' ?></div>
    </div>
  </div>
</section>

<!-- ผู้บริหาร -->
<?php if (!empty($executives)): ?>
<section class="section section-accent">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Executives</span><h2>ผู้บริหาร</h2><div class="title-underline"></div>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($executives as $ex): ?>
        <div class="col-md-4 col-lg-3" data-aos="fade-up">
          <div class="card-modern text-center p-4 h-100">
            <img src="<?= upload_url($ex['photo']) ?>" class="rounded-circle mb-3 mx-auto" width="120" height="120"
                 style="object-fit:cover" alt="<?= e($ex['full_name']) ?>">
            <h6 class="mb-1"><?= e($ex['full_name']) ?></h6>
            <div class="card-meta"><?= e($ex['position']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- โครงสร้าง/สาขา -->
<?php if (!empty($departments)): ?>
<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Organization</span><h2>หน่วยงาน / สาขาวิชา</h2><div class="title-underline"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($departments as $d): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <div class="card-modern p-4 h-100">
            <div class="feature-icon"><i class="fa-solid fa-sitemap"></i></div>
            <h5><?= e($d['name']) ?></h5>
            <p class="card-meta"><?= excerpt($d['description'] ?? '', 110) ?></p>
            <?php if ($d['head_name']): ?><small class="text-muted"><i class="fa-solid fa-user me-1"></i><?= e($d['head_name']) ?></small><?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- บุคลากร/อาจารย์ -->
<?php if (!empty($teachers)): ?>
<section class="section section-accent">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Team</span><h2>คณาจารย์และบุคลากร</h2><div class="title-underline"></div>
    </div>
    <div class="row g-4">
      <?php foreach (array_merge($teachers, $staff) as $t): ?>
        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
          <div class="card-modern text-center p-3 h-100">
            <img src="<?= upload_url($t['photo']) ?>" class="rounded-circle mb-2 mx-auto" width="90" height="90"
                 style="object-fit:cover" alt="<?= e($t['full_name']) ?>">
            <h6 class="mb-0 small"><?= e($t['full_name']) ?></h6>
            <div class="card-meta small"><?= e($t['position']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
