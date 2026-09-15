<?php
/** บุคลากรสายสนับสนุน — จัดกลุ่มตามหน่วยงาน */
?>
<?= page_header('บุคลากรสายสนับสนุน', ['รอบรั้วโรงเรียนการเรือน' => '', 'บุคลากรสายสนับสนุน' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Support Staff</span>
      <h2>บุคลากรสายสนับสนุน</h2>
      <div class="title-underline"></div>
      <p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php foreach ($groups as $groupName => $members): ?>
      <div class="mb-5" data-aos="fade-up">
        <h5 class="mb-3 d-flex align-items-center gap-2" style="color:var(--primary)">
          <i class="fa-solid fa-user-tie text-gold"></i> <?= e($groupName) ?>
          <span class="badge bg-secondary fw-normal"><?= count($members) ?> คน</span>
        </h5>
        <div class="row g-3">
          <?php foreach ($members as $m): ?>
            <div class="col-md-6 col-lg-4">
              <button type="button" class="card-modern p-3 h-100 d-flex flex-row align-items-center gap-3 w-100 border-0 text-start person-card"
                      data-bs-toggle="modal" data-bs-target="#personModal"
                      data-name="<?= e($m['full_name']) ?>"
                      data-position="<?= e($m['position'] ?? '') ?>"
                      data-photo="<?= e(upload_url($m['photo'])) ?>"
                      data-has-photo="<?= !empty($m['photo']) ? '1' : '0' ?>"
                      data-bio="<?= e($m['bio'] ?? '') ?>">
                <?php if (!empty($m['photo'])): ?>
                  <img src="<?= upload_url($m['photo']) ?>" alt="<?= e($m['full_name']) ?>"
                       class="rounded-circle flex-shrink-0" width="64" height="64" style="object-fit:cover" loading="lazy">
                <?php else: ?>
                  <div class="feature-icon flex-shrink-0" style="width:64px;height:64px;font-size:1.3rem">
                    <i class="fa-solid fa-user"></i>
                  </div>
                <?php endif; ?>
                <div>
                  <div class="fw-semibold"><?= e($m['full_name']) ?></div>
                  <?php if (!empty($m['position'])): ?><div class="card-meta small"><?= e($m['position']) ?></div><?php endif; ?>
                </div>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($groups)): ?>
      <p class="text-muted text-center py-5">ยังไม่มีข้อมูลบุคลากรสายสนับสนุน</p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/_person-modal.php'; ?>
