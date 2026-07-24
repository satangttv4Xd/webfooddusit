<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Upload;

/**
 * Admin\GalleryController — จัดการแกลเลอรี
 *   - อัลบั้ม (gallery_albums): CRUD
 *   - รูป/วิดีโอในอัลบั้ม (gallery): เพิ่ม/ลบ
 */
class GalleryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('gallery.manage');
    }

    /* ============ อัลบั้ม ============ */

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $pager = $this->model('GalleryAlbum')->paginate($page, ADMIN_PER_PAGE, [], 'event_date DESC, id DESC');

        // นับจำนวนรูปของแต่ละอัลบั้ม
        $counts = [];
        foreach ($pager['data'] as $a) {
            $counts[$a['id']] = $this->model('Gallery')->count(['album_id' => $a['id']]);
        }

        $this->adminView('gallery/index', ['title' => 'จัดการแกลเลอรี', 'pager' => $pager, 'counts' => $counts]);
    }

    public function create(): void
    {
        $this->adminView('gallery/form', ['title' => 'เพิ่มอัลบั้ม', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/gallery'); }
        $this->verifyCsrf();
        if ($this->input('name') === '') { $this->flash('error', 'กรุณากรอกชื่ออัลบั้ม'); $this->redirect('admin/gallery/create'); }

        $data = $this->collect();
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'gallery');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/gallery/create'); }
            $data['cover_image'] = $path;
        }

        $id = $this->model('GalleryAlbum')->create($data);
        audit_log('create', 'gallery', "เพิ่มอัลบั้ม #{$id}");
        $this->flash('success', 'เพิ่มอัลบั้มเรียบร้อยแล้ว จัดการรูปภาพได้เลย');
        $this->redirect('admin/gallery/items/' . $id);
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('GalleryAlbum')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบอัลบั้ม'); $this->redirect('admin/gallery'); }
        $this->adminView('gallery/form', ['title' => 'แก้ไขอัลบั้ม', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/gallery'); }
        $this->verifyCsrf();
        $model = $this->model('GalleryAlbum');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบอัลบั้ม'); $this->redirect('admin/gallery'); }

        $data = $this->collect();
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'gallery');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/gallery/edit/' . $id); }
            Upload::remove($item['cover_image']);
            $data['cover_image'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'gallery', "แก้ไขอัลบั้ม #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/gallery');
    }

    public function delete(int $id = 0): void
    {
        $this->model('GalleryAlbum')->delete($id);
        audit_log('delete', 'gallery', "ลบอัลบั้ม #{$id}");
        $this->flash('success', 'ลบอัลบั้มแล้ว');
        $this->redirect('admin/gallery');
    }

    /* ============ รูป/วิดีโอในอัลบั้ม ============ */

    /** หน้าจัดการรูปภาพในอัลบั้ม */
    public function items(int $albumId = 0): void
    {
        $album = $this->model('GalleryAlbum')->find($albumId);
        if (!$album) { $this->flash('error', 'ไม่พบอัลบั้ม'); $this->redirect('admin/gallery'); }
        $items = $this->model('Gallery')->where(['album_id' => $albumId], 'sort_order ASC, id ASC');
        $this->adminView('gallery/items', [
            'title' => 'รูปภาพ: ' . $album['name'],
            'album' => $album,
            'items' => $items,
        ]);
    }

    /** อัปโหลดรูปหลายไฟล์ / เพิ่มลิงก์วิดีโอ */
    public function upload(int $albumId = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/gallery'); }
        $this->verifyCsrf();
        $album = $this->model('GalleryAlbum')->find($albumId);
        if (!$album) { $this->flash('error', 'ไม่พบอัลบั้ม'); $this->redirect('admin/gallery'); }

        $model = $this->model('Gallery');
        $sort = $model->count(['album_id' => $albumId]);
        $added = 0;

        // เพิ่มลิงก์วิดีโอ (YouTube)
        $videoUrl = $this->input('video_url', '');
        if ($videoUrl !== '') {
            $model->create([
                'album_id'   => $albumId,
                'title'      => $this->input('video_title', 'วิดีโอ'),
                'type'       => 'video',
                'video_url'  => $videoUrl,
                'sort_order' => $sort++,
                'status'     => 'active',
            ]);
            $added++;
        }

        // อัปโหลดรูปหลายไฟล์
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $total = count($files['name']);
            for ($i = 0; $i < $total; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) { continue; }
                $single = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];
                $path = Upload::image($single, 'gallery');
                if ($path === null) { continue; }
                $model->create([
                    'album_id'   => $albumId,
                    'title'      => pathinfo($files['name'][$i], PATHINFO_FILENAME),
                    'type'       => 'image',
                    'file_path'  => $path,
                    'sort_order' => $sort++,
                    'status'     => 'active',
                ]);
                $added++;
            }
        }

        if ($added > 0) {
            audit_log('create', 'gallery', "เพิ่มรูป {$added} รายการในอัลบั้ม #{$albumId}");
            $this->flash('success', "เพิ่ม {$added} รายการเรียบร้อยแล้ว");
        } else {
            $this->flash('warning', 'ไม่มีรายการที่เพิ่ม (กรุณาเลือกรูปหรือใส่ลิงก์วิดีโอ)');
        }
        $this->redirect('admin/gallery/items/' . $albumId);
    }

    /** ลบรูป/วิดีโอเดี่ยว */
    public function deleteItem(int $id = 0): void
    {
        $item = $this->model('Gallery')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบรายการ'); $this->redirect('admin/gallery'); }
        if (!empty($item['file_path'])) { Upload::remove($item['file_path']); }
        $this->model('Gallery')->forceDelete($id);
        $this->flash('success', 'ลบรายการแล้ว');
        $this->redirect('admin/gallery/items/' . $item['album_id']);
    }

    private function collect(): array
    {
        $name = $this->input('name');
        return [
            'name'        => $name,
            'slug'        => $this->input('slug') ?: Security::slug($name),
            'description' => $this->input('description', ''),
            'event_date'  => $this->input('event_date') ?: null,
            'status'      => $this->input('status', 'active'),
        ];
    }
}
