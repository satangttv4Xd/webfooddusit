<?php /** รายการเอกสาร/รายงาน */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-muted small">เอกสาร/รายงานที่แสดงบนเว็บไซต์ เช่น หน้า "รายงานผลการดำเนินงาน"</span>
  <a href="<?= url('admin/documents/create') ?>" class="btn btn-gold btn-sm"><i class="fa-solid fa-plus me-1"></i> เพิ่มเอกสาร</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th style="width:60px">ปก</th><th>ชื่อเอกสาร</th><th>เลขที่</th><th>หมวดหมู่</th><th>วันที่</th><th>สถานะ</th><th class="text-end">จัดการ</th></tr></thead>
      <tbody>
        <?php foreach ($items as $d): ?>
          <tr>
            <td><img src="<?= upload_url($d['cover_image']) ?>" width="40" height="53" style="object-fit:cover;border-radius:4px" alt=""></td>
            <td class="fw-500"><?= e($d['title']) ?></td>
            <td class="small"><?= e($d['doc_number'] ?: '-') ?></td>
            <td class="small"><?= e($d['category'] ?: '-') ?></td>
            <td class="small"><?= $d['doc_date'] ? thai_date($d['doc_date']) : '-' ?></td>
            <td><?= status_badge($d['status']) ?></td>
            <td class="text-end text-nowrap">
              <?php if ($d['file_path']): ?>
                <a href="<?= upload_url($d['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
              <?php endif; ?>
              <a href="<?= url('admin/documents/edit/' . $d['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <a href="<?= url('admin/documents/delete/' . $d['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="ลบเอกสารนี้?"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?><tr><td colspan="7" class="text-center text-muted py-4">ยังไม่มีข้อมูลเอกสาร</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
