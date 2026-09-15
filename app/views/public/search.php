<?php
/** ผลการค้นหา */
$tokens = $tokens ?? [];

/** ไฮไลต์คำค้นในข้อความ (escape ก่อนเสมอ) */
if (!function_exists('search_highlight')) {
    function search_highlight(string $text, array $tokens): string
    {
        $safe = e($text);
        foreach ($tokens as $tk) {
            $q = preg_quote(e($tk), '/');
            $safe = preg_replace('/(' . $q . ')/iu', '<mark>$1</mark>', $safe) ?? $safe;
        }
        return $safe;
    }
}

$sections = [
    'curriculum' => ['หลักสูตร/สาขาวิชา', 'graduation-cap'],
    'news'       => ['ข่าวสาร', 'newspaper'],
    'programs'   => ['หลักสูตร (ฐานข้อมูล)', 'book'],
    'events'     => ['กิจกรรม', 'calendar-days'],
    'staff'      => ['บุคลากร/อาจารย์', 'chalkboard-user'],
    'deans'      => ['ทำเนียบคณบดี', 'user-tie'],
    'committee'  => ['คณะกรรมการประจำโรงเรียนการเรือน', 'people-group'],
    'documents'  => ['เอกสาร/รายงาน', 'file-contract'],
    'alumni'     => ['ศิษย์เก่า', 'user-graduate'],
    'downloads'  => ['เอกสารดาวน์โหลด', 'file-arrow-down'],
    'faq'        => ['คำถามที่พบบ่อย', 'circle-question'],
    'pages'      => ['หน้าเนื้อหา', 'file-lines'],
    'menu'       => ['หน้าอื่น ๆ ในเว็บไซต์', 'compass'],
];
?>
<?= page_header('ผลการค้นหา', ['ค้นหา' => '']) ?>

<section class="section">
  <div class="container">
    <form class="mb-4" method="get" action="<?= url('search') ?>">
      <div class="input-group input-group-lg">
        <input type="text" name="q" class="form-control" placeholder="ค้นหาหลักสูตร ข่าว กิจกรรม เอกสาร..." value="<?= e($keyword) ?>" autofocus>
        <button class="btn btn-primary-custom"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <div class="form-text">พิมพ์ได้หลายคำ เช่น "คหกรรม ทุนการศึกษา" (ระบบจะค้นหาทุกคำ)</div>
    </form>

    <?php if ($keyword === ''): ?>
      <p class="text-muted">กรุณาพิมพ์คำค้นหา</p>

    <?php elseif (empty($tokens)): ?>
      <div class="alert alert-warning"><i class="fa-solid fa-circle-info me-1"></i> กรุณาพิมพ์คำค้นหาอย่างน้อย 2 ตัวอักษร</div>

    <?php else: ?>
      <p class="text-muted mb-4">พบผลลัพธ์ <strong><?= $total ?></strong> รายการสำหรับ "<strong><?= e($keyword) ?></strong>"</p>

      <?php foreach ($sections as $key => [$label, $icon]): ?>
        <?php if (empty($groups[$key])) continue; ?>
        <div class="mb-4" data-aos="fade-up">
          <h5 class="mb-3"><i class="fa-solid fa-<?= $icon ?> text-gold me-2"></i><?= $label ?> (<?= count($groups[$key]) ?>)</h5>
          <div class="list-group">
            <?php foreach ($groups[$key] as $r): ?>
              <a href="<?= url($r['url']) ?>" class="list-group-item list-group-item-action py-3">
                <div class="fw-semibold" style="color:var(--primary)"><?= search_highlight($r['title'], $tokens) ?></div>
                <?php if (!empty($r['excerpt'])): ?>
                  <div class="small text-muted mt-1"><?= search_highlight($r['excerpt'], $tokens) ?></div>
                <?php endif; ?>
                <div class="small text-success mt-1"><i class="fa-solid fa-link me-1"></i><?= e(url($r['url'])) ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if ($total === 0): ?>
        <div class="text-center py-5 text-muted">
          <i class="fa-solid fa-magnifying-glass fa-3x mb-3"></i>
          <p class="mb-1">ไม่พบผลลัพธ์สำหรับ "<strong><?= e($keyword) ?></strong>"</p>
          <p class="small">ลองใช้คำค้นที่สั้นลง หรือใช้คำอื่นที่ใกล้เคียง</p>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
