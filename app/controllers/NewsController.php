<?php
/**
 * NewsController — ข่าวสาร (รายการ/รายละเอียด/หมวดหมู่/แสดงความคิดเห็น)
 */
class NewsController extends Controller
{
    /** รายการข่าว + ค้นหา + กรองหมวดหมู่ */
    public function index(): void
    {
        $page     = (int)$this->query('page', 1);
        $keyword  = trim((string)$this->query('q', ''));
        $catSlug  = trim((string)$this->query('cat', ''));

        $categoryId = null;
        if ($catSlug !== '') {
            $cat = $this->model('NewsCategory')->findWhere(['slug' => $catSlug]);
            $categoryId = $cat['id'] ?? null;
        }

        $pager      = $this->model('News')->paginatePublic($page, $categoryId, $keyword);
        $categories = $this->model('NewsCategory')->withCount();
        $popular    = $this->model('News')->popular(5);

        $this->view('public/news/index', [
            'title'      => 'ข่าวสารและกิจกรรม',
            'pager'      => $pager,
            'categories' => $categories,
            'popular'    => $popular,
            'keyword'    => $keyword,
            'catSlug'    => $catSlug,
        ]);
    }

    /** รายละเอียดข่าว */
    public function detail(string $slug = ''): void
    {
        $news = $this->model('News')->findBySlug($slug);
        if (!$news) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบข่าว']);
            return;
        }

        // เพิ่มยอดวิว
        $this->model('News')->incrementViews((int)$news['id']);

        $related = $this->model('News')->related((int)$news['category_id'], (int)$news['id'], 4);
        $popular = $this->model('News')->popular(5);

        // ความคิดเห็นที่อนุมัติแล้ว
        $comments = [];
        if ($news['allow_comment']) {
            $comments = (new Database())
                ->query("SELECT * FROM news_comments WHERE news_id=:id AND status='approved' AND deleted_at IS NULL ORDER BY created_at DESC")
                ->bind(['id' => $news['id']])->all();
        }

        $this->view('public/news/detail', [
            'title'    => $news['title'],
            'news'     => $news,
            'related'  => $related,
            'popular'  => $popular,
            'comments' => $comments,
        ]);
    }

    /** รับความคิดเห็น (POST) */
    public function comment(): void
    {
        if (!$this->isPost()) { $this->redirect('news'); }
        $this->verifyCsrf();

        $newsId = (int)$this->input('news_id');
        $name   = $this->input('name');
        $comment= $this->input('comment');

        if ($name === '' || $comment === '') {
            $this->flash('error', 'กรุณากรอกชื่อและความคิดเห็น');
        } else {
            (new Database())->query("INSERT INTO news_comments (news_id,name,email,comment,status,created_at,updated_at)
                VALUES (:nid,:name,:email,:cmt,'pending',:now,:now)")
                ->bind([
                    'nid' => $newsId, 'name' => $name,
                    'email' => $this->input('email', ''),
                    'cmt' => $comment, 'now' => date('Y-m-d H:i:s'),
                ])->execute();
            $this->flash('success', 'ส่งความคิดเห็นแล้ว รอการอนุมัติจากผู้ดูแล');
        }

        $slug = $this->input('slug', '');
        $this->redirect('news/detail/' . $slug);
    }
}
