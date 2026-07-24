<?php
/**
 * ============================================================
 *  ค่าการเชื่อมต่อฐานข้อมูล (Database Configuration)
 * ------------------------------------------------------------
 *  ค่าเริ่มต้นสำหรับ XAMPP คือ user=root ไม่มีรหัสผ่าน
 *  เมื่อขึ้นโฮสต์จริงให้แก้ค่าตามที่โฮสต์ให้มา
 * ============================================================
 */

return [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'database' => 'fooddusit_db',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'options'  => [
        // ให้โยน Exception เมื่อเกิดข้อผิดพลาด (ปลอดภัยกว่า)
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // ให้ผลลัพธ์เป็น associative array โดยค่าเริ่มต้น
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // ปิด emulation เพื่อให้ prepared statement ทำงานที่ฝั่ง DB จริง (ปลอดภัย)
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false,
    ],
];
