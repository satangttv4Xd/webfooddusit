<?php
/**
 * Layout สาธารณะ — ครอบทุกหน้าฝั่งผู้ใช้งาน
 * ตัวแปรที่ใช้: $content, $title, และ (ถ้ามี) $metaDescription, $ogImage
 */
$menus = (new Menu())->tree('header');
$footerMenus = (new Menu())->byPosition('footer');
$pageTitle = ($title ?? APP_NAME) . ' | ' . APP_NAME;
$metaDesc = $metaDescription ?? setting('site_description', APP_NAME);
$og = $ogImage ?? asset('images/og-default.svg');
?>
<!DOCTYPE html>
<html lang="th" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <!-- SEO -->
    <meta name="description" content="<?= e($metaDesc) ?>">
    <meta name="keywords" content="โรงเรียนการเรือน, มหาวิทยาลัยสวนดุสิต, การประกอบอาหาร, คหกรรม, หลักสูตร">
    <link rel="canonical" href="<?= e(BASE_URL . trim($_GET['url'] ?? '', '/')) ?>">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($title ?? APP_NAME) ?>">
    <meta property="og:description" content="<?= e($metaDesc) ?>">
    <meta property="og:image" content="<?= e($og) ?>">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <!-- Schema.org -->
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"CollegeOrUniversity","name":"<?= e(APP_NAME) ?>","url":"<?= e(BASE_URL) ?>"}
    </script>

    <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">

    <!-- Fonts + Bootstrap 5 + Icons + AOS + SweetAlert2 -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Loading / progress bar -->
    <div id="top-progress"></div>

    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="small">
                <i class="fa-solid fa-phone me-1"></i> <?= e(setting('site_phone', '0-2244-5000')) ?>
                <span class="mx-2">|</span>
                <i class="fa-solid fa-envelope me-1"></i> <?= e(setting('site_email', 'info@fooddusit.ac.th')) ?>
            </div>
            <div class="small d-flex align-items-center gap-3">
                <a href="<?= e(setting('facebook_url', '#')) ?>" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="<?= e(setting('youtube_url', '#')) ?>" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="<?= e(setting('line_url', '#')) ?>" target="_blank" aria-label="Line"><i class="fa-brands fa-line"></i></a>
                <button id="theme-toggle" class="btn btn-sm btn-link p-0 text-white" aria-label="สลับโหมดสี"><i class="fa-solid fa-moon"></i></button>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg main-nav sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= url('') ?>">
                <img src="<?= asset('images/sdu-logo.png') ?>" alt="โลโก้มหาวิทยาลัยสวนดุสิต" class="brand-logo-img">
                <span class="brand-text ms-2">
                    <strong>โรงเรียนการเรือน</strong>
                    <small class="d-block">มหาวิทยาลัยสวนดุสิต</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-label="เมนู">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <?php
                    if (!function_exists('render_nav_items')) {
                        function render_nav_items(array $items, bool $top = true): void
                        {
                            foreach ($items as $m) {
                                $hasChildren = !empty($m['children']);
                                if ($top) {
                                    if ($hasChildren) {
                                        echo '<li class="nav-item dropdown">';
                                        echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . e($m['title']) . '</a>';
                                        echo '<ul class="dropdown-menu">';
                                        render_nav_items($m['children'], false);
                                        echo '</ul></li>';
                                    } else {
                                        $active = (is_active($m['url']) && $m['url'] !== '') || ($m['url'] === '' && empty($_GET['url'])) ? 'active' : '';
                                        echo '<li class="nav-item"><a class="nav-link ' . $active . '" href="' . url($m['url']) . '" target="' . e($m['target']) . '">' . e($m['title']) . '</a></li>';
                                    }
                                } else {
                                    if ($hasChildren) {
                                        echo '<li class="dropdown-submenu">';
                                        echo '<a class="dropdown-item dropdown-toggle" href="#" role="button">' . e($m['title']) . '</a>';
                                        echo '<ul class="dropdown-menu">';
                                        render_nav_items($m['children'], false);
                                        echo '</ul></li>';
                                    } else {
                                        echo '<li><a class="dropdown-item" href="' . url($m['url']) . '" target="' . e($m['target']) . '">' . e($m['title']) . '</a></li>';
                                    }
                                }
                            }
                        }
                    }
                    render_nav_items($menus);
                    ?>
                    <li class="nav-item ms-lg-2">
                        <form class="d-flex" role="search" action="<?= url('search') ?>" method="get">
                            <div class="input-group input-group-sm nav-search">
                                <input class="form-control" type="search" name="q" placeholder="ค้นหา..." aria-label="ค้นหา">
                                <button class="btn btn-search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <?php if (Auth::check()): ?>
                            <a class="btn btn-gold btn-sm" href="<?= url('admin') ?>">
                                <i class="fa-solid fa-gauge me-1"></i>จัดการระบบ
                            </a>
                        <?php else: ?>
                            <a class="btn btn-gold btn-sm" href="<?= url('admin/auth/login') ?>">
                                <i class="fa-solid fa-right-to-bracket me-1"></i>เข้าสู่ระบบ
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    <?php if ($fm = flash_messages()): ?>
        <div class="container mt-3"><?= $fm ?></div>
    <?php endif; ?>

    <!-- Main content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="footer-title d-flex align-items-center gap-2">
                        <img src="<?= asset('images/sdu-logo.png') ?>" alt="โลโก้ มสด." style="height:40px;width:auto">
                        โรงเรียนการเรือน
                    </h5>
                    <p class="small text-white-50"><?= e(setting('site_description')) ?></p>
                    <div class="footer-social">
                        <a href="<?= e(setting('facebook_url', '#')) ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?= e(setting('youtube_url', '#')) ?>" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                        <a href="<?= e(setting('line_url', '#')) ?>" target="_blank"><i class="fa-brands fa-line"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="footer-title">ลิงก์ด่วน</h6>
                    <ul class="footer-links">
                        <li><a href="<?= url('about') ?>">เกี่ยวกับเรา</a></li>
                        <li><a href="<?= url('programs') ?>">หลักสูตร</a></li>
                        <li><a href="<?= url('news') ?>">ข่าวสาร</a></li>
                        <li><a href="<?= url('events') ?>">กิจกรรม</a></li>
                        <li><a href="<?= url('downloads') ?>">ดาวน์โหลด</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="footer-title">ติดต่อเรา</h6>
                    <ul class="footer-contact small">
                        <li><i class="fa-solid fa-location-dot me-2"></i><?= e(setting('site_address')) ?></li>
                        <li><i class="fa-solid fa-phone me-2"></i><?= e(setting('site_phone')) ?></li>
                        <li><i class="fa-solid fa-envelope me-2"></i><?= e(setting('site_email')) ?></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="footer-title">รับข่าวสาร</h6>
                    <form action="<?= url('newsletter/subscribe') ?>" method="post" class="newsletter-form">
                        <?= Security::csrfField() ?>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="อีเมลของคุณ" required>
                            <button class="btn btn-gold" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small">
                <span class="text-white-50"><?= e(setting('copyright', '© โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต')) ?></span>
                <a href="<?= url('page/privacy-policy') ?>" class="text-white-50">นโยบายความเป็นส่วนตัว</a>
            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <button id="back-to-top" aria-label="กลับขึ้นบน"><i class="fa-solid fa-chevron-up"></i></button>

    <!-- Cookie consent -->
    <div id="cookie-consent" class="cookie-consent">
        <span>เว็บไซต์นี้ใช้คุกกี้เพื่อประสบการณ์ที่ดีขึ้น</span>
        <button class="btn btn-sm btn-gold" id="cookie-accept">ยอมรับ</button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
