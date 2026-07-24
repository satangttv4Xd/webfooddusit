<?php /** รายการข่าว (admin) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <form class="d-flex" method="get" action="<?= url('admin/news') ?>" style="max-width:340px">
    <div class="input-group input-group-sm">
      <input type="text" name="q" class="form-control" placeholder="ค้นหาข่าว..." value="<?= e($q) ?>">
      <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
  </form>
  <?php if (Auth::can('news.create')): ?>
    <a href="<?= url('admin/news/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มข่าว</a>
  <?php endif; ?>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr><th style="width:70px">รูป</th><th>หัวข้อ</th><th>หมวดหมู่</th><th>ยอดชม</th><th>สถานะ</th><th>วันที่</th><th class="text-end">จัดการ</th></tr>
      </thead>
      <tbody>
        <?php foreach ($pager['data'] as $n): ?>
          <tr class="<?= $n['deleted_at'] ? 'table-secondary opacity-75' : '' ?>">
            <td><img src="<?= upload_url($n['cover_image']) ?>" class="table-img" alt=""></td>
            <td>
              <div class="fw-500"><?= e(excerpt($n['title'], 55)) ?></div>
              <?php if ($n['is_featured']): ?><span class="badge bg-warning text-dark">แนะนำ</span><?php endif; ?>
            </td>
            <td class="small"><?= e($cats[$n['category_id']] ?? '-') ?></td>
            <td class="small"><?= number_format($n['views']) ?></td>
            <td><?= status_badge($n['status']) ?></td>
            <td class="small text-muted"><?= thai_date($n['created_at']) ?></td>
            <td class="text-end text-nowrap">
              <?php if ($n['deleted_at']): ?>
                <a href="<?= url('admin/news/restore/' . $n['id']) ?>" class="btn btn-sm btn-outline-success" data-confirm="ต้องการกู้คืนข่าวนี้?"><i class="fa-solid fa-rotate-left"></i></a>
              <?php else: ?>
                <a href="<?= url('news/detail/' . $n['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
                <?php if (Auth::can('news.edit')): ?>
                  <a href="<?= url('admin/news/edit/' . $n['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                <?php endif; ?>
                <?php if (Auth::can('news.delete')): ?>
                  <a href="<?= url('admin/news/delete/' . $n['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ต้องการลบข่าวนี้?"><i class="fa-solid fa-trash"></i></a>
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูลข่าว</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3"><?= pagination($pager, 'admin/news') ?></div>
