<?php
/** หน้ารายวิชา / โครงสร้างหลักสูตร (แยกหน้า) */
$c = $courses;
?>
<?= page_header('รายวิชา / โครงสร้างหลักสูตร', [
    'หลักสูตร'         => 'curriculum',
    $name             => 'curriculum/' . $slug,
    'รายวิชา'          => '',
]) ?>

<section class="section">
  <div class="container">

    <div class="mb-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>

    <h4 class="mb-1"><i class="fa-solid fa-book-open me-2" style="color:var(--gold)"></i><?= e($name) ?></h4>
    <hr class="divider-gold mb-4" style="max-width:120px">

    <?php if (!empty($c['pdf'])): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <a href="<?= upload_url($c['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">
          <i class="fa-solid fa-file-pdf me-1"></i> เปิดดูเอกสารหลักสูตร (PDF)
        </a>
        <a href="<?= upload_url($c['pdf']) ?>" download class="btn btn-outline-gold">
          <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
        </a>
      </div>
    <?php endif; ?>

    <!-- สรุปหน่วยกิต -->
    <div class="card-modern p-4 mb-4" data-aos="fade-up">
      <p class="fw-semibold mb-3" style="color:var(--primary)">จำนวนหน่วยกิตที่ศึกษาตลอดหลักสูตร <?= (int)$c['total'] ?> หน่วยกิต</p>
      <div class="row g-3">
        <?php foreach ($c['summary'] as $s): ?>
          <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center h-100">
              <div class="h4 mb-0" style="color:var(--gold)"><?= (int)$s[1] ?></div>
              <div class="text-muted small">หน่วยกิต</div>
              <div class="mt-1"><?= e($s[0]) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ตารางรายวิชาแต่ละหมวด -->
    <?php foreach ($c['groups'] as $g): ?>
      <div class="card-modern p-4 mb-4" data-aos="fade-up">
        <h5 class="mb-3"><?= e($g['title']) ?></h5>
        <?php foreach ($g['subgroups'] as $sg): ?>
          <?php if (!empty($sg['label'])): ?>
            <p class="fw-semibold mt-3 mb-2" style="color:var(--primary)"><?= e($sg['label']) ?></p>
          <?php endif; ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width:120px">รหัสวิชา</th>
                  <th>ชื่อวิชา</th>
                  <th class="text-end" style="width:110px">หน่วยกิต</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sg['items'] as $it): ?>
                  <tr>
                    <td class="text-nowrap"><?= e($it[0]) ?></td>
                    <td>
                      <div><?= e($it[1]) ?></div>
                      <div class="text-muted small"><?= e($it[2]) ?></div>
                    </td>
                    <td class="text-end text-nowrap"><?= e($it[3]) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>

    <?php if (!empty($c['notes'])): ?>
      <div class="alert alert-light border" role="alert">
        <?php foreach ($c['notes'] as $n): ?>
          <div class="mb-2"><i class="fa-solid fa-circle-info me-1 text-muted"></i><?= e($n) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>
  </div>
</section>
