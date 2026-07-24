<?php
/** ฟอร์มหลักสูตร */
$isEdit = $program !== null;
$action = $isEdit ? url('admin/programs/update/' . $program['id']) : url('admin/programs/store');
$levels = ['bachelor' => 'ปริญญาตรี', 'master' => 'ปริญญาโท', 'doctoral' => 'ปริญญาเอก', 'training' => 'หลักสูตรอบรม'];
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= Security::csrfField() ?>
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4">
        <div class="row g-3">
          <div class="col-md-8"><label class="form-label">ชื่อหลักสูตร *</label>
            <input type="text" name="name" class="form-control" data-slug-source value="<?= e($program['name'] ?? '') ?>" required></div>
          <div class="col-md-4"><label class="form-label">ระดับ</label>
            <select name="level" class="form-select">
              <?php foreach ($levels as $v => $lb): ?><option value="<?= $v ?>" <?= ($program['level'] ?? '') === $v ? 'selected' : '' ?>><?= $lb ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-md-8"><label class="form-label">ชื่อภาษาอังกฤษ</label>
            <input type="text" name="name_en" class="form-control" value="<?= e($program['name_en'] ?? '') ?>"></div>
          <div class="col-md-4"><label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" data-slug-target value="<?= e($program['slug'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ปริญญา</label>
            <input type="text" name="degree" class="form-control" value="<?= e($program['degree'] ?? '') ?>"></div>
          <div class="col-md-3"><label class="form-label">ระยะเวลา</label>
            <input type="text" name="duration" class="form-control" value="<?= e($program['duration'] ?? '') ?>"></div>
          <div class="col-md-3"><label class="form-label">หน่วยกิต</label>
            <input type="text" name="credits" class="form-control" value="<?= e($program['credits'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ค่าใช้จ่าย</label>
            <input type="text" name="tuition_fee" class="form-control" value="<?= e($program['tuition_fee'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="form-label">ลิงก์สมัครเรียน</label>
            <input type="text" name="apply_url" class="form-control" value="<?= e($program['apply_url'] ?? '') ?>"></div>
          <div class="col-12"><label class="form-label">ภาพรวมหลักสูตร</label>
            <textarea name="overview" class="form-control" rows="3"><?= e($program['overview'] ?? '') ?></textarea></div>
          <div class="col-12"><label class="form-label">คุณสมบัติผู้สมัคร</label>
            <textarea name="qualification" class="form-control" rows="3"><?= e($program['qualification'] ?? '') ?></textarea></div>
          <div class="col-12"><label class="form-label">อาชีพหลังจบ</label>
            <textarea name="careers" class="form-control" rows="3"><?= e($program['careers'] ?? '') ?></textarea></div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4">
        <div class="mb-3"><label class="form-label">สถานะ</label>
          <select name="status" class="form-select">
            <option value="active" <?= ($program['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>ใช้งาน</option>
            <option value="inactive" <?= ($program['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
          </select></div>
        <div class="mb-3"><label class="form-label">สาขา/หน่วยงาน</label>
          <select name="department_id" class="form-select">
            <option value="">-- ไม่ระบุ --</option>
            <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>" <?= ($program['department_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option><?php endforeach; ?>
          </select></div>
        <div class="mb-3"><label class="form-label">ลำดับ</label>
          <input type="number" name="sort_order" class="form-control" value="<?= e($program['sort_order'] ?? 0) ?>"></div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="is_featured" id="feat" value="1" <?= ($program['is_featured'] ?? 0) ? 'checked' : '' ?>>
          <label class="form-check-label" for="feat">หลักสูตรแนะนำ</label>
        </div>
      </div>
      <div class="card p-4 mt-3">
        <label class="form-label">รูปปก</label>
        <img src="<?= upload_url($program['cover_image'] ?? '') ?>" id="coverPreview" class="img-fluid rounded mb-2" style="max-height:160px;object-fit:cover;width:100%">
        <input type="file" name="cover_image" class="form-control mb-3" accept="image/*" data-preview="#coverPreview">
        <label class="form-label">ไฟล์ PDF รายละเอียด</label>
        <input type="file" name="pdf_file" class="form-control" accept=".pdf">
        <?php if (!empty($program['pdf_file'])): ?><small class="text-muted d-block mt-1">มีไฟล์อยู่แล้ว</small><?php endif; ?>
      </div>
      <div class="card p-3 mt-3">
        <button class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-1"></i> บันทึก</button>
        <a href="<?= url('admin/programs') ?>" class="btn btn-link w-100 mt-1">ยกเลิก</a>
      </div>
    </div>
  </div>
</form>
