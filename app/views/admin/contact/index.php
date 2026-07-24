<?php /** กล่องข้อความติดต่อ */ ?>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>ผู้ส่ง</th><th>หัวข้อ</th><th>วันที่</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($pager['data'] as $c): ?>
          <tr class="<?= !$c['is_read'] ? 'fw-500' : '' ?>">
            <td><?= e($c['name']) ?><br><small class="text-muted"><?= e($c['email']) ?></small></td>
            <td><?= e(excerpt($c['subject'] ?: $c['message'], 50)) ?></td>
            <td class="small text-muted"><?= thai_date($c['created_at'], true) ?></td>
            <td><?= $c['is_read'] ? '<span class="badge bg-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/contact/view/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a>
              <a href="<?= url('admin/contact/delete/' . $c['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบข้อความนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?><tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อความ</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3"><?= pagination($pager, 'admin/contact') ?></div>
