<?php /** ดาวน์โหลดเอกสาร */
function human_filesize($bytes) {
    if (!$bytes) return '-';
    $u = ['B','KB','MB','GB']; $i = 0;
    while ($bytes >= 1024 && $i < 3) { $bytes /= 1024; $i++; }
    return round($bytes, 1) . ' ' . $u[$i];
}
?>
<?= page_header('ดาวน์โหลดเอกสาร', ['ดาวน์โหลด' => '']) ?>

<section class="section">
  <div class="container">
    <!-- ค้นหา/กรอง -->
    <form class="row g-2 mb-4" method="get" action="<?= url('downloads') ?>">
      <div class="col-md-7"><input type="text" name="q" class="form-control" placeholder="ค้นหาเอกสาร..." value="<?= e($keyword) ?>"></div>
      <div class="col-md-3">
        <select name="cat" class="form-select">
          <option value="">ทุกหมวดหมู่</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= e($c) ?>" <?= $category === $c ? 'selected' : '' ?>><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><button class="btn btn-primary-custom w-100">ค้นหา</button></div>
    </form>

    <div class="row g-3">
      <?php foreach ($pager['data'] as $d): ?>
        <div class="col-md-6" data-aos="fade-up">
          <div class="card-modern p-3 d-flex flex-row align-items-center gap-3 h-100">
            <div class="feature-icon mb-0 flex-shrink-0"><i class="fa-solid fa-file-<?= $d['file_type'] === 'pdf' ? 'pdf' : 'lines' ?>"></i></div>
            <div class="flex-grow-1">
              <h6 class="mb-1"><?= e($d['title']) ?></h6>
              <p class="card-meta mb-1 small"><?= excerpt($d['description'] ?? '', 70) ?></p>
              <small class="text-muted">
                <?php if ($d['category']): ?><span class="badge bg-light text-muted"><?= e($d['category']) ?></span><?php endif; ?>
                <?= strtoupper($d['file_type']) ?> · <?= human_filesize($d['file_size']) ?> ·
                <i class="fa-solid fa-download"></i> <?= number_format($d['download_count']) ?>
              </small>
            </div>
            <a href="<?= url('downloads/file/' . $d['id']) ?>" class="btn btn-gold btn-sm flex-shrink-0"><i class="fa-solid fa-download"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($pager['data'])): ?>
      <p class="text-center text-muted py-5">ไม่พบเอกสาร</p>
    <?php endif; ?>

    <div class="mt-5"><?= pagination($pager, 'downloads') ?></div>
  </div>
</section>
