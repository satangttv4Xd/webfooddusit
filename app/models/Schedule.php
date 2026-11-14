<?php
/**
 * Model: Schedule — ปฏิทินงาน / ภารกิจปฏิบัติงาน
 */
class Schedule extends Model
{
    protected string $table = 'schedules';
    private static bool $checkedSchema = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * ตรวจสอบและสร้างตาราง schedules รวมถึงสิทธิ์ schedules.manage ให้อัตโนมัติหากยังไม่มี (Auto Migration)
     * ทำให้เวลายกโค้ดไปรันเครื่องอื่นหรือโฮสต์อื่น ระบบจะสร้างตารางและสิทธิ์ให้เองทันที
     */
    private function ensureTableExists(): void
    {
        if (self::$checkedSchema) {
            return;
        }

        try {
            // สร้างตาราง schedules หากยังไม่มี
            $sql = "CREATE TABLE IF NOT EXISTS `schedules` (
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `title` VARCHAR(255) NOT NULL COMMENT 'ชื่องาน / ภารกิจ',
              `description` TEXT DEFAULT NULL COMMENT 'รายละเอียดงาน',
              `start_date` DATETIME NOT NULL COMMENT 'วันเวลาเริ่มต้น',
              `end_date` DATETIME DEFAULT NULL COMMENT 'วันเวลาสิ้นสุด',
              `location` VARCHAR(255) DEFAULT NULL COMMENT 'สถานที่ / ลิงก์ห้องประชุม',
              `image` VARCHAR(255) DEFAULT NULL COMMENT 'รูปภาพประกอบงาน',
              `color` VARCHAR(20) DEFAULT '#0d6efd' COMMENT 'สีแสดงบนปฏิทิน',
              `assigned_to` VARCHAR(255) DEFAULT NULL COMMENT 'ผู้รับผิดชอบ / หน่วยงาน',
              `status` ENUM('active','completed','cancelled') NOT NULL DEFAULT 'active',
              `created_at` DATETIME DEFAULT NULL,
              `updated_at` DATETIME DEFAULT NULL,
              `deleted_at` DATETIME DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_schedule_start` (`start_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->db->query($sql)->execute();

            // เพิ่มคอลัมน์ image หากตารางเดิมยังไม่มี
            try {
                $this->db->query("ALTER TABLE `schedules` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL COMMENT 'รูปภาพประกอบงาน' AFTER `location`")->execute();
            } catch (\Throwable $ignored) {}

            // เพิ่ม Permission สำหรับจัดการปฏิทินงาน หากยังไม่มี
            $permSql = "INSERT IGNORE INTO `permissions` (`name`, `slug`, `module`, `status`, `created_at`)
                        VALUES ('จัดการปฏิทินงาน', 'schedules.manage', 'schedules', 'active', NOW());";
            $this->db->query($permSql)->execute();

            // ผูกสิทธิ์ให้ Super Admin (1) และ Admin (2)
            $rolePermSql = "INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`)
                            SELECT 1, id, NOW() FROM `permissions` WHERE `slug` = 'schedules.manage'
                            UNION
                            SELECT 2, id, NOW() FROM `permissions` WHERE `slug` = 'schedules.manage';";
            $this->db->query($rolePermSql)->execute();

            // เพิ่มเมนู "ปฏิทินงาน" ลง Navbar อัตโนมัติ (ถ้ายังไม่มี)
            try {
                $hasMenu = $this->db->query("SELECT id FROM `menu` WHERE `url` = 'calendar' LIMIT 1")->column();
                if (!$hasMenu) {
                    $this->db->query("INSERT INTO `menu` (`parent_id`, `title`, `url`, `position`, `sort_order`, `status`, `created_at`, `updated_at`)
                                     VALUES (NULL, 'ปฏิทินงาน', 'calendar', 'header', 6, 'active', NOW(), NOW())")->execute();
                    $this->db->query("UPDATE `menu` SET `sort_order` = `sort_order` + 1 WHERE `url` IN ('gallery','downloads','contact')")->execute();
                }
            } catch (\Throwable $ignored) {}

            self::$checkedSchema = true;
        } catch (\Throwable $e) {
            error_log('Schedule auto-migration error: ' . $e->getMessage());
        }
    }

    /**
     * ดึงรายการงานตามช่วงวันที่ (สำหรับ FullCalendar)
     */
    public function getBetweenDates(string $startDate, string $endDate, bool $onlyActive = false): array
    {
        $sql = "SELECT * FROM schedules
                WHERE deleted_at IS NULL";
        
        if ($onlyActive) {
            $sql .= " AND status = 'active'";
        }

        $sql .= " AND (
            (DATE(start_date) <= :end_date1 AND (DATE(end_date) >= :start_date1 OR end_date IS NULL))
            OR (DATE(start_date) BETWEEN :start_date2 AND :end_date2)
        )
        ORDER BY start_date ASC";

        return $this->db->query($sql)->bind([
            'start_date1' => $startDate,
            'end_date1'   => $endDate,
            'start_date2' => $startDate,
            'end_date2'   => $endDate,
        ])->all();
    }
}
