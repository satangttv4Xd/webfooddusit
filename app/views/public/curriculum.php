<?php
/** หน้าหลักสูตร (โครงสร้าง/skeleton) */
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

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="<?= url('curriculum') ?>" class="btn btn-link"><i class="fa-solid fa-arrow-left me-1"></i> ดูหลักสูตรทั้งหมด</a>
      <a href="<?= url('contact') ?>" class="btn btn-primary-custom ms-2">สอบถามข้อมูลหลักสูตร</a>
    </div>
  </div>
</section>
