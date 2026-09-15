<?php
/** หน้าหลักสูตร — แสดงข้อมูลจริงเมื่อมี $detail มิฉะนั้นแสดงโครงสร้าง (skeleton) */
$detail = $detail ?? null;

/** ปุ่มลัดไปยังหัวข้อย่อยของหลักสูตร (โครงไว้ก่อน — เนื้อหาเติมภายหลัง) */
$navButtons = [
    ['fa-book-open',       'รายวิชา',                 '#courses'],
    ['fa-clipboard-list',  'แผนการรับ',               'https://tcas.dusit.ac.th/faculty/'],
    ['fa-calendar-days',   'แผนการศึกษา',             '#plan'],
    ['fa-user-check',      'คุณสมบัติผู้เรียน',        '#qual'],
    ['fa-briefcase',       'โอกาสในการประกอบอาชีพ',   '#career'],
    ['fa-award',           'ทุนการศึกษา',             '#scholarship'],
];

/** หัวข้อโครงสร้าง (ใช้เมื่อยังไม่มีข้อมูลจริง) */
$sections = [
    ['fa-circle-info',      'ชื่อหลักสูตร',            'ชื่อหลักสูตรภาษาไทยและภาษาอังกฤษ ชื่อปริญญา'],
    ['fa-lightbulb',        'ปรัชญาและวัตถุประสงค์',  'ปรัชญา ความสำคัญ และวัตถุประสงค์ของหลักสูตร'],
    ['fa-star',             'จุดเด่นของหลักสูตร',      'จุดเด่น ความพิเศษ และสิ่งที่ผู้เรียนจะได้รับ'],
    ['fa-diagram-project',  'โครงสร้างหลักสูตร',      'จำนวนหน่วยกิต หมวดวิชา และรายวิชา'],
    ['fa-briefcase',        'แนวทางการประกอบอาชีพ',   'อาชีพที่รองรับหลังสำเร็จการศึกษา'],
    ['fa-coins',            'ค่าธรรมเนียมการศึกษา',   'อัตราค่าเล่าเรียนต่อภาคการศึกษา'],
    ['fa-file-lines',       'คุณสมบัติและการรับสมัคร', 'คุณสมบัติผู้สมัคร เกณฑ์ และช่องทางการรับสมัคร'],
];
?>
<?= page_header($name, ['หลักสูตร' => 'curriculum', $name => '']) ?>

