<?php /** โครงสร้างการบริหารงาน */ ?>
<?= page_header('โครงสร้างการบริหารงาน', ['รอบรั้วโรงเรียนการเรือน' => '', 'โครงสร้างการบริหารงาน' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Organization</span>
      <h2>โครงสร้างการบริหารงาน</h2>
      <div class="title-underline"></div>
      <p>ผังโครงสร้างการบริหารงานและผู้รับผิดชอบหลักสูตร โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <div class="row justify-content-center" data-aos="fade-up">
      <div class="col-lg-11 text-center">
        <a href="<?= upload_url($image) ?>" target="_blank" rel="noopener" title="คลิกเพื่อดูภาพขนาดเต็ม">
          <img src="<?= upload_url($image) ?>" alt="โครงสร้างการบริหารงาน โรงเรียนการเรือน"
               class="img-fluid rounded-4 shadow-sm border" style="cursor:zoom-in">
        </a>
        <p class="text-muted small mt-3">
          <i class="fa-solid fa-magnifying-glass-plus me-1"></i> คลิกที่ภาพเพื่อดูขนาดเต็ม
        </p>
      </div>
    </div>
  </div>
</section>
