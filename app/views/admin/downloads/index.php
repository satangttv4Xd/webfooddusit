<?php /** รายการดาวน์โหลด (admin) */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">ไฟล์ดาวน์โหลดทั้งหมด</span>
  <a href="<?= url('admin/downloads/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มไฟล์</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>ชื่อไฟล์</th><th>หมวด</th><th>ชนิด</th><th>ดาวน์โหลด</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($pager['data'] as $d): ?>
          <tr>
            <td><?= e($d['title']) ?></td>
            <td class="small"><?= e($d['category'] ?: '-') ?></td>
            <td><span class="badge bg-secondary"><?= strtoupper($d['file_type']) ?></span></td>
            <td><?= number_format($d['download_count']) ?></td>
            <td><?= status_badge($d['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('downloads/file/' . $d['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-download"></i></a>
              <a href="<?= url('admin/downloads/edit/' . $d['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/downloads/delete/' . $d['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบไฟล์นี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?><tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีไฟล์</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3"><?= pagination($pager, 'admin/downloads') ?></div>