<section class="section">
  <div class="container">

    <?php if ($detail): ?>
      <!-- ========== ปุ่มลัดหัวข้อหลักสูตร (ตามแบบที่กำหนด) ========== -->
      <div class="row g-3 mb-5">
        <?php foreach ($navButtons as $i => $b): ?>
          <?php // ปุ่มทุนการศึกษา แสดงเฉพาะหน้าที่มีข้อมูลทุนเท่านั้น
                if (($b[2] ?? '') === '#scholarship' && empty($detail['scholarship'])) continue; ?>
          <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
            <?php
              $href = $b[2] ?? '#';
              if ($href === '#courses') {
                  $href = !empty($detail['courses']) ? url('curriculum/courses/' . $slug) : '#';
              }
              if ($href === '#plan') {
                  $href = !empty($detail['plan']) ? url('curriculum/plan/' . $slug) : '#';
              }
              if ($href === '#qual') {
                  $href = !empty($detail['qualification']) ? url('curriculum/qualification/' . $slug) : '#';
              }
              if ($href === '#career') {
                  $href = !empty($detail['career']) ? url('curriculum/career/' . $slug) : '#';
              }
              if ($href === '#scholarship') {
                  $href = !empty($detail['scholarship']) ? url('curriculum/scholarship/' . $slug) : '#';
              }
              $isExternal = str_starts_with($href, 'http');
            ?>
            <a href="<?= $href ?>" class="curri-btn"<?= $isExternal ? ' target="_blank" rel="noopener"' : '' ?>>
              <span class="curri-btn__icon"><i class="fa-solid <?= $b[0] ?>"></i></span>
              <span class="curri-btn__label"><?= e($b[1]) ?></span>
            </a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="row g-4">
        <!-- ชื่อปริญญา -->
        <div class="col-md-6" data-aos="fade-up">
          <div class="card-modern p-4 h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-graduation-cap"></i></div>
              <div>
                <h5 class="mb-2">ชื่อปริญญา</h5>
                <p class="mb-1"><?= e($detail['degree_th']) ?></p>
                <p class="mb-0 text-muted"><?= e($detail['degree_en']) ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- ชื่อสาขาวิชา -->
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="card-modern p-4 h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-circle-info"></i></div>
              <div>
                <h5 class="mb-2">ชื่อสาขาวิชา</h5>
                <p class="mb-1"><?= e($detail['name_th']) ?></p>
                <p class="mb-0 text-muted"><?= e($detail['name_en']) ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- ปรัชญาและวัตถุประสงค์ -->
        <div class="col-12" data-aos="fade-up">
          <div class="card-modern p-4 h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-lightbulb"></i></div>
              <div>
                <h5 class="mb-2">ปรัชญาและวัตถุประสงค์</h5>
                <p class="mb-0"><?= nl2br(e($detail['philosophy'])) ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- อัตราค่าธรรมเนียมการศึกษา -->
        <div class="col-lg-7" data-aos="fade-up">
          <div class="card-modern p-4 h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-coins"></i></div>
              <div class="w-100">
                <h5 class="mb-3">อัตราค่าธรรมเนียมการศึกษา</h5>

                <?php $tu = $detail['tuition']; ?>
                <?php if (!empty($tu['lines'])): // แบบรายการ (เช่น ป.โท) ?>
                  <?php if (!empty($tu['title'])): ?><p class="fw-semibold mb-2" style="color:var(--primary)"><?= e($tu['title']) ?></p><?php endif; ?>
                  <?php if (!empty($tu['intro'])): ?><p class="mb-2"><?= e($tu['intro']) ?></p><?php endif; ?>
                  <ol class="mb-0 ps-3">
                    <?php foreach ($tu['lines'] as $ln): ?>
                      <li class="mb-1"><?= e($ln[0]) ?>
                        <?php if (!empty($ln[1])): ?>
                          <ul class="mt-1"><?php foreach ($ln[1] as $sub): ?><li><?= e($sub) ?></li><?php endforeach; ?></ul>
                        <?php endif; ?>
                      </li>
                    <?php endforeach; ?>
                  </ol>

                <?php elseif (!empty($tu['tables'])): // แบบตารางตามช่วงหน่วยกิต (เช่น ป.ตรี) ?>
                  <?php foreach ($tu['tables'] as $ti => $tb): ?>
                    <p class="fw-semibold mb-2 <?= $ti > 0 ? 'mt-4' : '' ?>" style="color:var(--primary)"><?= e($tb['title']) ?></p>
                    <?php if (!empty($tb['fixed'])): ?>
                      <ul class="ps-3 mb-2"><?php foreach ($tb['fixed'] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
                    <?php endif; ?>
                    <p class="mb-1 small text-muted">ค่าบำรุงการศึกษา (คำนวณตามจำนวนหน่วยกิต):</p>
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light"><tr><?php foreach ($tb['head'] as $h): ?><th class="text-center small"><?= e($h) ?></th><?php endforeach; ?></tr></thead>
                        <tbody>
                          <?php foreach ($tb['rows'] as $r): $last = count($r) - 1; ?>
                            <tr>
                              <?php foreach ($r as $ci => $cell): ?>
                                <td class="<?= $ci === 0 ? 'text-center' : 'text-end' ?><?= ($ci === $last && $last > 0) ? ' fw-semibold' : '' ?>"><?= e($cell) ?></td>
                              <?php endforeach; ?>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php endforeach; ?>
                  <?php if (!empty($tu['note'])): ?><p class="mt-3 mb-0"><?= e($tu['note']) ?></p><?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- ติดต่อ -->
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
          <div class="card-modern p-4 h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-address-card"></i></div>
              <div>
                <h5 class="mb-2">ติดต่อ</h5>
                <p class="mb-1 fw-semibold"><?= e($detail['contact']['name']) ?></p>
                <p class="mb-2"><?= e($detail['contact']['org']) ?></p>
                <p class="mb-1"><i class="fa-solid fa-location-dot me-2 text-muted"></i><?= e($detail['contact']['address']) ?></p>
                <p class="mb-1"><i class="fa-solid fa-phone me-2 text-muted"></i><?= e($detail['contact']['phone']) ?></p>
                <p class="mb-0"><i class="fa-solid fa-envelope me-2 text-muted"></i><a href="mailto:<?= e($detail['contact']['email']) ?>"><?= e($detail['contact']['email']) ?></a></p>

                <?php if (!empty($detail['contact']['branches'])): ?>
                  <hr class="my-3">
                  <?php foreach ($detail['contact']['branches'] as $br): ?>
                    <div class="mb-2">
                      <div class="fw-semibold small"><?= e($br[0]) ?></div>
                      <div class="small"><i class="fa-solid fa-phone me-2 text-muted"></i><?= e($br[1]) ?></div>
                      <?php if (!empty($br[2])): ?><div class="small"><i class="fa-solid fa-envelope me-2 text-muted"></i><a href="mailto:<?= e($br[2]) ?>"><?= e($br[2]) ?></a></div><?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ========== ภาพบรรยากาศการเรียนการสอน ========== -->
      <?php if (!empty($detail['gallery'])): ?>
        <h4 class="mt-5 mb-3 text-center">ภาพบรรยากาศการเรียนการสอน</h4>
        <hr class="divider-gold mx-auto mb-4" style="max-width:120px">
        <div class="row g-3">
          <?php foreach ($detail['gallery'] as $i => $img): ?>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
              <img src="<?= upload_url($img) ?>" alt="บรรยากาศการเรียนการสอน <?= $i + 1 ?>"
                   class="img-fluid rounded-4 shadow-sm w-100" style="height:220px;object-fit:cover" loading="lazy">
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- ========== หน้าโครงสร้าง (ยังไม่มีข้อมูลจริง) ========== -->
      <div class="alert alert-info d-flex align-items-center gap-2" role="alert" data-aos="fade-up">
        <i class="fa-solid fa-circle-info"></i>
        <span>หน้านี้เป็น<strong>โครงสร้างเบื้องต้น</strong> เนื้อหารายละเอียดอยู่ระหว่างจัดทำ</span>
      </div>

      <div class="row g-4 mt-1">
        <?php foreach ($sections as $i => $s): ?>
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 100 ?>">
            <div class="card-modern p-4 h-100">
              <div class="d-flex align-items-start gap-3">
                <div class="feature-icon flex-shrink-0"><i class="fa-solid <?= $s[0] ?>"></i></div>
                <div>
                  <h5 class="mb-1"><?= e($s[1]) ?></h5>
                  <p class="card-meta mb-0"><?= e($s[2]) ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="<?= url('curriculum') ?>" class="btn btn-link"><i class="fa-solid fa-arrow-left me-1"></i> ดูหลักสูตรทั้งหมด</a>
      <a href="<?= url('contact') ?>" class="btn btn-primary-custom ms-2">สอบถามข้อมูลหลักสูตร</a>
    </div>
  </div>
</section>
