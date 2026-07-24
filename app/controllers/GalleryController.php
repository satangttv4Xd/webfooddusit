<?php
/**
 * GalleryController — แกลเลอรี (อัลบั้ม/รูปภาพ/วิดีโอ)
 */
class GalleryController extends Controller
{
    public function index(): void
    {
        $albums = $this->model('GalleryAlbum')->withCount();
        $this->view('public/gallery/index', [
            'title'  => 'แกลเลอรี',
            'albums' => $albums,
        ]);
    }

    public function album(string $slug = ''): void
    {
        $model = $this->model('GalleryAlbum');
        $album = $model->findBySlug($slug);
        if (!$album) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบอัลบั้ม']);
            return;
        }
        $items = $model->items((int)$album['id']);
        $this->view('public/gallery/album', [
            'title' => $album['name'],
            'album' => $album,
            'items' => $items,
        ]);
    }
}
