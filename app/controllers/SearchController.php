<?php
/**
 * SearchController — ค้นหาทั้งเว็บไซต์
 * ครอบคลุม: ข่าว หลักสูตร(DB) กิจกรรม บุคลากร คณบดี ศิษย์เก่า ดาวน์โหลด FAQ หน้าเนื้อหา
 *           + หน้าหลักสูตร/หน้าย่อย (จาก CurriculumController) + เมนูหน้าเว็บทั่วไป
 * รองรับ: หลายคำค้น (AND), ไม่สนตัวพิมพ์เล็ก-ใหญ่, ตัดแท็ก HTML, จัดอันดับ, ตัดข้อความรอบคำค้น
 */
class SearchController extends Controller
{
    /** จำนวนผลลัพธ์สูงสุดต่อหมวด */
    private const PER_GROUP = 20;

    public function index(): void
    {
        $keyword = trim((string)$this->query('q', ''));
        $tokens  = $this->tokenize($keyword);
        $groups  = $this->lookup($tokens);
        $total   = array_sum(array_map('count', $groups));

        $this->view('public/search', [
            'title'           => $keyword !== '' ? 'ผลการค้นหา: ' . $keyword : 'ค้นหา',
            'metaDescription' => 'ค้นหาข้อมูลในเว็บไซต์โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต',
            'keyword'         => $keyword,
            'tokens'          => $tokens,
            'groups'          => $groups,
            'total'           => $total,
        ]);
    }

    /**
     * ค้นหาแล้วคืนผลลัพธ์เป็น array (ใช้ซ้ำได้จากที่อื่น เช่น ChatController)
     * @return array ['หมวด' => [['title','url','excerpt','score'], ...], ...]
     */
    public function lookup(array $tokens): array
    {
        $groups = [];
        if ($tokens) {
            // ค้นหลักสูตรก่อน เพื่อกันเมนูที่ชี้ไปหน้าเดียวกันแสดงซ้ำ
            $curriculum = $this->searchCurriculum($tokens);

            $groups = [
                'curriculum' => $curriculum,
                'news'       => $this->searchTable($tokens, 'news', ['title', 'excerpt', 'content', 'tags'],
                                    "status='published'", fn($r) => ['title' => $r['title'], 'url' => 'news/detail/' . $r['slug']]),
                'programs'   => $this->searchTable($tokens, 'programs', ['name', 'name_en', 'overview', 'qualification', 'careers'],
                                    "status='active'", fn($r) => ['title' => $r['name'], 'url' => 'programs/detail/' . $r['slug']]),
                'events'     => $this->searchTable($tokens, 'events', ['title', 'description', 'location'],
                                    "status='active'", fn($r) => ['title' => $r['title'], 'url' => 'events/detail/' . $r['slug']]),
                'staff'      => $this->searchTable($tokens, 'staff', ['full_name', 'position', 'expertise', 'bio'],
                                    "status='active'", fn($r) => ['title' => $r['full_name'], 'url' => $r['type'] === 'staff' ? 'info/staff-support' : 'info/staff-academic']),
                'deans'      => $this->searchTable($tokens, 'deans', ['full_name', 'position', 'term', 'bio'],
                                    "status='active'", fn($r) => ['title' => $r['full_name'], 'url' => 'deans']),
                'committee'  => $this->searchTable($tokens, 'school_committee', ['full_name', 'position', 'bio'],
                                    "status='active'", fn($r) => ['title' => $r['full_name'], 'url' => 'info/school-committee']),
                'documents'  => $this->searchTable($tokens, 'documents', ['title', 'doc_number'],
                                    "status='active'", fn($r) => ['title' => $r['title'], 'url' => self::documentUrl($r['category'] ?? '')]),
                'alumni'     => $this->searchTable($tokens, 'alumni', ['full_name', 'program', 'current_job', 'organization', 'quote'],
                                    "status='active'", fn($r) => ['title' => $r['full_name'], 'url' => 'alumni']),
                'downloads'  => $this->searchTable($tokens, 'downloads', ['title', 'description'],
                                    "status='active'", fn($r) => ['title' => $r['title'], 'url' => 'downloads']),
                'faq'        => $this->searchTable($tokens, 'faq', ['question', 'answer'],
                                    "status='active'", fn($r) => ['title' => $r['question'], 'url' => 'about']),
                'pages'      => $this->searchTable($tokens, 'pages', ['title', 'content'],
                                    "status='published'", fn($r) => ['title' => $r['title'], 'url' => 'page/' . $r['slug']]),
                'menu'       => $this->searchMenu($tokens, array_column($curriculum, 'url')),
            ];
            // ตัดหมวดที่ไม่มีผลลัพธ์ออก
            $groups = array_filter($groups);
        }
        return $groups;
    }

