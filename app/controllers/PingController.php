<?php
/**
 * PingController — ปลายทางเล็ก ๆ ให้หน้าเว็บสาธารณะเช็คว่า "มีการเปลี่ยนแปลงเนื้อหาใหม่หรือไม่"
 * ใช้ audit_logs เป็นตัวบอกเวลาแก้ไขล่าสุด (ทุกครั้งที่แอดมิน สร้าง/แก้ไข/ลบ เนื้อหาจะถูกบันทึกไว้ที่นี่อยู่แล้ว)
 * ไม่นับ action ที่ไม่กระทบเนื้อหาสาธารณะ เช่น login/logout
 */
class PingController extends Controller
{
    public function updates(): void
    {
        $ts = 0;
        try {
            $last = (new Database())->query(
                "SELECT MAX(created_at) FROM audit_logs WHERE module <> 'auth'"
            )->column();
            if (!empty($last)) {
                $ts = strtotime($last);
            }
        } catch (Throwable $e) {
            // ตารางมีปัญหา/ไม่มีสิทธิ์ — ตอบ 0 ไปเฉย ๆ ฝั่ง JS จะไม่ reload
        }

        $this->json(['ts' => $ts]);
    }
}
