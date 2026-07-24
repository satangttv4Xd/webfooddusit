<?php /** รายการหน้าเนื้อหา (admin) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <form class="d-flex" method="get" action="<?= url('admin/pages') ?>" style="max-width:340px">
    <div class="input-group input-group-sm">
      <input type="text" name="q" class="form-control" placeholder="ค้นหาหน้า..." value="<?= e($q) ?>">
      <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
  </form>
  <a href="<?= url('admin/pages/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มหน้า</a>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr><th>ชื่อหน้า</th><th>Slug</th><th>เทมเพลต</th><th>สถานะ</th><th>อัปเดตล่าสุด</th><th class="text-end">จัดการ</th></tr>
      </thead>
      <tbody>
        <?php foreach ($pager['data'] as $p): ?>
          <tr>
            <td class="fw-500"><?= e(excerpt($p['title'], 60)) ?></td>
            <td class="small text-muted"><code><?= e($p['slug']) ?></code></td>
            <td class="small"><?= e($p['template'] ?: 'default') ?></td>
            <td><?= status_badge($p['status']) ?></td>
            <td class="small text-muted"><?= thai_date($p['updated_at']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('page/' . $p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
              <a href="<?= url('admin/pages/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/pages/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ต้องการลบหน้านี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีหน้าเนื้อหา</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3"><?= pagination($pager, 'admin/pages') ?></div>
