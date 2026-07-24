<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Upload;
use Validator;

/**
 * Admin\NewsController — จัดการข่าว (CRUD + Soft Delete + Restore)
 */
class NewsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('news.view');
    }

    /** รายการข่าว + ค้นหา + แบ่งหน้า */
    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $q = trim((string)$this->query('q', ''));

        $searchSql = ''; $searchParams = [];
        if ($q !== '') {
            $searchSql = 'title LIKE :q';
            $searchParams = ['q' => "%{$q}%"];
        }

        $pager = $this->model('News')->paginate($page, ADMIN_PER_PAGE, [], 'id DESC', $searchSql, $searchParams);

        // แนบชื่อหมวดหมู่
        $cats = [];
        foreach ($this->model('NewsCategory')->all() as $c) { $cats[$c['id']] = $c['name']; }

        $this->adminView('news/index', [
            'title' => 'จัดการข่าว', 'pager' => $pager, 'cats' => $cats, 'q' => $q,
        ]);
    }

    /** ฟอร์มเพิ่มข่าว */
    public function create(): void
    {
        $this->requirePermission('news.create');
        $this->adminView('news/form', [
            'title' => 'เพิ่มข่าวใหม่',
            'news'  => null,
            'categories' => $this->model('NewsCategory')->all('sort_order ASC'),
        ]);
    }

    /** บันทึกข่าวใหม่ (POST) */
    public function store(): void
    {
        $this->requirePermission('news.create');
        if (!$this->isPost()) { $this->redirect('admin/news'); }
        $this->verifyCsrf();

        $data = $this->collect();
        if (($err = $this->validate($data)) !== '') {
            $_SESSION['old'] = $_POST;
            $this->flash('error', $err);
            $this->redirect('admin/news/create');
        }

        // อัปโหลดรูปปก
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'news');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/news/create'); }
            $data['cover_image'] = $path;
        }

        $data['user_id'] = \Auth::id();
        $id = $this->model('News')->create($data);
        audit_log('create', 'news', "เพิ่มข่าว #{$id}: {$data['title']}");
        $this->flash('success', 'เพิ่มข่าวเรียบร้อยแล้ว');
        $this->redirect('admin/news');
    }

    /** ฟอร์มแก้ไข */
    public function edit(int $id = 0): void
    {
        $this->requirePermission('news.edit');
        $news = $this->model('News')->find($id);
        if (!$news) { $this->flash('error', 'ไม่พบข่าว'); $this->redirect('admin/news'); }
        $this->adminView('news/form', [
            'title' => 'แก้ไขข่าว',
            'news'  => $news,
            'categories' => $this->model('NewsCategory')->all('sort_order ASC'),
        ]);
    }

    /** บันทึกการแก้ไข (POST) */
    public function update(int $id = 0): void
    {
        $this->requirePermission('news.edit');
        if (!$this->isPost()) { $this->redirect('admin/news'); }
        $this->verifyCsrf();

        $model = $this->model('News');
        $news = $model->find($id);
        if (!$news) { $this->flash('error', 'ไม่พบข่าว'); $this->redirect('admin/news'); }

        $data = $this->collect();
        if (($err = $this->validate($data)) !== '') {
            $this->flash('error', $err);
            $this->redirect('admin/news/edit/' . $id);
        }

        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'news');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/news/edit/' . $id); }
            Upload::remove($news['cover_image']); // ลบรูปเก่า
            $data['cover_image'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'news', "แก้ไขข่าว #{$id}");
        $this->flash('success', 'บันทึกการแก้ไขเรียบร้อยแล้ว');
        $this->redirect('admin/news');
    }

    /** ลบ (Soft Delete) */
    public function delete(int $id = 0): void
    {
        $this->requirePermission('news.delete');
        $this->model('News')->delete($id);
        audit_log('delete', 'news', "ลบข่าว #{$id}");
        $this->flash('success', 'ย้ายข่าวไปถังขยะแล้ว');
        $this->redirect('admin/news');
    }

    /** กู้คืน */
    public function restore(int $id = 0): void
    {
        $this->requirePermission('news.delete');
        $this->model('News')->restore($id);
        audit_log('restore', 'news', "กู้คืนข่าว #{$id}");
        $this->flash('success', 'กู้คืนข่าวเรียบร้อยแล้ว');
        $this->redirect('admin/news');
    }

    /** รวบรวมข้อมูลจากฟอร์ม */
    private function collect(): array
    {
        $title = $this->input('title');
        $slug = $this->input('slug') ?: Security::slug($title);
        return [
            'title'         => $title,
            'slug'          => $slug,
            'category_id'   => (int)$this->input('category_id'),
            'excerpt'       => $this->input('excerpt', ''),
            'content'       => $_POST['content'] ?? '',
            'tags'          => $this->input('tags', ''),
            'is_featured'   => $this->input('is_featured') ? 1 : 0,
            'allow_comment' => $this->input('allow_comment') ? 1 : 0,
            'meta_title'    => $this->input('meta_title', ''),
            'meta_description' => $this->input('meta_description', ''),
            'status'        => $this->input('status', 'draft'),
            'published_at'  => $this->input('status') === 'published'
                                ? ($this->input('published_at') ?: date('Y-m-d H:i:s'))
                                : ($this->input('published_at') ?: null),
        ];
    }

    /** ตรวจสอบข้อมูล */
    private function validate(array $data): string
    {
        $v = new Validator($data);
        $v->validate(
            ['title' => 'required|max:255', 'content' => 'required'],
            ['title' => 'หัวข้อข่าว', 'content' => 'เนื้อหา']
        );
        if ($data['category_id'] <= 0) return 'กรุณาเลือกหมวดหมู่';
        return $v->firstError();
    }
}
