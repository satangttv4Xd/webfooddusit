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

        if ($slug === 'school-committee') {
            $this->view('public/school-committee', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'คณะกรรมการประจำโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'groups'          => $this->model('SchoolCommittee')->activeGrouped(),
            ]);
            return;
        }

        if ($slug === 'staff-academic') {
            $this->view('public/staff-academic', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'บุคลากรสายวิชาการ โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'groups'          => $this->model('Staff')->academicGrouped(),
            ]);
            return;
        }

        if ($slug === 'staff-support') {
            $this->view('public/staff-support', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'บุคลากรสายสนับสนุน โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'groups'          => $this->model('Staff')->groupedByType('staff'),
            ]);
            return;
        }

        if ($slug === 'annual-report') {
            $this->view('public/annual-report', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'รายงานผลการดำเนินงาน โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'items'           => $this->model('Document')->byCategory('รายงานผลการดำเนินงาน'),
            ]);
            return;
        }

        if ($slug === 'newsletter') {
            $this->view('public/newsletter', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'จดหมายข่าว โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'items'           => $this->model('Document')->byCategory('จดหมายข่าว'),
            ]);
            return;
        }

        if ($slug === 'km') {
            $this->view('public/km', [
                'title'           => $this->pages[$slug],
                'metaDescription' => 'การจัดการความรู้ (KM) โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
                'items'           => $this->model('Document')->byCategory('การจัดการความรู้ (KM)'),
            ]);
            return;
        }

        $this->view('public/coming-soon', [
            'title'    => $this->pages[$slug],
            'pageName' => $this->pages[$slug],
        ]);
    }
}
