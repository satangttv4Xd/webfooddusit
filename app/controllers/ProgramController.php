<?php
/**
 * ProgramController — หลักสูตร (แสดงทั้งหมด/รายละเอียด)
 */
class ProgramController extends Controller
{
    /** แสดงหลักสูตรทั้งหมด แบ่งตามระดับ */
    public function index(): void
    {
        $model = $this->model('Program');
        $this->view('public/programs/index', [
            'title'    => 'หลักสูตร',
            'bachelor' => $model->byLevel('bachelor'),
            'master'   => $model->byLevel('master'),
            'doctoral' => $model->byLevel('doctoral'),
            'training' => $model->byLevel('training'),
        ]);
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
