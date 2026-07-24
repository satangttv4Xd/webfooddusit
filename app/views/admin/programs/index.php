<?php /** รายการหลักสูตร (admin) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <form class="d-flex" method="get" action="<?= url('admin/programs') ?>" style="max-width:340px">
    <div class="input-group input-group-sm">
      <input type="text" name="q" class="form-control" placeholder="ค้นหาหลักสูตร..." value="<?= e($q) ?>">
      <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
  </form>
  <a href="<?= url('admin/programs/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มหลักสูตร</a>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th style="width:70px">รูป</th><th>ชื่อหลักสูตร</th><th>ระดับ</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($pager['data'] as $p): ?>
          <tr class="<?= $p['deleted_at'] ? 'table-secondary opacity-75' : '' ?>">
            <td><img src="<?= upload_url($p['cover_image']) ?>" class="table-img" alt=""></td>
            <td>
              <div class="fw-500"><?= e($p['name']) ?></div>
              <?php if ($p['is_featured']): ?><span class="badge bg-warning text-dark">แนะนำ</span><?php endif; ?>
            </td>
            <td class="small"><?= e(\Program::levelLabel($p['level'])) ?></td>
            <td><?= status_badge($p['status']) ?></td>
            <td class="text-end text-nowrap">
              <?php if ($p['deleted_at']): ?>
                <a href="<?= url('admin/programs/restore/' . $p['id']) ?>" class="btn btn-sm btn-outline-success" data-confirm="กู้คืนหลักสูตรนี้?"><i class="fa-solid fa-rotate-left"></i></a>
              <?php else: ?>
                <a href="<?= url('admin/programs/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                <a href="<?= url('admin/programs/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบหลักสูตรนี้?"><i class="fa-solid fa-trash"></i></a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?><tr><td colspan="5" class="text-center text-muted py-4">ไม่พบหลักสูตร</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3"><?= pagination($pager, 'admin/programs') ?></div>
