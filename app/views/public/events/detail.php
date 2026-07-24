<?php /** รายละเอียดกิจกรรม */
$isFull = $event['max_attendees'] && $registered >= $event['max_attendees'];
?>
<?= page_header($event['title'], ['กิจกรรม' => 'events', excerpt($event['title'], 40) => '']) ?>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8" data-aos="fade-up">
        <img src="<?= upload_url($event['cover_image']) ?>" class="img-fluid rounded-4 shadow mb-4 w-100" alt="<?= e($event['title']) ?>">
        <h2 style="color:var(--primary)"><?= e($event['title']) ?></h2>
        <div class="d-flex flex-wrap gap-4 my-3 text-muted">
          <span><i class="fa-regular fa-calendar me-1 text-gold"></i><?= thai_date($event['start_date'], true) ?></span>
          <span><i class="fa-solid fa-location-dot me-1 text-gold"></i><?= e($event['location']) ?></span>
        </div>
        <hr class="divider-gold mb-3">
        <div class="article-content"><?= $event['description'] ?></div>
      </div>

      <div class="col-lg-4">
        <div class="card-modern p-4 sticky-top" style="top:90px" data-aos="fade-left">
          <h5 class="mb-3">ข้อมูลกิจกรรม</h5>
          <ul class="list-unstyled">
            <li class="py-2 border-bottom"><i class="fa-regular fa-calendar me-2 text-gold"></i><?= thai_date($event['start_date']) ?></li>
            <?php if ($event['end_date']): ?><li class="py-2 border-bottom"><i class="fa-solid fa-flag-checkered me-2 text-gold"></i>ถึง <?= thai_date($event['end_date']) ?></li><?php endif; ?>
            <li class="py-2 border-bottom"><i class="fa-solid fa-location-dot me-2 text-gold"></i><?= e($event['location']) ?></li>
            <?php if ($event['max_attendees']): ?>
              <li class="py-2 border-bottom"><i class="fa-solid fa-users me-2 text-gold"></i>ลงทะเบียนแล้ว <?= $registered ?>/<?= $event['max_attendees'] ?></li>
            <?php endif; ?>
          </ul>

          <?php if ($event['allow_register']): ?>
            <?php if ($isFull): ?>
              <div class="alert alert-warning mb-0">ที่นั่งเต็มแล้ว</div>
            <?php else: ?>
              <button class="btn btn-gold w-100 mt-2" data-bs-toggle="collapse" data-bs-target="#regForm">
                <i class="fa-solid fa-pen me-1"></i> ลงทะเบียนเข้าร่วม
              </button>
              <div class="collapse mt-3" id="regForm">
                <form method="post" action="<?= url('events/register') ?>">
                  <?= Security::csrfField() ?>
                  <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
                  <input type="hidden" name="slug" value="<?= e($event['slug']) ?>">
                  <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="ชื่อ-นามสกุล *" required></div>
                  <div class="mb-2"><input type="email" name="email" class="form-control" placeholder="อีเมล *" required></div>
                  <div class="mb-2"><input type="tel" name="phone" class="form-control" placeholder="เบอร์โทร *" required></div>
                  <div class="mb-2"><input type="text" name="organization" class="form-control" placeholder="หน่วยงาน/สังกัด"></div>
                  <button class="btn btn-primary-custom w-100">ยืนยันการลงทะเบียน</button>
                </form>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