    // ============================================================
    //  ตัวช่วยค้นหา
    // ============================================================

    /** แยกคำค้นเป็นคำย่อย (ตัดช่องว่างซ้ำ) — ทุกคำต้องพบทั้งหมด (AND) */
    public function tokenize(string $keyword): array
    {
        $parts = preg_split('/\s+/u', $keyword, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $parts = array_slice(array_unique($parts), 0, 5); // กันคำค้นยาวเกินจำเป็น
        return array_values(array_filter($parts, fn($p) => mb_strlen($p) >= 2));
    }

    /** escape อักขระพิเศษของ LIKE (% _ \) ไม่ให้กลายเป็น wildcard */
    private function likeEscape(string $s): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $s);
    }

    /**
     * ค้นหาในตารางฐานข้อมูล
     * @param array    $cols   คอลัมน์ที่ค้น (คอลัมน์แรกถือเป็น "ชื่อเรื่อง" ใช้จัดอันดับ)
     * @param string   $extra  เงื่อนไขเพิ่ม เช่น "status='active'"
     * @param callable $map    แปลงแถวเป็น ['title'=>..,'url'=>..]
     */
    private function searchTable(array $tokens, string $table, array $cols, string $extra, callable $map): array
    {
        // ทุก token ต้องพบในคอลัมน์ใดคอลัมน์หนึ่ง (AND ระหว่าง token, OR ระหว่างคอลัมน์)
        $where  = [];
        $params = [];
        foreach ($tokens as $i => $tk) {
            $ors = [];
            foreach ($cols as $j => $c) {
                $p = "t{$i}_{$j}";
                $ors[] = "{$c} LIKE :{$p} ESCAPE '\\\\'";
                $params[$p] = '%' . $this->likeEscape($tk) . '%';
            }
            $where[] = '(' . implode(' OR ', $ors) . ')';
        }

        $sql = sprintf(
            'SELECT %s FROM %s WHERE %s AND %s AND deleted_at IS NULL LIMIT %d',
            implode(',', array_unique(array_merge(['id'], $cols, $this->extraCols($table)))),
            $table,
            implode(' AND ', $where),
            $extra,
            self::PER_GROUP
        );

        try {
            $rows = (new Database())->query($sql)->bind($params)->all();
        } catch (Throwable $e) {
            return []; // ตารางไม่มีคอลัมน์นั้น/ตารางหาย — ข้ามหมวดนี้ไป ไม่ทำให้ทั้งหน้าพัง
        }

        $out = [];
        foreach ($rows as $r) {
            $base = $map($r);
            // เนื้อหาสำหรับตัดข้อความ: รวมทุกคอลัมน์ที่ค้น
            $body = '';
            foreach ($cols as $c) { $body .= ' ' . ($r[$c] ?? ''); }
            $out[] = [
                'title'   => (string)$base['title'],
                'url'     => $base['url'],
                'excerpt' => $this->excerpt($body, $tokens),
                'score'   => $this->score((string)$base['title'], $body, $tokens),
            ];
        }
        return $this->sortByScore($out);
    }

    /** คอลัมน์เพิ่มที่ต้อง SELECT มาใช้สร้างลิงก์ (เช่น slug) */
    private function extraCols(string $table): array
    {
        if (in_array($table, ['news', 'programs', 'events', 'pages'], true)) { return ['slug']; }
        if ($table === 'staff') { return ['type']; }
        if ($table === 'documents') { return ['category']; }
        return [];
    }

    /** หมวดหมู่เอกสาร → หน้าเว็บที่แสดงเอกสารนั้น */
    private static function documentUrl(string $category): string
    {
        return match ($category) {
            'จดหมายข่าว'             => 'info/newsletter',
            'การจัดการความรู้ (KM)'  => 'info/km',
            default                   => 'info/annual-report',
        };
    }

    /** ค้นหน้าหลักสูตรและหน้าย่อย (ข้อมูลอยู่ใน CurriculumController ไม่ใช่ฐานข้อมูล) */
    private function searchCurriculum(array $tokens): array
    {
        $file = APP_PATH . '/controllers/CurriculumController.php';
        if (!is_file($file)) { return []; }
        require_once $file;
        if (!method_exists('CurriculumController', 'searchIndex')) { return []; }

        $out = [];
        foreach (CurriculumController::searchIndex() as $item) {
            if (!$this->matchAll($item['text'], $tokens)) { continue; }
            $out[] = [
                'title'   => $item['title'],
                'url'     => $item['url'],
                'excerpt' => $this->excerpt($item['text'], $tokens),
                'score'   => $this->score($item['title'], $item['text'], $tokens),
            ];
        }
        return $this->sortByScore($out);
    }

    /**
     * ค้นเมนูของเว็บ (ครอบคลุมหน้าทั่วไป เช่น เกี่ยวกับเรา ติดต่อ แกลเลอรี)
     * @param array $excludeUrls url ที่แสดงในหมวดอื่นแล้ว จะไม่แสดงซ้ำ
     */
    private function searchMenu(array $tokens, array $excludeUrls = []): array
    {
        try {
            $rows = (new Database())->query(
                "SELECT title, url FROM menu WHERE status='active' AND deleted_at IS NULL"
            )->all();
        } catch (Throwable $e) {
            return [];
        }

        $out  = [];
        $seen = array_flip(array_map(fn($u) => trim($u, '/'), $excludeUrls));
        foreach ($rows as $r) {
            $url = trim((string)$r['url'], '/');
            // ข้ามลิงก์ภายนอก รายการซ้ำ และหน้าที่แสดงในหมวดอื่นแล้ว
            if ($url === '' || str_starts_with($url, 'http') || isset($seen[$url])) { continue; }
            if (!$this->matchAll($r['title'], $tokens)) { continue; }
            $seen[$url] = true;
            $out[] = [
                'title'   => $r['title'],
                'url'     => $url,
                'excerpt' => '',
                'score'   => $this->score($r['title'], $r['title'], $tokens),
            ];
        }
        return $this->sortByScore($out);
    }

    /** ข้อความมีครบทุก token หรือไม่ */
    private function matchAll(string $haystack, array $tokens): bool
    {
        $h = mb_strtolower($haystack);
        foreach ($tokens as $tk) {
            if (mb_strpos($h, mb_strtolower($tk)) === false) { return false; }
        }
        return true;
    }

    /** คะแนนความเกี่ยวข้อง: เจอในชื่อเรื่องสำคัญกว่าเจอในเนื้อหา */
    private function score(string $title, string $body, array $tokens): int
    {
        $t = mb_strtolower($title);
        $b = mb_strtolower(strip_tags($body));
        $score = 0;
        foreach ($tokens as $tk) {
            $k = mb_strtolower($tk);
            if ($t === $k)                        { $score += 100; }  // ตรงทั้งชื่อ
            elseif (mb_strpos($t, $k) === 0)      { $score += 50; }   // ขึ้นต้นด้วยคำค้น
            elseif (mb_strpos($t, $k) !== false)  { $score += 30; }   // อยู่ในชื่อ
            if (mb_strpos($b, $k) !== false)      { $score += 5; }    // อยู่ในเนื้อหา
        }
        return $score;
    }

    /** เรียงตามคะแนนมาก→น้อย */
    private function sortByScore(array $rows): array
    {
        usort($rows, fn($a, $b) => $b['score'] <=> $a['score']);
        return $rows;
    }

    /** ตัดข้อความรอบ ๆ คำค้นมาแสดง (คล้าย snippet ของ Google) */
    private function excerpt(string $body, array $tokens, int $len = 160): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string)$body)));
        if ($text === '') { return ''; }

        $pos = false;
        foreach ($tokens as $tk) {
            $p = mb_stripos($text, $tk);
            if ($p !== false) { $pos = $p; break; }
        }
        if ($pos === false) {
            return mb_substr($text, 0, $len) . (mb_strlen($text) > $len ? '…' : '');
        }

        $start = max(0, $pos - 60);
        $snippet = mb_substr($text, $start, $len);
        return ($start > 0 ? '…' : '') . $snippet . ($start + $len < mb_strlen($text) ? '…' : '');
    }
}
