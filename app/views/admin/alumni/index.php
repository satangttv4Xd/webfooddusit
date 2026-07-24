<?php /** รายการศิษย์เก่า */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">ศิษย์เก่า ที่แสดงบนเว็บไซต์</span>
  <a href="<?= url('admin/alumni/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มศิษย์เก่า</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th style="width:70px">รูป</th><th>ชื่อ-นามสกุล</th><th>อาชีพปัจจุบัน</th><th>รุ่นปี</th><th>ดีเด่น</th><th>ลำดับ</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($alumni as $a): ?>
          <tr>
            <td><img src="<?= upload_url($a['photo']) ?>" class="rounded-circle" width="44" height="44" style="object-fit:cover" alt=""></td>
            <td class="fw-500"><?= e($a['full_name']) ?></td>
            <td class="small"><?= e($a['current_job'] ?: '-') ?></td>
            <td class="small"><?= e($a['graduate_year'] ?: '-') ?></td>
            <td><?= !empty($a['is_featured']) ? '<span class="badge bg-warning text-dark">ดีเด่น</span>' : '<span class="text-muted small">-</span>' ?></td>
            <td><?= (int)$a['sort_order'] ?></td>
            <td><?= status_badge($a['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/alumni/edit/' . $a['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/alumni/delete/' . $a['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบศิษย์เก่าคนนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($alumni)): ?><tr><td colspan="8" class="text-center text-muted py-4">ยังไม่มีข้อมูลศิษย์เก่า</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
