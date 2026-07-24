<?php /** ประวัติความเป็นมา */ ?>
<?= page_header('ประวัติความเป็นมา', ['รอบรั้วโรงเรียนการเรือน' => '', 'ประวัติความเป็นมา' => '']) ?>

<section class="section">
  <div class="container">

    <!-- บทนำ -->
    <div class="row align-items-center g-5 mb-5">
      <div class="col-lg-7" data-aos="fade-right">
        <span class="eyebrow text-gold fw-600">About Us</span>
        <h2 class="mt-2 mb-3" style="color:var(--primary)">โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</h2>
        <hr class="divider-gold mb-3">
        <p class="lead" style="font-size:1.1rem;line-height:1.9">
          ตลอดระยะเวลาที่ผ่านมากว่า <strong>80 ปี</strong> มหาวิทยาลัยสวนดุสิตได้พัฒนาการจัดการเรียนการสอน
          และสร้างความเข้มแข็งทางวิชาการด้านอาหารมาอย่างต่อเนื่อง จนเป็นที่ยอมรับในระดับประเทศและนานาชาติ
        </p>
        <p style="line-height:1.9">
          เมื่อวันที่ <strong>26 กุมภาพันธ์ 2553</strong> <em>โรงเรียนการเรือน (School of Culinary Arts)</em>
          ได้ถูกจัดตั้งขึ้นเป็นหน่วยงานเทียบเท่าคณะ เพื่อมุ่งผลิตบัณฑิตด้านศาสตร์การประกอบอาหาร
          คหกรรมศาสตร์ และการบริการที่มีคุณภาพสู่สังคม
        </p>
      </div>
      <div class="col-lg-5" data-aos="fade-left">
        <img src="https://food.dusit.ac.th/home/wp-content/uploads/2024/06/SCA.png"
             onerror="this.onerror=null;this.src='<?= asset('images/og-default.svg') ?>'"
             class="img-fluid rounded-4 shadow" alt="โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต">
      </div>
    </div>

    <!-- สมรรถนะหลัก (ภาพอนาคต / จุดมุ่งเน้น / พันธกิจ) -->
    <?php
    $cores = [
        ['fa-eye',      'ภาพอนาคต',  'เป็นสถาบันการศึกษาทางด้านอาหาร ที่ตอบสนองความต้องการของผู้เรียนในทุกมิติและทุกช่วงวัย'],
        ['fa-bullseye', 'จุดมุ่งเน้น', 'เน้นการปฏิบัติด้วยความเชี่ยวชาญด้านอาหารจนเป็นมืออาชีพ บนพื้นฐานทฤษฎีที่ถูกต้อง'],
        ['fa-flag',     'พันธกิจ',    'โรงเรียนการเรือนเป็นหน่วยงานผลิตบัณฑิตให้มีคุณภาพทางวิชาชีพขั้นสูงด้านการประกอบอาหารและการจัดตกแต่ง การสร้างและพัฒนาองค์ความรู้และนวัตกรรมเพื่อเผยแพร่และเป็นแหล่งอ้างอิงแก่สังคม และทำนุบำรุงศิลปะและวัฒนธรรม'],
    ];
    ?>
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Core Competency</span>
      <h2>สมรรถนะหลักของโรงเรียนการเรือน</h2>
      <div class="title-underline"></div>
      <p>เชี่ยวชาญด้านการประกอบอาหารไทยและการจัดตกแต่ง การบูรณาการศาสตร์ด้านอาหาร เป็นแหล่งอ้างอิง องค์ความรู้ และนวัตกรรมแก่สังคม</p>
    </div>
    <div class="row g-4 mb-5">
      <?php foreach ($cores as $i => $c): ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <div class="card-modern p-4 text-center h-100">
            <div class="feature-icon mx-auto"><i class="fa-solid <?= $c[0] ?>"></i></div>
            <h5 class="mb-2"><?= e($c[1]) ?></h5>
            <p class="card-meta mb-0"><?= e($c[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ไทม์ไลน์พัฒนาการ -->
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Milestones</span>
      <h2>พัฒนาการของโรงเรียนการเรือน</h2>
      <div class="title-underline"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="history-timeline">
          <?php foreach ($timeline as $t): ?>
            <div class="timeline-item" data-aos="fade-up">
              <span class="timeline-year"><?= e($t[0]) ?></span>
              <div class="card-modern p-3 p-lg-4"><?= e($t[1]) ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</section>
