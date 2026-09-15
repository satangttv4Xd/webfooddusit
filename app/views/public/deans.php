<?php /** ทำเนียบคณบดี */ ?>
<?= page_header('ทำเนียบคณบดี', ['รอบรั้วโรงเรียนการเรือน' => '', 'ทำเนียบคณบดี' => '']) ?>

<section class="section">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <span class="eyebrow">Our Deans</span>
      <h2>ทำเนียบคณบดี</h2>
      <div class="title-underline"></div>
      <p>รายนามคณบดีโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต</p>
    </div>

    <?php if (empty($deans)): ?>
      <div class="alert alert-warning text-center">ยังไม่มีข้อมูลคณบดี</div>
    <?php else: ?>
      <div class="row g-4 justify-content-center">
        <?php foreach ($deans as $d): ?>
          <div class="col-sm-6 col-lg-4" data-aos="fade-up">
            <button type="button" class="card-modern text-center p-4 h-100 w-100 border-0 dean-card"
                    data-bs-toggle="modal" data-bs-target="#deanModal"
                    data-name="<?= e($d['full_name']) ?>"
                    data-position="<?= e($d['position']) ?>"
                    data-term="<?= e($d['term'] ?? '') ?>"
                    data-photo="<?= e(upload_url($d['photo'])) ?>"
                    data-bio="<?= e($d['bio'] ?? '') ?>">
              <img src="<?= upload_url($d['photo']) ?>" class="rounded-circle mb-3 mx-auto" width="150" height="150"
                   style="object-fit:cover" alt="<?= e($d['full_name']) ?>">
              <h5 class="mb-1"><?= e($d['full_name']) ?></h5>
              <div class="card-cat mb-2"><?= e($d['position']) ?></div>
              <?php if (!empty($d['term'])): ?>
                <p class="card-meta mb-0"><i class="fa-regular fa-calendar me-1"></i><?= e($d['term']) ?></p>
              <?php endif; ?>
              <?php if (!empty($d['bio'])): ?>
                <p class="card-meta mt-2 mb-0 text-primary small"><i class="fa-regular fa-eye me-1"></i>ดูรายละเอียด</p>
              <?php endif; ?>
            </button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Modal รายละเอียดคณบดี -->
<div class="modal fade" id="deanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deanModalName"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-4 text-center">
            <img id="deanModalPhoto" src="" class="rounded-circle mb-2" width="140" height="140" style="object-fit:cover" alt="">
            <div class="card-cat" id="deanModalPosition"></div>
            <p class="card-meta mb-0 mt-2" id="deanModalTerm"></p>
          </div>
          <div class="col-md-8">
            <h6 class="text-muted mb-2">ประวัติ / ผลงาน</h6>
            <div id="deanModalBio" class="article-content" style="white-space:pre-line"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('deanModal');
  if (!modal) return;
  modal.addEventListener('show.bs.modal', function (ev) {
    var btn = ev.relatedTarget;
    document.getElementById('deanModalName').textContent = btn.dataset.name || '';
    document.getElementById('deanModalPosition').textContent = btn.dataset.position || '';
    document.getElementById('deanModalTerm').innerHTML = btn.dataset.term
      ? '<i class="fa-regular fa-calendar me-1"></i>' + btn.dataset.term : '';
    document.getElementById('deanModalPhoto').src = btn.dataset.photo || '';
    document.getElementById('deanModalBio').textContent = btn.dataset.bio || 'ยังไม่มีข้อมูลประวัติ/ผลงาน';
  });
});
</script>
