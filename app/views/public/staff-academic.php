<?php
/** บุคลากรสายวิชาการ — จัดกลุ่มตามหลักสูตร/วิทยาเขต */
?>
<?= page_header('บุคลากรสายวิชาการ', ['รอบรั้วโรงเรียนการเรือน' => '', 'บุคลากรสายวิชาการ' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Academic Staff</span>
      <h2>บุคลากรสายวิชาการ</h2>
      <div class="title-underline"></div>
      <p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php foreach ($groups as $groupName => $members): ?>
      <div class="mb-5" data-aos="fade-up">
        <h5 class="mb-3 d-flex align-items-center gap-2" style="color:var(--primary)">
          <i class="fa-solid fa-graduation-cap text-gold"></i> <?= e($groupName) ?>
          <span class="badge bg-secondary fw-normal"><?= count($members) ?> คน</span>
        </h5>
        <div class="row g-3">
          <?php foreach ($members as $m): ?>
            <div class="col-6 col-md-4 col-lg-3">
              <button type="button" class="card-modern p-3 h-100 text-center w-100 border-0 person-card"
                      data-bs-toggle="modal" data-bs-target="#personModal"
                      data-name="<?= e($m['full_name']) ?>"
                      data-position="<?= e($m['position'] ?? '') ?>"
                      data-photo="<?= e(upload_url($m['photo'])) ?>"
                      data-has-photo="<?= !empty($m['photo']) ? '1' : '0' ?>"
                      data-bio="<?= e($m['bio'] ?? '') ?>">
                <?php if (!empty($m['photo'])): ?>
                  <img src="<?= upload_url($m['photo']) ?>" alt="<?= e($m['full_name']) ?>"
                       class="rounded-circle mx-auto mb-2" width="72" height="72" style="object-fit:cover" loading="lazy">
                <?php else: ?>
                  <div class="feature-icon mx-auto mb-2" style="width:72px;height:72px;font-size:1.5rem">
                    <i class="fa-solid fa-user"></i>
                  </div>
                <?php endif; ?>
                <div class="fw-semibold small"><?= e($m['full_name']) ?></div>
                <?php if (!empty($m['position'])): ?><div class="card-meta small"><?= e($m['position']) ?></div><?php endif; ?>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($groups)): ?>
      <p class="text-muted text-center py-5">ยังไม่มีข้อมูลบุคลากรสายวิชาการ</p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/_person-modal.php'; ?>
