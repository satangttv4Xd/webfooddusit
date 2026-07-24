<?php
/**
 * AlumniController — ศิษย์เก่า (หน้าสาธารณะ)
 */
class AlumniController extends Controller
{
    public function index(): void
    {
        $this->view('public/alumni', [
            'title'           => 'ศิษย์เก่า',
            'metaDescription' => 'ศิษย์เก่าโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ความสำเร็จและเส้นทางอาชีพของบัณฑิตในสายการประกอบอาหารและการบริการ',
            'alumni'          => $this->model('Alumni')->activeAll(),
        ]);
    }
}
