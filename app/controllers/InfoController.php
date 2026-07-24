<?php
/**
 * InfoController — หน้าข้อมูลทั่วไป (โครงสร้างไว้ก่อน / ยังไม่มีเนื้อหา)
 * ใช้กับเมนูที่ต้องการให้กดเข้าได้ แต่เนื้อหาจะเติมภายหลัง
 */
class InfoController extends Controller
{
    private array $pages = [
        'school-committee' => 'คณะกรรมการประจำโรงเรียนการเรือน',
        'staff-academic'   => 'บุคลากรสายวิชาการ',
        'staff-support'    => 'บุคลากรสายสนับสนุน',
        'annual-report'    => 'รายงานผลการดำเนินงาน',
        'newsletter'       => 'จดหมายข่าว',
        'km'               => 'การจัดการความรู้ (KM)',
    ];

    public function index(string $slug = ''): void
    {
        if (!isset($this->pages[$slug])) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบหน้าที่ต้องการ']);
            return;
        }

        $this->view('public/coming-soon', [
            'title'    => $this->pages[$slug],
            'pageName' => $this->pages[$slug],
        ]);
    }
}
