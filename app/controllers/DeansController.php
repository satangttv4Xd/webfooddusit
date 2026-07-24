<?php
/**
 * DeansController — ทำเนียบคณบดี (หน้าสาธารณะ)
 */
class DeansController extends Controller
{
    public function index(): void
    {
        $this->view('public/deans', [
            'title'           => 'ทำเนียบคณบดี',
            'metaDescription' => 'ทำเนียบคณบดีโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต รายนามผู้ดำรงตำแหน่งคณบดีตั้งแต่อดีตจนถึงปัจจุบัน',
            'deans'           => $this->model('Dean')->activeAll(),
        ]);
    }
}
