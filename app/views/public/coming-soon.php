<?php /** หน้าโครงสร้าง (ยังไม่มีเนื้อหา) */ ?>
<?= page_header($pageName, ['รอบรั้วโรงเรียนการเรือน' => '', $pageName => '']) ?>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center" data-aos="fade-up">
        <div class="feature-icon mx-auto mb-3" style="width:80px;height:80px;font-size:2rem">
          <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>
        <h2 style="color:var(--primary)"><?= e($pageName) ?></h2>
        <div class="title-underline mx-auto"></div>
        <p class="text-muted mt-3">เนื้อหาส่วนนี้อยู่ระหว่างการจัดทำ จะเปิดให้บริการเร็ว ๆ นี้</p>
        <a href="<?= url('') ?>" class="btn btn-primary-custom mt-2"><i class="fa-solid fa-house me-1"></i> กลับหน้าแรก</a>
      </div>
    </div>
  </div>
</section>
