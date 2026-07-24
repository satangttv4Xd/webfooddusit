<?php
/**
 * ============================================================
 *  Global Helper Functions
 * ------------------------------------------------------------
 *  ฟังก์ชันสั้น ๆ ที่เรียกใช้บ่อยทั่วทั้งเว็บ (view/controller)
 * ============================================================
 */

/**
 * สร้าง URL เต็มจาก path ภายในเว็บ
 */
function url(string $path = ''): string
{
    // ลิงก์ภายนอก/พิเศษ (http, //, mailto:, tel:, #) ให้คืนค่าเดิมไม่เติม BASE_URL
    if ($path !== '' && preg_match('~^(https?://|//|mailto:|tel:|#)~i', $path)) {
        return $path;
    }
    return BASE_URL . ltrim($path, '/');
}

/**
 * สร้าง URL ของไฟล์ asset (css/js/images)
 */
function asset(string $path): string
{
    $rel = ltrim($path, '/');
    $url = BASE_URL . 'public/assets/' . $rel;
    // แนบเวอร์ชันจากเวลาแก้ไขไฟล์ (cache-busting) กันเบราว์เซอร์ใช้ CSS/JS เก่าค้าง
    $file = PUBLIC_PATH . '/assets/' . $rel;
    if (is_file($file)) {
        $url .= '?v=' . filemtime($file);
    }
    return $url;
}

/**
 * สร้าง URL ของไฟล์ที่อัปโหลด
 */
function upload_url(?string $path): string
{
    if (empty($path)) {
        return asset('images/placeholder.svg');
    }
    if (str_starts_with($path, 'http')) {
        return $path;
    }
    return BASE_URL . 'public/uploads/' . ltrim($path, '/');
}

/**
 * แสดงข้อความปลอดภัย (escape กัน XSS) — ใช้ใน view
 */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * ตัดข้อความให้สั้นลง พร้อมจุดไข่ปลา
 */
function excerpt(string $text, int $limit = 120): string
{
    $text = trim(strip_tags($text));
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit) . '…';
}

/**
 * แปลงวันที่เป็นรูปแบบไทย เช่น 15 ก.ค. 2569
 */
function thai_date(?string $datetime, bool $withTime = false): string
{
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
        return '-';
    }
    $months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
               'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $ts = strtotime($datetime);
    $day = date('j', $ts);
    $month = $months[(int)date('n', $ts)];
    $year = (int)date('Y', $ts) + 543; // พ.ศ.
    $out = "{$day} {$month} {$year}";
    if ($withTime) {
        $out .= ' ' . date('H:i', $ts) . ' น.';
    }
    return $out;
}

/**
 * คืนชื่อเดือนไทยแบบย่อจากวันที่ เช่น 'ก.ค.'
 */
function thai_month(?string $datetime): string
{
    if (empty($datetime)) return '';
    $months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
               'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    return $months[(int)date('n', strtotime($datetime))] ?? '';
}

/**
 * ตรวจว่า path ปัจจุบันตรงกับที่ระบุหรือไม่ (ใช้ทำเมนู active)
 */
function is_active(string $segment): bool
{
    $current = trim($_GET['url'] ?? '', '/');
    return str_starts_with($current, trim($segment, '/'));
}

/**
 * แสดงส่วนหัวของหน้า (Page Header) พร้อม breadcrumb
 * @param string $title หัวข้อหน้า
 * @param array  $crumbs รายการ breadcrumb ['ชื่อ' => 'path', ...] path ว่าง = หน้าปัจจุบัน
 */
function page_header(string $title, array $crumbs = []): string
{
    $html = '<section class="page-header"><div class="container">';
    $html .= '<h1 data-aos="fade-up">' . e($title) . '</h1>';
    $html .= '<nav aria-label="breadcrumb"><ol class="breadcrumb breadcrumb-custom mt-2">';
    $html .= '<li class="breadcrumb-item"><a href="' . url('') . '">หน้าแรก</a></li>';
    $count = count($crumbs);
    $i = 0;
    foreach ($crumbs as $label => $path) {
        $i++;
        if ($path === '' || $i === $count) {
            $html .= '<li class="breadcrumb-item active">' . e($label) . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . url($path) . '">' . e($label) . '</a></li>';
        }
    }
    $html .= '</ol></nav></div></section>';
    return $html;
}

