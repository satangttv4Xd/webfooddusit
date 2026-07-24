<?php /** รายชื่อผู้ลงทะเบียนกิจกรรม (admin) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <div>
    <a href="<?= url('admin/events') ?>" class="btn btn-sm btn-link px-0"><i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้ากิจกรรม</a>
    <div class="small text-muted">กิจกรรม: <strong><?= e($event['title']) ?></strong> &middot; <?= count($regs) ?> คน</div>
  </div>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr><th>#</th><th>ชื่อ-สกุล</th><th>อีเมล</th><th>โทรศัพท์</th><th>หน่วยงาน</th><th>สถานะ</th><th>วันที่ลงทะเบียน</th></tr>
      </thead>
      <tbody>
        <?php foreach ($regs as $i => $r): ?>
          <tr>
            <td class="text-muted"><?= $i + 1 ?></td>
            <td class="fw-500"><?= e($r['name']) ?></td>
            <td class="small"><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></td>
            <td class="small"><?= e($r['phone'] ?: '-') ?></td>
            <td class="small"><?= e($r['organization'] ?: '-') ?></td>
            <td><?= status_badge($r['status']) ?></td>
            <td class="small text-muted"><?= thai_date($r['created_at'], true) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($regs)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">ยังไม่มีผู้ลงทะเบียน</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
