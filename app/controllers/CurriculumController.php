<?php
/**
 * CurriculumController — หน้าหลักสูตร/สาขาวิชา (โครงสร้างไว้ก่อน — skeleton)
 * เนื้อหาจริงเติมภายหลัง หรือย้ายไปเก็บในฐานข้อมูลได้
 */
class CurriculumController extends Controller
{
    /** รายการหลักสูตร: slug => ชื่อเต็ม */
    private array $curricula = [
        'bsc-culinary-tech'               => 'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ',
        'culinary-tech-service'           => 'สาขาวิชาเทคโนโลยีการประกอบอาหารและการบริการ',
        'home-economics'                  => 'สาขาวิชาคหกรรมศาสตร์',
        'food-innovation-2568'            => 'สาขาวิชานวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร (หลักสูตรปรับปรุง พ.ศ. 2568)',
        'nutrition-health-2568'           => 'สาขาวิชาโภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย (หลักสูตรปรับปรุง พ.ศ. 2568)',
        'food-industry'                   => 'สาขาวิชาอุตสาหกรรมการประกอบอาหาร',
        'nutrition-fitness'               => 'สาขาวิชาโภชนาการและการประกอบอาหารเพื่อการสร้างเสริมสมรรถภาพและการชะลอวัย',
        'food-business-culinary-arts-2569'=> 'สาขาวิชาการจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร (หลักสูตรพัฒนาใหม่ พ.ศ. 2569)',
    ];

    public function index(string $slug = ''): void
    {
        // ไม่ระบุ slug → แสดงรายการหลักสูตรทั้งหมด
        if ($slug === '') {
            $this->view('public/curriculum-list', [
                'title'           => 'หลักสูตร',
                'metaDescription' => 'หลักสูตรที่เปิดสอนของโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ด้านการประกอบอาหาร คหกรรมศาสตร์ และการบริการ',
                'curricula'       => $this->curricula,
            ]);
            return;
        }

        // ไม่พบหลักสูตรตาม slug → 404
        if (!isset($this->curricula[$slug])) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบหลักสูตร']);
            return;
        }

        $this->view('public/curriculum', [
            'title'           => $this->curricula[$slug],
            'metaDescription' => $this->curricula[$slug] . ' โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
            'name'            => $this->curricula[$slug],
            'slug'            => $slug,
        ]);
    }
}
