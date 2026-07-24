<?php /** รายละเอียดหลักสูตร */ ?>
<?= page_header($program['name'], ['หลักสูตร' => 'programs', $program['name'] => '']) ?>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8">
        <img src="<?= upload_url($program['cover_image']) ?>" class="img-fluid rounded-4 shadow mb-4" alt="<?= e($program['name']) ?>" data-aos="fade-up">

        <span class="card-cat"><?= e(Program::levelLabel($program['level'])) ?></span>
        <h2 class="mt-2" style="color:var(--primary)"><?= e($program['name']) ?></h2>
        <?php if ($program['name_en']): ?><p class="text-muted"><?= e($program['name_en']) ?></p><?php endif; ?>

        <?php if ($program['overview']): ?>
          <h4 class="mt-4">ภาพรวมหลักสูตร</h4><hr class="divider-gold mb-3">
          <div class="article-content"><?= nl2br(e($program['overview'])) ?></div>
        <?php endif; ?>

        <?php if ($program['qualification']): ?>
          <h4 class="mt-4">คุณสมบัติผู้สมัคร</h4><hr class="divider-gold mb-3">
          <div class="article-content"><?= nl2br(e($program['qualification'])) ?></div>
        <?php endif; ?>

        <?php if ($program['careers']): ?>
          <h4 class="mt-4">อาชีพหลังสำเร็จการศึกษา</h4><hr class="divider-gold mb-3">
          <div class="article-content"><?= nl2br(e($program['careers'])) ?></div>
        <?php endif; ?>

        <?php if (!empty($curriculum)): ?>
          <h4 class="mt-4">โครงสร้างหลักสูตร / รายวิชา</h4><hr class="divider-gold mb-3">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light"><tr><th>รหัสวิชา</th><th>ชื่อวิชา</th><th>ชั้นปี</th><th class="text-end">หน่วยกิต</th></tr></thead>
              <tbody>
                <?php foreach ($curriculum as $c): ?>
                  <tr><td><?= e($c['course_code']) ?></td><td><?= e($c['course_name']) ?></td>
                      <td><?= e($c['year']) ?></td><td class="text-end"><?= e($c['credits']) ?></td></tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <?php if ($program['content']): ?>
          <div class="article-content mt-4"><?= $program['content'] ?></div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card-modern p-4 mb-4 sticky-top" style="top:90px" data-aos="fade-left">
          <h5 class="mb-3">ข้อมูลหลักสูตร</h5>
          <ul class="list-unstyled">
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">ระดับ</span><strong><?= e(Program::levelLabel($program['level'])) ?></strong></li>
            <?php if ($program['degree']): ?><li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">ปริญญา</span><strong class="text-end"><?= e($program['degree']) ?></strong></li><?php endif; ?>
            <?php if ($program['duration']): ?><li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">ระยะเวลา</span><strong><?= e($program['duration']) ?></strong></li><?php endif; ?>
            <?php if ($program['credits']): ?><li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">หน่วยกิต</span><strong><?= e($program['credits']) ?></strong></li><?php endif; ?>
            <?php if ($program['tuition_fee']): ?><li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">ค่าใช้จ่าย</span><strong class="text-end"><?= e($program['tuition_fee']) ?></strong></li><?php endif; ?>
          </ul>
          <div class="d-grid gap-2 mt-3">
            <?php if ($program['apply_url']): ?>
              <a href="<?= e($program['apply_url']) ?>" target="_blank" class="btn btn-gold"><i class="fa-solid fa-pen-to-square me-1"></i> สมัครเรียน</a>
            <?php endif; ?>
            <?php if ($program['pdf_file']): ?>
              <a href="<?= upload_url($program['pdf_file']) ?>" target="_blank" class="btn btn-outline-gold"><i class="fa-solid fa-file-pdf me-1"></i> ดาวน์โหลดรายละเอียด</a>
            <?php endif; ?>
            <a href="<?= url('contact') ?>" class="btn btn-primary-custom"><i class="fa-solid fa-envelope me-1"></i> สอบถามข้อมูล</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
