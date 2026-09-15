<?php
/** คณะกรรมการประจำโรงเรียนการเรือน */
$icons = [
    'ผู้บริหาร'                 => 'user-tie',
    'ผู้ทรงคุณวุฒิ'              => 'award',
    'ประธานหลักสูตร'            => 'graduation-cap',
    'ผู้แทนจากคณาจารย์ประจำ'    => 'chalkboard-user',
    'เลขานุการ'                 => 'file-signature',
];
?>
<?= page_header('คณะกรรมการประจำโรงเรียนการเรือน', ['รอบรั้วโรงเรียนการเรือน' => '', 'คณะกรรมการประจำโรงเรียนการเรือน' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">School Committee</span>
      <h2>คณะกรรมการประจำโรงเรียนการเรือน</h2>
      <div class="title-underline"></div>
      <p>มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php foreach ($groups as $groupName => $members): ?>
      <div class="mb-5" data-aos="fade-up">
        <h5 class="mb-3 d-flex align-items-center gap-2" style="color:var(--primary)">
          <i class="fa-solid fa-<?= $icons[$groupName] ?? 'user' ?> text-gold"></i> <?= e($groupName) ?>
        </h5>
        <div class="row g-3">
          <?php foreach ($members as $m): ?>
            <div class="col-md-6">
              <button type="button" class="card-modern p-3 h-100 d-flex flex-row align-items-center gap-3 w-100 border-0 text-start person-card"
                      data-bs-toggle="modal" data-bs-target="#personModal"
                      data-name="<?= e($m['full_name']) ?>"
                      data-position="<?= e($m['position']) ?>"
                      data-photo="<?= e(upload_url($m['photo'])) ?>"
                      data-has-photo="<?= !empty($m['photo']) ? '1' : '0' ?>"
                      data-bio="<?= e($m['bio'] ?? '') ?>">
                <?php if (!empty($m['photo'])): ?>
                  <img src="<?= upload_url($m['photo']) ?>" alt="<?= e($m['full_name']) ?>"
                       class="rounded-circle flex-shrink-0" width="56" height="56" style="object-fit:cover">
                <?php else: ?>
                  <div class="feature-icon flex-shrink-0" style="width:56px;height:56px;font-size:1.2rem">
                    <i class="fa-solid fa-user"></i>
                  </div>
                <?php endif; ?>
                <div>
                  <div class="fw-semibold"><?= e($m['full_name']) ?></div>
                  <div class="card-meta small"><?= e($m['position']) ?></div>
                </div>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/_person-modal.php'; ?>
