<?php
/** หน้าคุณสมบัติผู้เรียน (แยกหน้า) */
$q = $qualification;
?>
<?= page_header('คุณสมบัติผู้เรียน', [
    'หลักสูตร'        => 'curriculum',
    $name            => 'curriculum/' . $slug,
    'คุณสมบัติผู้เรียน' => '',
]) ?>

<section class="section">
  <div class="container">

    <div class="mb-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>

    <h4 class="mb-1"><i class="fa-solid fa-user-check me-2" style="color:var(--gold)"></i>คุณสมบัติผู้เรียน</h4>
    <p class="text-muted mb-3"><?= e($name) ?></p>
    <hr class="divider-gold mb-4" style="max-width:120px">

    <?php if (!empty($q['pdf'])): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <a href="<?= upload_url($q['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">
          <i class="fa-solid fa-file-pdf me-1"></i> เปิดดูเอกสารคุณสมบัติผู้เรียน (PDF)
        </a>
        <a href="<?= upload_url($q['pdf']) ?>" download class="btn btn-outline-gold">
          <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($q['items'])): ?>
      <div class="card-modern p-4" data-aos="fade-up">
        <ol class="mb-0 ps-3">
          <?php foreach ($q['items'] as $item): ?>
            <?php if (is_array($item)): // [ข้อความ, [ข้อย่อย...]] ?>
              <li class="mb-3"><?= e($item[0]) ?>
                <ol class="mt-2 mb-0" type="1" style="list-style-type:none;padding-left:1rem">
                  <?php foreach ($item[1] as $si => $sub): ?>
                    <li class="mb-1">(<?= $si + 1 ?>) <?= e($sub) ?></li>
                  <?php endforeach; ?>
                </ol>
              </li>
            <?php else: ?>
              <li class="mb-3"><?= e($item) ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ol>
      </div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="<?= url('curriculum/' . $slug) ?>" class="btn btn-link ps-0">
        <i class="fa-solid fa-arrow-left me-1"></i> กลับไปหน้าหลักสูตร
      </a>
    </div>
  </div>
</section>
