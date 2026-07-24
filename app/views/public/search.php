<?php /** ผลการค้นหา */ ?>
<?= page_header('ผลการค้นหา', ['ค้นหา' => '']) ?>

<section class="section">
  <div class="container">
    <form class="mb-4" method="get" action="<?= url('search') ?>">
      <div class="input-group input-group-lg">
        <input type="text" name="q" class="form-control" placeholder="ค้นหา..." value="<?= e($keyword) ?>">
        <button class="btn btn-primary-custom"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </form>

    <?php if ($keyword === ''): ?>
      <p class="text-muted">กรุณาพิมพ์คำค้นหา</p>
    <?php else: ?>
      <p class="text-muted mb-4">พบผลลัพธ์ <strong><?= $total ?></strong> รายการสำหรับ "<strong><?= e($keyword) ?></strong>"</p>

      <?php
      $sections = [
        'news'      => ['ข่าวสาร', 'newspaper'],
        'programs'  => ['หลักสูตร', 'graduation-cap'],
        'staff'     => ['บุคลากร/อาจารย์', 'chalkboard-user'],
        'deans'     => ['ทำเนียบคณบดี', 'user-tie'],
        'downloads' => ['เอกสารดาวน์โหลด', 'file'],
        'faq'       => ['คำถามที่พบบ่อย', 'circle-question'],
        'pages'     => ['หน้าเนื้อหา', 'file-lines'],
      ];
      foreach ($sections as $key => [$label, $icon]):
        if (empty($results[$key])) continue; ?>
        <div class="mb-4">
          <h5 class="mb-3"><i class="fa-solid fa-<?= $icon ?> text-gold me-2"></i><?= $label ?> (<?= count($results[$key]) ?>)</h5>
          <div class="list-group">
            <?php foreach ($results[$key] as $r): ?>
              <?php
              $link = match ($key) {
                'news'      => url('news/detail/' . $r['slug']),
                'programs'  => url('programs/detail/' . $r['slug']),
                'staff'     => url('about'),
                'deans'     => url('deans'),
                'downloads' => url('downloads'),
                'pages'     => url('page/' . $r['slug']),
                default     => '#',
              };
              $t = $r['title'] ?? $r['name'] ?? $r['full_name'] ?? $r['question'] ?? '';
              ?>
              <a href="<?= $link ?>" class="list-group-item list-group-item-action">
                <strong><?= e($t) ?></strong>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if ($total === 0): ?>
        <div class="text-center py-5 text-muted">
          <i class="fa-solid fa-magnifying-glass fa-3x mb-3"></i><p>ไม่พบผลลัพธ์</p>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
