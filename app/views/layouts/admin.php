<?php
/**
 * Layout หลังบ้าน (Admin) — sidebar + topbar
 * ตัวแปร: $content, $title
 */
$user = Auth::user();
$unread = 0;
try { $unread = (new Contact())->unreadCount(); } catch (Throwable $e) {}

/** ตรวจว่าเมนูใด active */
$cur = trim($_GET['url'] ?? '', '/');
function menu_active(string $seg): string {
    global $cur;
    return str_starts_with($cur, 'admin/' . $seg) ? 'active' : '';
}

// รายการเมนู sidebar: [path, ไอคอน, ชื่อ, permission (null=ทุกคน)]
$navItems = [
    ['',           'gauge-high',      'แดชบอร์ด', null],
    ['news',       'newspaper',       'จัดการข่าว', 'news.view'],
    ['programs',   'graduation-cap',  'หลักสูตร', 'programs.manage'],
    ['staff',      'chalkboard-user', 'บุคลากร/อาจารย์', 'staff.manage'],
    ['deans',      'user-tie',        'ทำเนียบคณบดี', 'staff.manage'],
    ['committee',  'people-group',    'คณะกรรมการประจำโรงเรียน', 'committee.manage'],
    ['alumni',     'user-graduate',   'ศิษย์เก่า', 'staff.manage'],
    ['events',     'calendar-days',   'กิจกรรม', 'events.manage'],
    ['banner',     'images',          'แบนเนอร์', 'banner.manage'],
    ['gallery',    'photo-film',      'แกลเลอรี', 'gallery.manage'],
    ['downloads',  'file-arrow-down', 'ดาวน์โหลด', 'downloads.manage'],
    ['documents',  'file-contract',   'เอกสาร/รายงาน', 'documents.manage'],
    ['pages',      'file-lines',      'หน้าเนื้อหา', 'pages.manage'],
    ['faq',        'circle-question', 'คำถามพบบ่อย', 'pages.manage'],
    ['contact',    'envelope',        'ข้อความติดต่อ', 'contact.view'],
    ['users',      'users',           'ผู้ใช้งาน', 'users.manage'],
    ['roles',      'user-shield',     'บทบาท/สิทธิ์', 'roles.manage'],
    ['settings',   'gear',            'ตั้งค่าเว็บไซต์', 'settings.manage'],
];
?>
<!DOCTYPE html>
<html lang="th" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ผู้ดูแลระบบ') ?> | ระบบจัดการเว็บไซต์</title>
    <link rel="icon" type="image/png" href="<?= asset('images/sdu-logo.png') ?>">
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= asset('css/admin.css') ?>" rel="stylesheet">
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="<?= asset('images/sdu-logo.png') ?>" alt="โลโก้ มสด." style="height:38px;width:auto">
            <div class="brand-text ms-2">
                <strong>โรงเรียนการเรือน</strong>
                <small>ระบบจัดการเว็บไซต์</small>
            </div>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($navItems as [$path, $icon, $label, $perm]): ?>
                <?php if ($perm !== null && !Auth::can($perm)) continue; ?>
                <a href="<?= url('admin/' . $path) ?>" class="nav-link <?= menu_active($path) ?: ($path === '' && $cur === 'admin' ? 'active' : '') ?>">
                    <i class="fa-solid fa-<?= $icon ?>"></i> <span><?= $label ?></span>
                    <?php if ($path === 'contact' && $unread > 0): ?>
                        <span class="badge bg-danger ms-auto"><?= $unread ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= url('') ?>" target="_blank" class="nav-link"><i class="fa-solid fa-globe"></i> <span>ดูเว็บไซต์</span></a>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <button class="btn btn-sm btn-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
            <h5 class="topbar-title mb-0"><?= e($title ?? '') ?></h5>
            <div class="ms-auto d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-link text-reset p-0" id="adminThemeToggle" title="สลับโหมด"><i class="fa-solid fa-moon"></i></button>
                <div class="dropdown">
                    <a class="d-flex align-items-center text-reset text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="avatar-circle"><?= mb_substr($user['name'] ?? 'A', 0, 1) ?></span>
                        <span class="d-none d-md-inline ms-2">
                            <span class="fw-500"><?= e($user['name']) ?></span>
                            <small class="d-block text-muted"><?= e($user['role_name']) ?></small>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="<?= url('admin/users/profile') ?>"><i class="fa-solid fa-user me-2"></i>โปรไฟล์</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= url('admin/auth/logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i>ออกจากระบบ</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="admin-content">
            <?php if ($fm = flash_messages()): ?><?= $fm ?><?php endif; ?>
            <?= $content ?>
        </main>

        <footer class="admin-footer">
            <small class="text-muted"><?= e(setting('copyright', 'โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต')) ?></small>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
