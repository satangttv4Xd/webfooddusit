<?php
/** Modal รายละเอียดบุคคล (ใช้ร่วมกับปุ่ม .person-card ที่มี data-name/position/photo/has-photo/bio) */
?>
<div class="modal fade" id="personModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="personModalName"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-4 text-center">
            <img id="personModalPhoto" src="" class="rounded-circle mb-2 d-none" width="140" height="140" style="object-fit:cover" alt="">
            <div id="personModalIcon" class="feature-icon mx-auto mb-2 d-none" style="width:140px;height:140px;font-size:3rem">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="card-cat" id="personModalPosition"></div>
          </div>
          <div class="col-md-8">
            <h6 class="text-muted mb-2">ประวัติ / ผลงาน</h6>
            <div id="personModalBio" class="article-content" style="white-space:pre-line"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('personModal');
  if (!modal) return;
  modal.addEventListener('show.bs.modal', function (ev) {
    var btn = ev.relatedTarget;
    document.getElementById('personModalName').textContent = btn.dataset.name || '';
    document.getElementById('personModalPosition').textContent = btn.dataset.position || '';
    document.getElementById('personModalBio').textContent = btn.dataset.bio || 'ยังไม่มีข้อมูลประวัติ/ผลงาน';
    var photo = document.getElementById('personModalPhoto');
    var icon = document.getElementById('personModalIcon');
    if (btn.dataset.hasPhoto === '1') {
      photo.src = btn.dataset.photo || '';
      photo.classList.remove('d-none');
      icon.classList.add('d-none');
    } else {
      photo.classList.add('d-none');
      icon.classList.remove('d-none');
    }
  });
});
</script>
