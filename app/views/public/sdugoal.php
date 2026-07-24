<?php /** SDU Goal : 2568–2571 | The Power of SDU */ ?>
<?= page_header('SDU Goal : 2568–2571', ['รอบรั้วโรงเรียนการเรือน' => '', 'SDU Goal' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Strategic Plan</span>
      <h2>SDU Goal : 2568–2571 | The Power of SDU</h2>
      <div class="title-underline"></div>
      <p>เป้าหมายและทิศทางการพัฒนามหาวิทยาลัยสวนดุสิต ประจำปี พ.ศ. 2568–2571</p>
    </div>

    <?php if (empty($files)): ?>
      <div class="alert alert-warning text-center">ยังไม่มีไฟล์เอกสารในขณะนี้</div>
    <?php else: ?>

      <!-- รายการไฟล์ให้ดาวน์โหลด -->
      <div class="row justify-content-center g-3 mb-5">
        <?php foreach ($files as $f): ?>
          <div class="col-lg-9" data-aos="fade-up">
            <div class="card-modern p-3 p-lg-4">
              <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="feature-icon flex-shrink-0" style="background:#e11d2a"><i class="fa-solid fa-file-pdf"></i></div>
                <div class="flex-grow-1">
                  <h5 class="mb-1"><?= e($f['name']) ?></h5>
                  <span class="card-meta"><i class="fa-solid fa-database me-1"></i>ขนาดไฟล์ <?= e($f['size']) ?> · PDF</span>
                </div>
                <div class="d-flex gap-2">
                  <a href="<?= url('sdugoal/open/' . rawurlencode($f['name'])) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-eye me-1"></i> เปิดดู
                  </a>
                  <a href="<?= url('sdugoal/download/' . rawurlencode($f['name'])) ?>" class="btn btn-primary-custom btn-sm">
                    <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- พรีวิวไฟล์แรก -->
      <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-lg-10">
          <div class="ratio ratio-4x3 rounded-4 shadow-sm overflow-hidden border">
            <iframe src="<?= url('sdugoal/open/' . rawurlencode($files[0]['name'])) ?>#toolbar=1"
                    title="พรีวิวเอกสาร SDU Goal" loading="lazy"></iframe>
          </div>
        </div>
      </div>

    <?php endif; ?>
  </div>
</section>
