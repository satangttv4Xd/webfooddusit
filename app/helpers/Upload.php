<?php
/**
 * ============================================================
 *  Upload (Helper)
 * ------------------------------------------------------------
 *  จัดการอัปโหลดไฟล์อย่างปลอดภัย ตรวจสอบชนิด/ขนาด/นามสกุล
 *  ตั้งชื่อไฟล์ใหม่กันการทับ และกันการอัปโหลดไฟล์อันตราย
 * ============================================================
 */

class Upload
{
    /** @var string ข้อความ error ล่าสุด */
    public static string $error = '';

    /**
     * อัปโหลดรูปภาพ
     * @param array  $file      รายการจาก $_FILES['field']
     * @param string $subfolder โฟลเดอร์ย่อยใน uploads/ เช่น 'news'
     * @return string|null  path สัมพัทธ์ (เช่น news/xxx.jpg) หรือ null เมื่อผิดพลาด
     */
    public static function image(array $file, string $subfolder = ''): ?string
    {
        return self::handle($file, $subfolder, explode(',', ALLOWED_IMAGE_TYPES));
    }

    /**
     * อัปโหลดเอกสาร (pdf/word/excel...)
     */
    public static function document(array $file, string $subfolder = ''): ?string
    {
        return self::handle($file, $subfolder, explode(',', ALLOWED_DOC_TYPES));
    }

    /**
     * ตัวจัดการหลัก
     */
    private static function handle(array $file, string $subfolder, array $allowed): ?string
    {
        self::$error = '';

        // ไม่มีการอัปโหลด
        if (!isset($file['tmp_name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            self::$error = 'ไม่พบไฟล์ที่อัปโหลด';
            return null;
        }

        // ตรวจ error จากการอัปโหลด
        if ($file['error'] !== UPLOAD_ERR_OK) {
            self::$error = 'เกิดข้อผิดพลาดขณะอัปโหลด (รหัส ' . $file['error'] . ')';
            return null;
        }

        // ตรวจขนาด
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            self::$error = 'ไฟล์มีขนาดใหญ่เกินกำหนด (สูงสุด ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . ' MB)';
            return null;
        }

        // ตรวจนามสกุล
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            self::$error = 'ชนิดไฟล์ไม่ได้รับอนุญาต (อนุญาต: ' . implode(', ', $allowed) . ')';
            return null;
        }

        // ตรวจ MIME type จริงของไฟล์ (กันการปลอมนามสกุล)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!self::isMimeAllowed($mime, $ext)) {
            self::$error = 'ชนิดไฟล์จริงไม่ตรงกับนามสกุล (อาจเป็นไฟล์อันตราย)';
            return null;
        }

        // เตรียมโฟลเดอร์ปลายทาง
        $targetDir = UPLOAD_PATH . '/' . trim($subfolder, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // ตั้งชื่อไฟล์ใหม่กันซ้ำและกันชื่ออันตราย
        $newName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $targetPath = $targetDir . '/' . $newName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            self::$error = 'ไม่สามารถย้ายไฟล์ไปยังปลายทางได้';
            return null;
        }

        return trim($subfolder, '/') . '/' . $newName;
    }

    /**
     * ตรวจว่า MIME type ตรงกับนามสกุลที่อนุญาต
     */
    private static function isMimeAllowed(string $mime, string $ext): bool
    {
        $map = [
            'jpg'  => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png'  => ['image/png'],
            'webp' => ['image/webp'],
            'gif'  => ['image/gif'],
            'pdf'  => ['application/pdf'],
            'doc'  => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
            'xls'  => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
            'ppt'  => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
        ];
        return isset($map[$ext]) && in_array($mime, $map[$ext], true);
    }

    /**
     * ลบไฟล์ที่อัปโหลดไว้ (ใช้ตอนแก้ไข/ลบข้อมูล)
     */
    public static function remove(?string $path): void
    {
        if (empty($path)) {
            return;
        }
        $full = UPLOAD_PATH . '/' . ltrim($path, '/');
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
