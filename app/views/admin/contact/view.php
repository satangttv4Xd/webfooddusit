<?php /** รายละเอียดข้อความติดต่อ */ ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h5 class="mb-1"><?= e($item['subject'] ?: 'ไม่มีหัวข้อ') ?></h5>
          <div class="text-muted small">จาก <strong><?= e($item['name']) ?></strong> &lt;<?= e($item['email']) ?>&gt;</div>
        </div>
        <span class="text-muted small"><?= thai_date($item['created_at'], true) ?></span>
      </div>
      <hr>
      <table class="table table-sm mb-3">
        <tr><td class="text-muted" style="width:120px">เบอร์โทร</td><td><?= e($item['phone'] ?: '-') ?></td></tr>
        <tr><td class="text-muted">IP Address</td><td><?= e($item['ip_address'] ?: '-') ?></td></tr>
      </table>
      <div class="p-3 bg-light rounded"><?= nl2br(e($item['message'])) ?></div>
      <div class="mt-4 d-flex gap-2">
        <a href="mailto:<?= e($item['email']) ?>" class="btn btn-primary"><i class="fa-solid fa-reply me-1"></i> ตอบกลับทางอีเมล</a>
        <a href="<?= url('admin/contact/delete/' . $item['id']) ?>" class="btn btn-outline-danger" data-confirm="ลบข้อความนี้?"><i class="fa-solid fa-trash me-1"></i> ลบ</a>
        <a href="<?= url('admin/contact') ?>" class="btn btn-link ms-auto">กลับ</a>
      </div>
    </div>
  </div>
</div>