/**
 * แสดงและล้าง Flash message
 */
function flash_messages(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $html = '';
    $map = ['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'];
    foreach ($_SESSION['flash'] as $type => $msg) {
        $cls = $map[$type] ?? 'info';
        $html .= '<div class="alert alert-' . $cls . ' alert-dismissible fade show" role="alert">'
               . e($msg)
               . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    unset($_SESSION['flash']);
    return $html;
}

/**
 * สร้าง query string โดยคงค่าปัจจุบันไว้ (ใช้กับ pagination + filter)
 */
function build_query(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);
    unset($params['url']);
    return http_build_query($params);
}

/**
 * แสดงตัวแบ่งหน้า (Pagination) แบบ Bootstrap
 * @param array  $pager ผลลัพธ์จาก Model::paginate()
 * @param string $baseUrl URL ฐาน เช่น 'news'
 */
function pagination(array $pager, string $baseUrl): string
{
    if (($pager['total_pages'] ?? 1) <= 1) {
        return '';
    }
    $current = $pager['page'];
    $total = $pager['total_pages'];
    $html = '<nav><ul class="pagination justify-content-center">';

    $prevDisabled = $current <= 1 ? ' disabled' : '';
    $html .= '<li class="page-item' . $prevDisabled . '"><a class="page-link" href="'
           . url($baseUrl . '?' . build_query(['page' => $current - 1])) . '">&laquo;</a></li>';

    $start = max(1, $current - 2);
    $end = min($total, $current + 2);
    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $current ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="'
               . url($baseUrl . '?' . build_query(['page' => $i])) . '">' . $i . '</a></li>';
    }

    $nextDisabled = $current >= $total ? ' disabled' : '';
    $html .= '<li class="page-item' . $nextDisabled . '"><a class="page-link" href="'
           . url($baseUrl . '?' . build_query(['page' => $current + 1])) . '">&raquo;</a></li>';

    $html .= '</ul></nav>';
    return $html;
}

/**
 * ดึงค่า setting จากฐานข้อมูล (แคชในหน่วยความจำระหว่าง request)
 */
function setting(string $key, $default = '')
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            $rows = (new Database())->query("SELECT `key`, `value` FROM settings")->all();
            foreach ($rows as $r) {
                $cache[$r['key']] = $r['value'];
            }
        } catch (Throwable $e) {
            $cache = [];
        }
    }
    return $cache[$key] ?? $default;
}

/**
 * แสดง badge สถานะแบบสีสัน
 */
function status_badge(string $status): string
{
    $map = [
        'active'    => ['success', 'ใช้งาน'],
        'inactive'  => ['secondary', 'ปิดใช้งาน'],
        'published' => ['success', 'เผยแพร่'],
        'draft'     => ['warning', 'ฉบับร่าง'],
        'pending'   => ['info', 'รอตรวจ'],
    ];
    [$cls, $label] = $map[$status] ?? ['secondary', $status];
    return '<span class="badge bg-' . $cls . '">' . e($label) . '</span>';
}

/**
 * บันทึก Audit Log การกระทำของผู้ใช้
 */
function audit_log(string $action, string $module, string $detail = ''): void
{
    try {
        $db = new Database();
        $db->query("INSERT INTO audit_logs (user_id, action, module, detail, ip_address, user_agent, created_at)
                    VALUES (:uid, :action, :module, :detail, :ip, :ua, :now)")
           ->bind([
               'uid'    => Auth::id(),
               'action' => $action,
               'module' => $module,
               'detail' => $detail,
               'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
               'ua'     => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
               'now'    => date('Y-m-d H:i:s'),
           ])->execute();
    } catch (Throwable $e) {
        error_log('Audit log failed: ' . $e->getMessage());
    }
}

/**
 * เก็บค่า old input ไว้เติมในฟอร์มเมื่อ validation ไม่ผ่าน
 */
function old(string $key, $default = '')
{
    return e($_SESSION['old'][$key] ?? $default);
}
