<?php /** รายการคณะกรรมการประจำโรงเรียนการเรือน */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">คณะกรรมการที่แสดงบนเว็บไซต์ (หน้า "คณะกรรมการประจำโรงเรียนการเรือน")</span>
  <a href="<?= url('admin/committee/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มกรรมการ</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th style="width:70px">รูป</th><th>ชื่อ-นามสกุล</th><th>ตำแหน่ง</th><th>กลุ่ม</th><th>ลำดับ</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php $curGroup = null; foreach ($items as $d): ?>
          <?php if ($d['group_name'] !== $curGroup): $curGroup = $d['group_name']; ?>
            <tr class="table-light"><td colspan="7" class="fw-semibold small" style="color:var(--primary)"><?= e($curGroup) ?></td></tr>
          <?php endif; ?>
          <tr>
            <td><img src="<?= upload_url($d['photo']) ?>" class="rounded-circle" width="44" height="44" style="object-fit:cover" alt=""></td>
            <td class="fw-500"><?= e($d['full_name']) ?></td>
            <td class="small"><?= e($d['position']) ?></td>
            <td class="small"><?= e($d['group_name']) ?></td>
            <td><?= (int)$d['sort_order'] ?></td>
            <td><?= status_badge($d['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/committee/edit/' . $d['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/committee/delete/' . $d['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบข้อมูลกรรมการคนนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?><tr><td colspan="7" class="text-center text-muted py-4">ยังไม่มีข้อมูลคณะกรรมการ</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
