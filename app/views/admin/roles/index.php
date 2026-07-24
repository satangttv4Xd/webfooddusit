<?php /** รายการบทบาท (Roles) */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">บทบาทและสิทธิ์การใช้งานระบบหลังบ้าน</span>
  <a href="<?= url('admin/roles/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มบทบาท</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>บทบาท</th><th>รหัส (slug)</th><th>คำอธิบาย</th><th class="text-center">สิทธิ์</th><th class="text-center">ผู้ใช้</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($roles as $r): $isSuper = $r['slug'] === 'super_admin'; ?>
          <tr>
            <td class="fw-500"><i class="fa-solid fa-user-shield text-muted me-1"></i> <?= e($r['name']) ?></td>
            <td class="small"><code><?= e($r['slug']) ?></code></td>
            <td class="small text-muted"><?= e($r['description'] ?? '') ?></td>
            <td class="text-center">
              <?php if ($isSuper): ?>
                <span class="badge bg-dark">ทุกสิทธิ์</span>
              <?php else: ?>
                <span class="badge bg-info"><?= (int)$r['perm_count'] ?></span>
              <?php endif; ?>
            </td>
            <td class="text-center"><span class="badge bg-secondary"><?= (int)$r['user_count'] ?></span></td>
            <td><?= status_badge($r['status']) ?></td>
            <td class="text-end text-nowrap">
              <a href="<?= url('admin/roles/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <?php if (in_array($r['slug'], ['super_admin', 'admin'], true) || (int)$r['user_count'] > 0): ?>
                <button class="btn btn-sm btn-outline-secondary" disabled title="ลบไม่ได้ (บทบาทหลัก หรือมีผู้ใช้อยู่)"><i class="fa-solid fa-lock"></i></button>
              <?php else: ?>
                <a href="<?= url('admin/roles/delete/' . $r['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบบทบาทนี้?"><i class="fa-solid fa-trash"></i></a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
