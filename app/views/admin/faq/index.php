<?php /** รายการ FAQ */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">คำถามที่พบบ่อย</span>
  <a href="<?= url('admin/faq/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มคำถาม</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>คำถาม</th><th>หมวด</th><th>ลำดับ</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($faqs as $f): ?>
          <tr>
            <td><?= e(excerpt($f['question'], 70)) ?></td>
            <td class="small"><?= e($f['category'] ?: '-') ?></td>
            <td><?= $f['sort_order'] ?></td>
            <td><?= status_badge($f['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/faq/edit/' . $f['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/faq/delete/' . $f['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบคำถามนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($faqs)): ?><tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีคำถาม</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
