<?php /** รายการผู้ใช้ */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">ผู้ใช้งานระบบหลังบ้านทั้งหมด</span>
  <a href="<?= url('admin/users/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มผู้ใช้</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>ชื่อ</th><th>ชื่อผู้ใช้</th><th>อีเมล</th><th>บทบาท</th><th>สถานะ</th><th>เข้าใช้ล่าสุด</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td>
              <span class="avatar-circle me-2" style="width:32px;height:32px;font-size:.85rem"><?= mb_substr($u['full_name'], 0, 1) ?></span>
              <?= e($u['full_name']) ?>
            </td>
            <td class="small"><?= e($u['username']) ?></td>
            <td class="small"><?= e($u['email']) ?></td>
            <td><span class="badge bg-primary"><?= e($u['role_name']) ?></span></td>
            <td><?= status_badge($u['status']) ?></td>
            <td class="small text-muted"><?= $u['last_login_at'] ? thai_date($u['last_login_at'], true) : 'ยังไม่เคย' ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/users/delete/' . $u['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบผู้ใช้นี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
