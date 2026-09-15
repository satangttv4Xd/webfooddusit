<?php
/**
 * ProgramController — หลักสูตร (แสดงทั้งหมด/รายละเอียด)
 */
class ProgramsController extends Controller
{
    /** แสดงหลักสูตรทั้งหมด — ย้ายไปแสดงที่หน้า /curriculum ซึ่งมีข้อมูลครบทุกสาขาแล้ว */
    public function index(): void
    {
        $this->redirect('curriculum');
    }

    /** รายละเอียดหลักสูตรตาม slug */
    public function detail(string $slug = ''): void
    {
        $program = $this->model('Program')->findBySlug($slug);
        if (!$program) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบหลักสูตร']);
            return;
        }
        $curriculum = $this->model('Program')->curriculum((int)$program['id']);

        $this->view('public/programs/detail', [
            'title'      => $program['name'],
            'program'    => $program,
            'curriculum' => $curriculum,
        ]);
    }
}
