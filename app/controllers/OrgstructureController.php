<?php
/**
 * OrgstructureController — โครงสร้างการบริหารงาน (ผังองค์กร)
 * ข้อมูลอ้างอิงจากเว็บไซต์โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต
 */
class OrgstructureController extends Controller
{
    public function index(): void
    {
        $this->view('public/orgstructure', [
            'title'           => 'โครงสร้างการบริหารงาน',
            'metaDescription' => 'โครงสร้างการบริหารงานและผู้รับผิดชอบหลักสูตร โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
            'image'           => 'pages/org-structure.png',
        ]);
    }
}
