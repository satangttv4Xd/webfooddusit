<?php
/** หน้าแผนการศึกษา (แยกหน้า) */
$p = $plan;
?>
<?= page_header('แผนการศึกษา', [
    'หลักสูตร' => 'curriculum',
    $name      => 'curriculum/' . $slug,
    'แผนการศึกษา' => '',
]) ?>

<section class="section">
  <div class="container">

    <div class="mb-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>

    <h4 class="mb-1"><i class="fa-solid fa-calendar-days me-2" style="color:var(--gold)"></i>แผนการศึกษา (ตลอดหลักสูตร)</h4>
    <p class="text-muted mb-1"><?= e($name) ?></p>
    <?php if (!empty($p['intro'])): ?><p class="mb-3"><?= e($p['intro']) ?></p><?php endif; ?>
    <hr class="divider-gold mb-4" style="max-width:120px">

    <?php if (!empty($p['pdf'])): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <a href="<?= upload_url($p['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">
          <i class="fa-solid fa-file-pdf me-1"></i> เปิดดูแผนการศึกษา (PDF)
        </a>
        <a href="<?= upload_url($p['pdf']) ?>" download class="btn btn-outline-gold">
          <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
        </a>
      </div>
    <?php endif; ?>

    <!-- ตารางแผนการศึกษาแต่ละภาคเรียน -->
    <?php foreach ($p['semesters'] as $sem): ?>
      <div class="card-modern p-4 mb-4" data-aos="fade-up">
        <h5 class="mb-3"><?= e($sem['title']) ?></h5>
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:110px">รหัสวิชา</th>
                <th>ชื่อวิชา</th>
                <th class="text-center" style="width:80px">หน่วยกิต</th>
                <th class="text-center" style="width:70px">ทฤษฎี</th>
                <th class="text-center" style="width:70px">ปฏิบัติ</th>
                <th class="text-center" style="width:90px">ศึกษาด้วยตนเอง</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sem['sections'] as $sc): ?>
                <tr class="table-light">
                  <td colspan="6" class="fw-semibold" style="color:var(--primary)"><?= e($sc['label']) ?></td>
                </tr>
                <?php foreach ($sc['items'] as $it): ?>
                  <tr>
                    <td class="text-nowrap"><?= e($it[0]) ?></td>
                    <td><?= e($it[1]) ?></td>
                    <td class="text-center"><?= e($it[2]) ?></td>
                    <td class="text-center"><?= e($it[3]) ?></td>
                    <td class="text-center"><?= e($it[4]) ?></td>
                    <td class="text-center"><?= e($it[5]) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endforeach; ?>
              <tr>
                <td colspan="2" class="text-end fw-semibold">รวม</td>
                <td class="text-center fw-semibold" style="color:var(--gold)"><?= e($sem['total']) ?></td>
                <td colspan="3"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (!empty($p['note'])): ?>
      <div class="alert alert-light border" role="alert"><i class="fa-solid fa-circle-info me-1 text-muted"></i><?= e($p['note']) ?></div>
    <?php endif; ?>
    <p class="text-muted small">หมายเหตุ: หน่วยชั่วโมง ทฤษฎี / ปฏิบัติ / ศึกษาด้วยตนเอง คิดเป็น ชม./สัปดาห์</p>

    <div class="mt-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>
  </div>
</section>
