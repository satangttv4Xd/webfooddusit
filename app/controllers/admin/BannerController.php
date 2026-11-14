<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;

/**
 * Admin\BannerController — จัดการแบนเนอร์หน้าแรก (CRUD)
 */
class BannerController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('banner.manage');
    }

    public function index(): void
    {
        $banners = $this->model('Banner')->all('sort_order ASC, id ASC');
        $this->adminView('banner/index', ['title' => 'จัดการแบนเนอร์', 'banners' => $banners]);
    }

    public function create(): void
    {
        $this->adminView('banner/form', ['title' => 'เพิ่มแบนเนอร์', 'banner' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/banner'); }
        $this->verifyCsrf();

        $croppedData = trim($_POST['cropped_image'] ?? '');
        $path = null;

        if ($croppedData !== '') {
            $path = Upload::imageBase64($croppedData, 'banner');
            if ($path === null) {
                $this->flash('error', Upload::$error ?: 'ไม่สามารถบันทึกภาพแบนเนอร์ได้');
                $this->redirect('admin/banner/create');
            }
        } elseif (!empty($_FILES['image']['name'])) {
            $path = Upload::image($_FILES['image'], 'banner');
            if ($path === null) {
                $this->flash('error', Upload::$error);
                $this->redirect('admin/banner/create');
            }
        } else {
            $this->flash('error', 'กรุณาเลือกรูปแบนเนอร์');
            $this->redirect('admin/banner/create');
        }

        $data = $this->collect();
        $data['image'] = $path;
        $this->model('Banner')->create($data);
        audit_log('create', 'banner', 'เพิ่มแบนเนอร์');
        $this->flash('success', 'เพิ่มแบนเนอร์เรียบร้อยแล้ว');
        $this->redirect('admin/banner');
    }

    public function edit(int $id = 0): void
    {
        $banner = $this->model('Banner')->find($id);
        if (!$banner) { $this->flash('error', 'ไม่พบแบนเนอร์'); $this->redirect('admin/banner'); }
        $this->adminView('banner/form', ['title' => 'แก้ไขแบนเนอร์', 'banner' => $banner]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/banner'); }
        $this->verifyCsrf();
        $model = $this->model('Banner');
        $banner = $model->find($id);
        if (!$banner) { $this->flash('error', 'ไม่พบแบนเนอร์'); $this->redirect('admin/banner'); }

        $data = $this->collect();
        $croppedData = trim($_POST['cropped_image'] ?? '');

        if ($croppedData !== '') {
            $path = Upload::imageBase64($croppedData, 'banner');
            if ($path === null) {
                $this->flash('error', Upload::$error ?: 'ไม่สามารถบันทึกภาพแบนเนอร์ได้');
                $this->redirect('admin/banner/edit/' . $id);
            }
            Upload::remove($banner['image']);
            $data['image'] = $path;
        } elseif (!empty($_FILES['image']['name'])) {
            $path = Upload::image($_FILES['image'], 'banner');
            if ($path === null) {
                $this->flash('error', Upload::$error);
                $this->redirect('admin/banner/edit/' . $id);
            }
            Upload::remove($banner['image']);
            $data['image'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'banner', "แก้ไขแบนเนอร์ #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/banner');
    }

    public function delete(int $id = 0): void
    {
        $banner = $this->model('Banner')->find($id);
        if ($banner) { Upload::remove($banner['image']); }
        $this->model('Banner')->forceDelete($id);
        audit_log('delete', 'banner', "ลบแบนเนอร์ #{$id}");
        $this->flash('success', 'ลบแบนเนอร์แล้ว');
        $this->redirect('admin/banner');
    }

    private function collect(): array
    {
        return [
            'title'       => $this->input('title', ''),
            'subtitle'    => $this->input('subtitle', ''),
            'link_url'    => $this->input('link_url', ''),
            'button_text' => $this->input('button_text', ''),
            'sort_order'  => (int)$this->input('sort_order', 0),
            'status'      => $this->input('status', 'active'),
        ];
    }
}
