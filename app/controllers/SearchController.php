<?php
/**
 * SearchController — ค้นหาทั้งเว็บไซต์ (ข่าว/หลักสูตร/ดาวน์โหลด/FAQ/หน้า)
 */
class SearchController extends Controller
{
    public function index(): void
    {
        $keyword = trim((string)$this->query('q', ''));
        $results = ['news' => [], 'programs' => [], 'staff' => [], 'deans' => [], 'downloads' => [], 'faq' => [], 'pages' => []];

        if ($keyword !== '' && mb_strlen($keyword) >= 2) {
            $kw = "%{$keyword}%";
            $db = new Database();

            $results['news'] = $db->query(
                "SELECT id,title,slug,excerpt,cover_image FROM news
                 WHERE (title LIKE :k OR content LIKE :k2) AND status='published' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw])->all();

            $results['programs'] = $db->query(
                "SELECT id,name,slug,overview FROM programs
                 WHERE (name LIKE :k OR overview LIKE :k2) AND status='active' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw])->all();

            $results['staff'] = $db->query(
                "SELECT id,full_name,position,type FROM staff
                 WHERE (full_name LIKE :k OR position LIKE :k2 OR expertise LIKE :k3) AND status='active' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw, 'k3' => $kw])->all();

            $results['deans'] = $db->query(
                "SELECT id,full_name,position,term FROM deans
                 WHERE (full_name LIKE :k OR position LIKE :k2 OR term LIKE :k3) AND status='active' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw, 'k3' => $kw])->all();

            $results['downloads'] = $db->query(
                "SELECT id,title,description FROM downloads
                 WHERE (title LIKE :k OR description LIKE :k2) AND status='active' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw])->all();

            $results['faq'] = $db->query(
                "SELECT id,question,answer FROM faq
                 WHERE (question LIKE :k OR answer LIKE :k2) AND status='active' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw])->all();

            $results['pages'] = $db->query(
                "SELECT id,title,slug FROM pages
                 WHERE (title LIKE :k OR content LIKE :k2) AND status='published' AND deleted_at IS NULL
                 LIMIT 10")->bind(['k' => $kw, 'k2' => $kw])->all();
        }

        $total = array_sum(array_map('count', $results));

        $this->view('public/search', [
            'title'   => 'ผลการค้นหา: ' . $keyword,
            'keyword' => $keyword,
            'results' => $results,
            'total'   => $total,
        ]);
    }
}
