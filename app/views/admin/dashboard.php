<?php /** แดชบอร์ด */ ?>
<!-- Stat cards -->
<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['news',      'ข่าวทั้งหมด',  'newspaper',      'bg-grad-1', 'admin/news'],
    ['programs',  'หลักสูตร',     'graduation-cap', 'bg-grad-2', 'admin/programs'],
    ['events',    'กิจกรรม',      'calendar-days',  'bg-grad-3', 'admin/events'],
    ['users',     'ผู้ใช้งาน',    'users',          'bg-grad-4', 'admin/users'],
    ['contact',   'ข้อความใหม่',  'envelope',       'bg-grad-5', 'admin/contact'],
    ['downloads', 'ไฟล์ดาวน์โหลด','file-arrow-down','bg-grad-6', 'admin/downloads'],
  ];
  foreach ($cards as [$key, $label, $icon, $bg, $link]): ?>
    <div class="col-6 col-lg-4 col-xl-2">
      <a href="<?= url($link) ?>" class="text-decoration-none">
        <div class="stat-card <?= $bg ?>">
          <i class="fa-solid fa-<?= $icon ?> icon"></i>
          <div class="num"><?= number_format($stats[$key]) ?></div>
          <div class="label small"><?= $label ?></div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <!-- Visitor chart -->
  <div class="col-lg-8">
    <div class="card p-3 mb-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0"><i class="fa-solid fa-chart-line text-primary me-2"></i>สถิติผู้เข้าชม 7 วันล่าสุด</h6>
        <div class="text-end small">
          <span class="badge bg-primary">วันนี้ <?= number_format($visitStats['today']) ?></span>
          <span class="badge bg-secondary">รวม <?= number_format($visitStats['total']) ?></span>
        </div>
      </div>
      <canvas id="visitChart" height="90"></canvas>
    </div>

    <!-- Recent news -->
    <div class="card p-3">
      <h6 class="mb-3"><i class="fa-solid fa-newspaper text-primary me-2"></i>ข่าวล่าสุด</h6>
      <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
          <tbody>
            <?php foreach ($recentNews as $n): ?>
              <tr>
                <td style="width:60px"><img src="<?= upload_url($n['cover_image']) ?>" class="table-img" alt=""></td>
                <td><?= e(excerpt($n['title'], 50)) ?></td>
                <td class="text-end"><?= status_badge($n['status']) ?></td>
                <td class="text-end text-muted small"><?= thai_date($n['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($recentNews)): ?><tr><td class="text-muted text-center py-3">ยังไม่มีข่าว</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent activity + contact -->
  <div class="col-lg-4">
    <div class="card p-3 mb-3">
      <h6 class="mb-3"><i class="fa-solid fa-envelope text-danger me-2"></i>ข้อความติดต่อล่าสุด</h6>
      <?php foreach ($recentContact as $c): ?>
        <a href="<?= url('admin/contact/show/' . $c['id']) ?>" class="d-block text-reset text-decoration-none border-bottom py-2">
          <div class="d-flex justify-content-between">
            <strong class="small"><?= e($c['name']) ?></strong>
            <?php if (!$c['is_read']): ?><span class="badge bg-danger">ใหม่</span><?php endif; ?>
          </div>
          <small class="text-muted"><?= e(excerpt($c['subject'] ?: $c['message'], 40)) ?></small>
        </a>
      <?php endforeach; ?>
      <?php if (empty($recentContact)): ?><p class="text-muted small mb-0">ยังไม่มีข้อความ</p><?php endif; ?>
    </div>

    <div class="card p-3">
      <h6 class="mb-3"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>กิจกรรมล่าสุดในระบบ</h6>
      <ul class="list-unstyled mb-0 small">
        <?php foreach ($recentActivity as $a): ?>
          <li class="border-bottom py-2">
            <i class="fa-solid fa-circle text-primary" style="font-size:.5rem"></i>
            <strong><?= e($a['full_name'] ?? 'ระบบ') ?></strong>
            <?= e($a['action']) ?> <span class="text-muted">(<?= e($a['module']) ?>)</span>
            <small class="d-block text-muted ms-3"><?= thai_date($a['created_at'], true) ?></small>
          </li>
        <?php endforeach; ?>
        <?php if (empty($recentActivity)): ?><li class="text-muted">ยังไม่มีกิจกรรม</li><?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  const raw = <?= json_encode($visitStats['chart'], JSON_UNESCAPED_UNICODE) ?>;
  const labels = raw.map(r => r.visit_date);
  const data = raw.map(r => parseInt(r.total, 10));
  const ctx = document.getElementById('visitChart');
  if (ctx && window.Chart) {
    new Chart(ctx, {
      type: 'line',
      data: { labels, datasets: [{
        label: 'ผู้เข้าชม', data,
        borderColor: '#0F4C81', backgroundColor: 'rgba(15,76,129,.1)',
        fill: true, tension: .4, pointBackgroundColor: '#D4AF37',
      }]},
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
  }
})();
</script>
