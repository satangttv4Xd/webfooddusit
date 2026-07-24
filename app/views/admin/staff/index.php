<?php /** รายการบุคลากร/อาจารย์ */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">บุคลากร / อาจารย์ ที่แสดงบนเว็บไซต์</span>
  <a href="<?= url('admin/staff/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มบุคลากร</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th style="width:70px">รูป</th><th>ชื่อ-นามสกุล</th><th>ตำแหน่ง</th><th>ประเภท</th><th>ลำดับ</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($staff as $s): ?>
          <tr>
            <td><img src="<?= upload_url($s['photo']) ?>" class="rounded-circle" width="44" height="44" style="object-fit:cover" alt=""></td>
            <td class="fw-500"><?= e($s['full_name']) ?></td>
            <td class="small"><?= e($s['position'] ?: '-') ?></td>
            <td><span class="badge bg-secondary"><?= e(Staff::typeLabel($s['type'])) ?></span></td>
            <td><?= (int)$s['sort_order'] ?></td>
            <td><?= status_badge($s['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/staff/edit/' . $s['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/staff/delete/' . $s['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบบุคลากรคนนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($staff)): ?><tr><td colspan="7" class="text-center text-muted py-4">ยังไม่มีข้อมูลบุคลากร</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
