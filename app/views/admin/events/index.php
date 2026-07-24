<?php /** รายการกิจกรรม (admin) */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">กิจกรรมทั้งหมด</span>
  <a href="<?= url('admin/events/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มกิจกรรม</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>ชื่อกิจกรรม</th><th>วันที่</th><th>สถานที่</th><th>ลงทะเบียน</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($pager['data'] as $ev): ?>
          <tr>
            <td><?= e($ev['title']) ?></td>
            <td class="small"><?= thai_date($ev['start_date']) ?></td>
            <td class="small"><?= e(excerpt($ev['location'] ?? '', 30)) ?></td>
            <td><?= $ev['allow_register'] ? '<span class="badge bg-success">เปิด</span>' : '<span class="badge bg-secondary">ปิด</span>' ?></td>
            <td><?= status_badge($ev['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/events/registrations/' . $ev['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-users"></i></a>
              <a href="<?= url('admin/events/edit/' . $ev['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/events/delete/' . $ev['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบกิจกรรมนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?><tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีกิจกรรม</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3"><?= pagination($pager, 'admin/events') ?></div>
