<?php
/**
 * ============================================================
 *  ค่าการเชื่อมต่อฐานข้อมูล (Database Configuration)
 * ------------------------------------------------------------
 *  ค่าเริ่มต้นสำหรับ XAMPP คือ user=root ไม่มีรหัสผ่าน
 *  เมื่อขึ้นโฮสต์จริงให้แก้ค่าตามที่โฮสต์ให้มา
 * ============================================================
 */

// ตรวจสอบสภาพแวดล้อม (Local หรือ Production/Hosting)
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '']) || (php_sapi_name() === 'cli');

if ($isLocal) {
    // สำหรับเครื่อง Localhost (XAMPP)
    return [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'database' => 'fooddusit_db',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
        'collation'=> 'utf8mb4_unicode_ci',
        'options'  => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false,
        ],
    ];
}

// สำหรับ InfinityFree Hosting (webfooddusit.xo.je)
return [
    'host'     => 'sql203.infinityfree.com',
    'port'     => '3306',
    'database' => 'if0_42921351_fooddusit',
    'username' => 'if0_42921351',
    'password' => '6GFOxkxhBXv',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false,
    ],
];
