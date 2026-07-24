<?php
/** ตั้งค่าเว็บไซต์ */
$groupLabels = ['general' => 'ทั่วไป', 'contact' => 'ข้อมูลติดต่อ', 'social' => 'โซเชียลมีเดีย'];
?>
<form method="post" action="<?= url('admin/settings/update') ?>">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <?php foreach ($grouped as $group => $items): ?>
      <div class="col-lg-6">
        <div class="card p-4 h-100">
          <h6 class="mb-3"><i class="fa-solid fa-sliders text-primary me-2"></i><?= $groupLabels[$group] ?? $group ?></h6>
          <?php foreach ($items as $s): ?>
            <div class="mb-3">
              <label class="form-label small fw-500"><?= e($s['label'] ?: $s['key']) ?></label>
              <?php if ($s['type'] === 'textarea'): ?>
                <textarea name="settings[<?= e($s['key']) ?>]" class="form-control" rows="3"><?= e($s['value']) ?></textarea>
              <?php else: ?>
                <input type="text" name="settings[<?= e($s['key']) ?>]" class="form-control" value="<?= e($s['value']) ?>">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึกการตั้งค่า</button>
  </div>
</form>
