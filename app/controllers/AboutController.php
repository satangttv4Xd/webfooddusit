<?php
/**
 * AboutController — เกี่ยวกับเรา (ประวัติ วิสัยทัศน์ ผู้บริหาร บุคลากร)
 */
class AboutController extends Controller
{
    public function index(): void
    {
        $history    = $this->model('Page')->findBySlug('history');
        $vision     = $this->model('Page')->findBySlug('vision-mission');
        $executives = $this->model('Staff')->byType('executive');
        $teachers   = $this->model('Staff')->byType('teacher');
        $staff      = $this->model('Staff')->byType('staff');
        $departments= $this->model('Department')->activeAll();

        $this->view('public/about', [
            'title'       => 'เกี่ยวกับเรา',
            'history'     => $history,
            'vision'      => $vision,
            'executives'  => $executives,
            'teachers'    => $teachers,
            'staff'       => $staff,
            'departments' => $departments,
        ]);
    }
}
