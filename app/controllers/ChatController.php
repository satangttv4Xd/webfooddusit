<?php
/**
 * ChatController — ผู้ช่วยตอบคำถามอัตโนมัติ (ไม่ใช้ AI / ไม่มีค่าใช้จ่าย)
 * ตอบจากข้อมูลจริงในเว็บ: FAQ, หลักสูตร, ค่าธรรมเนียม, ทุน, ติดต่อ, ข่าว/กิจกรรม
 * รับ POST ที่ /chat/ask แล้วตอบกลับเป็น JSON
 */
class ChatController extends Controller
{
    /** จำนวนลิงก์แนะนำสูงสุดต่อคำตอบ (>= จำนวนหลักสูตรทั้งหมด กันไม่ให้รายการหลักสูตรถูกตัด) */
    private const MAX_LINKS = 8;

    /** ปุ่มคำถามยอดนิยม (แสดงตอนเปิดแชท) */
    public const QUICK = [
        'มีหลักสูตรอะไรบ้าง',
        'ค่าเทอมเท่าไหร่',
        'สมัครเรียนยังไง',
        'มีทุนการศึกษาไหม',
        'ติดต่อยังไง',
    ];

    /** ตอบคำถาม (AJAX) */
    public function ask(): void
    {
        $msg = trim((string)($this->input('message') ?? ''));

        if ($msg === '') {
            $this->json(['reply' => 'พิมพ์คำถามได้เลยครับ 😊', 'links' => []]);
            return;
        }
        if (mb_strlen($msg) > 300) {
            $msg = mb_substr($msg, 0, 300);
        }

        [$reply, $links] = $this->answer($msg);
        $this->json(['reply' => $reply, 'links' => array_slice($links, 0, self::MAX_LINKS)]);
    }

    // ============================================================
    //  ตรรกะการตอบ
    // ============================================================

    /** หาคำตอบจากข้อความ → [ข้อความตอบ, ลิงก์แนะนำ] */
    private function answer(string $msg): array
    {
        $m = mb_strtolower($msg);

        // 1) ทักทาย / ขอบคุณ — ตอบสั้น ๆ ไม่ต้องค้นหา
        if ($this->has($m, ['สวัสดี', 'หวัดดี', 'hello', 'hi ', 'ดีครับ', 'ดีค่ะ'])) {
            return ['สวัสดีครับ 👋 ผมเป็นผู้ช่วยตอบคำถามของโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ถามเรื่องหลักสูตร ค่าเทอม การรับสมัคร หรือทุนการศึกษาได้เลยครับ', []];
        }
        if ($this->has($m, ['ขอบคุณ', 'ขอบใจ', 'thank'])) {
            return ['ยินดีครับ 🙏 หากมีคำถามเพิ่มเติมถามได้ตลอดนะครับ', []];
        }

        // 2) เจตนาเฉพาะเรื่อง (ตอบตรงประเด็นจากข้อมูลจริง)
        // ตรวจ "คณะกรรมการ" ก่อนเสมอ เพราะมีคำว่า "คณะ" ซ้อนอยู่ ไม่งั้นจะโดนจับเป็นคำถามเรื่องหลักสูตรผิด
        if ($this->has($m, ['คณะกรรมการ', 'กรรมการประจำโรงเรียน', 'กรรมการโรงเรียน'])) {
            return $this->answerCommittee();
        }
        if ($this->has($m, ['ติดต่อ', 'เบอร์', 'โทร', 'อีเมล', 'email', 'ที่อยู่', 'แผนที่'])) {
            return $this->answerContact();
        }
        if ($this->has($m, ['สมัคร', 'รับสมัคร', 'tcas', 'เข้าเรียน', 'admission'])) {
            return $this->answerAdmission();
        }
        if ($this->has($m, ['ทุน', 'กยศ', 'กรอ', 'scholarship'])) {
            return $this->answerScholarship();
        }
        if ($this->has($m, ['ค่าเทอม', 'ค่าเรียน', 'ค่าธรรมเนียม', 'ค่าใช้จ่าย', 'แพงไหม', 'ราคา'])) {
            return $this->answerTuition($msg);
        }
        if ($this->has($m, ['คณบดี', 'dean'])) {
            return $this->answerDeans();
        }
        if ($this->has($m, ['อาจารย์', 'บุคลากร', 'staff', 'เจ้าหน้าที่'])) {
            return $this->answerStaff($msg);
        }
        if ($this->has($m, ['จดหมายข่าว', 'newsletter'])) {
            return $this->answerDocumentCategory('info/newsletter', 'จดหมายข่าว');
        }
        if ($this->has($m, ['การจัดการความรู้', ' km ', 'knowledge management'])) {
            return $this->answerDocumentCategory('info/km', 'การจัดการความรู้ (KM)');
        }
        if ($this->has($m, ['รายงานผลการดำเนินงาน', 'annual report'])) {
            return $this->answerDocumentCategory('info/annual-report', 'รายงานผลการดำเนินงาน');
        }
        if ($this->has($m, ['หลักสูตร', 'สาขา', 'เรียนอะไร', 'เปิดสอน', 'คณะ'])) {
            return $this->answerCurriculumList($msg);
        }

        // 3) ค้นหาใน FAQ ก่อน (คำตอบสำเร็จรูปที่ผู้ดูแลเขียนไว้)
        if ($faq = $this->findFaq($msg)) {
            return [$faq['answer'], [['title' => 'ดูคำถามที่พบบ่อยทั้งหมด', 'url' => url('about')]]];
        }

        // 4) ค้นทั้งเว็บ (ใช้ระบบค้นหาเดียวกับหน้า /search)
        return $this->answerBySearch($msg);
    }

    /**
     * ข้อความมีคำใดคำหนึ่งหรือไม่ — ยอมให้สะกดผิดเล็กน้อยได้
     * เช่น "ค่าเทิมเท่าไหร่" ก็ยังจับคำว่า "ค่าเทอม" ได้
     */
    private function has(string $haystack, array $needles): bool
    {
        // รอบแรก: หาแบบตรงเป๊ะก่อน (เร็วที่สุด)
        foreach ($needles as $n) {
            if (mb_strpos($haystack, mb_strtolower($n)) !== false) { return true; }
        }
        // รอบสอง: ยอมให้พิมพ์ผิดตามความยาวคำ
        foreach ($needles as $n) {
            if (Fuzzy::contains($haystack, $n)) { return true; }
        }
        return false;
    }

    // ---------- คำตอบเฉพาะเรื่อง ----------

    private function answerContact(): array
    {
        $phone = setting('site_phone', '0-2244-5000');
        $email = setting('site_email', 'info@fooddusit.ac.th');
        $addr  = setting('site_address', '295 ถนนนครราชสีมา เขตดุสิต กรุงเทพมหานคร 10300');

        $reply = "ติดต่อโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ได้ที่\n"
               . "📍 {$addr}\n📞 {$phone}\n✉️ {$email}";
        return [$reply, [['title' => 'หน้าติดต่อเรา (มีแผนที่และแบบฟอร์ม)', 'url' => url('contact')]]];
    }

    private function answerAdmission(): array
    {
        $reply = "การรับสมัครนักศึกษาใหม่ดำเนินการผ่านระบบ TCAS ของมหาวิทยาลัยสวนดุสิตครับ\n"
               . "สามารถดูรอบการรับสมัคร คุณสมบัติ และกำหนดการได้ที่ลิงก์ด้านล่าง "
               . "หรือดูรายละเอียดคุณสมบัติผู้เรียนของแต่ละหลักสูตรในหน้าหลักสูตรได้เลยครับ";
        return [$reply, [
            ['title' => 'ระบบรับสมัคร TCAS มหาวิทยาลัยสวนดุสิต', 'url' => 'https://tcas.dusit.ac.th/faculty/'],
            ['title' => 'ดูหลักสูตรทั้งหมด', 'url' => url('curriculum')],
            ['title' => 'สอบถามเพิ่มเติม (ติดต่อเรา)', 'url' => url('contact')],
        ]];
    }

    private function answerScholarship(): array
    {
        $reply = "โรงเรียนการเรือนมีทุนการศึกษาหลายประเภทครับ ได้แก่\n"
               . "• กองทุนเงินให้กู้ยืมเพื่อการศึกษา (กยศ.)\n"
               . "• กองทุนเงินกู้ยืมที่ผูกกับรายได้ในอนาคต (กรอ.)\n"
               . "• ทุนเอมอร ปทุมมา (มูลนิธิสร้างฝัน) ทุนละ 20,000 บาท\n"
               . "ดูคุณสมบัติและรายละเอียดการสมัครได้ที่หน้าทุนการศึกษาของแต่ละหลักสูตรครับ";

        $links = [];
        foreach ($this->curriculumPages('scholarship') as $p) {
            $links[] = ['title' => $p['title'], 'url' => url($p['url'])];
        }
        return [$reply, $links];
    }

    private function answerCommittee(): array
    {
        $reply = "ดูรายชื่อคณะกรรมการประจำโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ได้ที่หน้านี้เลยครับ "
               . "(กดที่ชื่อแต่ละท่านเพื่อดูประวัติ/ผลงานเพิ่มเติมได้)";
        return [$reply, [['title' => 'คณะกรรมการประจำโรงเรียนการเรือน', 'url' => url('info/school-committee')]]];
    }

    private function answerDeans(): array
    {
        $reply = "ดูทำเนียบคณบดีโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ได้ที่หน้านี้เลยครับ "
               . "(กดที่การ์ดแต่ละท่านเพื่อดูประวัติ/ผลงานเพิ่มเติมได้)";
        return [$reply, [['title' => 'ทำเนียบคณบดี', 'url' => url('deans')]]];
    }

    private function answerStaff(string $msg): array
    {
        if ($this->has(mb_strtolower($msg), ['สนับสนุน', 'เจ้าหน้าที่'])) {
            return [
                'ดูรายชื่อบุคลากรสายสนับสนุนของโรงเรียนการเรือนได้ที่หน้านี้ครับ',
                [['title' => 'บุคลากรสายสนับสนุน', 'url' => url('info/staff-support')]],
            ];
        }
        return [
            'ดูรายชื่ออาจารย์/บุคลากรสายวิชาการของโรงเรียนการเรือนได้ที่หน้านี้ครับ (กดที่ชื่อแต่ละท่านเพื่อดูประวัติเพิ่มเติมได้)',
            [
                ['title' => 'บุคลากรสายวิชาการ', 'url' => url('info/staff-academic')],
                ['title' => 'บุคลากรสายสนับสนุน', 'url' => url('info/staff-support')],
            ],
        ];
    }

    private function answerDocumentCategory(string $path, string $label): array
    {
        return ["ดู{$label}ของโรงเรียนการเรือนได้ที่หน้านี้ครับ", [['title' => $label, 'url' => url($path)]]];
    }

    private function answerTuition(string $msg): array
    {
        // หาว่าถามถึงหลักสูตรไหน
        $matched = $this->matchCurriculum($msg);

        if ($matched) {
            $reply = "ค่าธรรมเนียมการศึกษาของ{$matched['name']} ดูรายละเอียดทั้งหมด (แยกตามจำนวนหน่วยกิตและรหัสนักศึกษา) ได้ในหน้าหลักสูตรครับ";
            return [$reply, [['title' => $matched['name'], 'url' => url('curriculum/' . $matched['slug'])]]];
        }

        $reply = "ค่าธรรมเนียมการศึกษาแตกต่างกันตามหลักสูตรและจำนวนหน่วยกิตที่ลงทะเบียนครับ\n"
               . "โดยทั่วไประดับปริญญาตรีมีค่ายืนยันสิทธิ์ 4,000 บาท ค่าขึ้นทะเบียน 7,000 บาท "
               . "และค่าบำรุงการศึกษาประมาณ 7,500–32,500 บาทต่อภาคเรียน (ตามหน่วยกิต) "
               . "รวมถึงค่าชุดอุปกรณ์และชุดปฏิบัติงาน 10,000 บาท (ชำระครั้งเดียว)\n"
               . "เลือกดูหลักสูตรที่สนใจเพื่อดูอัตราที่แน่นอนได้เลยครับ";
        return [$reply, $this->curriculumLinks()];
    }

    private function answerCurriculumList(string $msg): array
    {
        // ถามถึงหลักสูตรใดหลักสูตรหนึ่งโดยเฉพาะ
        if ($matched = $this->matchCurriculum($msg)) {
            $links = [['title' => 'ข้อมูลหลักสูตร', 'url' => url('curriculum/' . $matched['slug'])]];
            foreach (['courses' => 'รายวิชา', 'plan' => 'แผนการศึกษา', 'qualification' => 'คุณสมบัติผู้เรียน', 'career' => 'โอกาสในการประกอบอาชีพ'] as $k => $label) {
                if ($this->curriculumHas($matched['slug'], $k)) {
                    $links[] = ['title' => $label, 'url' => url('curriculum/' . $k . '/' . $matched['slug'])];
                }
            }
            return ["ข้อมูล{$matched['name']} ครับ — เลือกดูหัวข้อที่สนใจได้เลย", $links];
        }

        return ['โรงเรียนการเรือนเปิดสอนหลายสาขาวิชาครับ เลือกดูรายละเอียดได้เลย', $this->curriculumLinks()];
    }

    // ---------- ค้นหาทั่วเว็บ ----------

    private function answerBySearch(string $msg): array
    {
        $search = $this->searchEngine();
        $tokens = $search->tokenize($msg);
        $groups = $tokens ? $search->lookup($tokens) : [];

        $flat = [];
        foreach ($groups as $rows) {
            foreach ($rows as $r) { $flat[] = $r; }
        }
        // เรียงตามคะแนนความเกี่ยวข้อง
        usort($flat, fn($a, $b) => ($b['score'] ?? 0) <=> ($a['score'] ?? 0));

        // ค้นแบบตรงตัวไม่เจอ → ลองเทียบกับหน้าหลักสูตรแบบยอมให้สะกดผิด
        if (!$flat && $tokens) {
            $fuzzy = $this->fuzzyCurriculumMatch($tokens);
            if ($fuzzy) {
                return ['คุณหมายถึงเรื่องนี้หรือเปล่าครับ 🤔', $fuzzy];
            }
        }

        if (!$flat) {
            return [
                "ขออภัยครับ ผมยังไม่พบข้อมูลที่ตรงกับคำถามนี้ 🙏\n"
                . "ลองถามใหม่ด้วยคำสั้น ๆ เช่น \"หลักสูตร\" \"ค่าเทอม\" \"ทุนการศึกษา\" หรือติดต่อเจ้าหน้าที่โดยตรงก็ได้ครับ",
                [
                    ['title' => 'ค้นหาในเว็บไซต์', 'url' => url('search?q=' . rawurlencode($msg))],
                    ['title' => 'ติดต่อเรา', 'url' => url('contact')],
                ],
            ];
        }

        $links = [];
        foreach (array_slice($flat, 0, self::MAX_LINKS) as $r) {
            $links[] = ['title' => $r['title'], 'url' => url($r['url'])];
        }

        $first = $flat[0];
        $reply = 'พบข้อมูลที่เกี่ยวข้องครับ';
        if (!empty($first['excerpt'])) {
            $reply .= "\n\n" . mb_substr($first['excerpt'], 0, 220);
        }
        return [$reply, $links];
    }

    /**
     * เทียบคำค้นกับหน้าหลักสูตรแบบยอมให้สะกดผิด (ใช้เมื่อค้นแบบตรงตัวไม่เจอ)
     * @return array ลิงก์แนะนำ (ว่างถ้าไม่ใกล้เคียงพอ)
     */
    private function fuzzyCurriculumMatch(array $tokens): array
    {
        $scored = [];
        foreach ($this->curriculumIndex() as $item) {
            $hits = 0;
            foreach ($tokens as $tk) {
                // ชื่อหน้าให้น้ำหนักมากกว่าเนื้อหา (เนื้อหารวมชื่อรายวิชาทั้งหมด)
                if (Fuzzy::containsNormalized($item['title'], $tk))     { $hits += 3; }
                elseif (Fuzzy::containsNormalized($item['text'], $tk))  { $hits += 1; }
                elseif ($this->nearPhrase($item['text'], $tk))          { $hits += 1; } // พิมพ์ผิดหลายตัว
            }
            if ($hits > 0) {
                $scored[] = ['hits' => $hits, 'title' => $item['title'], 'url' => url($item['url'])];
            }
        }
        if (!$scored) { return []; }

        usort($scored, fn($a, $b) => $b['hits'] <=> $a['hits']);
        return array_map(
            fn($s) => ['title' => $s['title'], 'url' => $s['url']],
            array_slice($scored, 0, self::MAX_LINKS)
        );
    }

    /**
     * เนื้อหามีวลีที่สะกดใกล้เคียงคำค้นไหม (รองรับพิมพ์ผิดหลายตัว)
     * เทียบเฉพาะวลีที่ความยาวใกล้เคียงกัน จึงเร็วแม้เนื้อหายาว
     */
    private function nearPhrase(string $text, string $token): bool
    {
        $tk    = Fuzzy::normalize($token);
        $tkLen = mb_strlen($tk);
        if ($tkLen < 4) { return false; } // คำสั้นเกินไป เสี่ยงจับผิดคำ

        $tol = min(2, intdiv($tkLen, 4)); // ยาว 4–7 ตัว = ผิดได้ 1, 8 ตัวขึ้นไป = ผิดได้ 2

        foreach (preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $phrase) {
            $p = Fuzzy::normalize($phrase);
            $pLen = mb_strlen($p);
            if ($pLen === 0) { continue; }

            // วลีสั้น: เทียบทั้งวลี
            if (abs($pLen - $tkLen) <= $tol && Fuzzy::distance($p, $tk, $tol) <= $tol) {
                return true;
            }
            // วลียาว (เช่นชื่อวิชา): เทียบเฉพาะช่วงย่อยที่ยาวเท่าคำค้น
            if ($pLen > $tkLen + $tol) {
                for ($i = 0; $i <= $pLen - $tkLen; $i++) {
                    if (Fuzzy::distance(mb_substr($p, $i, $tkLen), $tk, $tol) <= $tol) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /** โหลดตัวค้นหา (ใช้ตรรกะเดียวกับหน้า /search) */
    private function searchEngine(): SearchController
    {
        require_once APP_PATH . '/controllers/SearchController.php';
        return new SearchController();
    }

    // ---------- ตัวช่วยเกี่ยวกับหลักสูตร ----------

    /** รายการหน้าหลักสูตรทั้งหมดจาก CurriculumController */
    private function curriculumIndex(): array
    {
        require_once APP_PATH . '/controllers/CurriculumController.php';
        return CurriculumController::searchIndex();
    }

    /** หน้าหลักสูตรหลัก (ไม่รวมหน้าย่อย) → ลิงก์แนะนำ */
    private function curriculumLinks(): array
    {
        $links = [];
        foreach ($this->curriculumIndex() as $item) {
            // หน้าหลัก = curriculum/{slug} (ไม่มี path ย่อย)
            if (substr_count(trim($item['url'], '/'), '/') !== 1) { continue; }
            $links[] = ['title' => $item['title'], 'url' => url($item['url'])];
        }
        return $links;
    }

    /** หน้าย่อยประเภทที่ระบุของทุกหลักสูตร เช่น 'scholarship' */
    private function curriculumPages(string $type): array
    {
        $out = [];
        foreach ($this->curriculumIndex() as $item) {
            if (str_starts_with(trim($item['url'], '/'), 'curriculum/' . $type . '/')) {
                $out[] = $item;
            }
        }
        return $out;
    }

    /** หลักสูตรนี้มีหน้าย่อยประเภทที่ระบุหรือไม่ */
    private function curriculumHas(string $slug, string $type): bool
    {
        foreach ($this->curriculumIndex() as $item) {
            if (trim($item['url'], '/') === 'curriculum/' . $type . '/' . $slug) { return true; }
        }
        return false;
    }

    /** เดาว่าผู้ใช้ถามถึงหลักสูตรไหน (จับคำสำคัญในชื่อหลักสูตร) */
    private function matchCurriculum(string $msg): ?array
    {
        $m = mb_strtolower($msg);
        $keywords = [
            'culinary-tech-service'  => ['เทคโนโลยีการประกอบอาหาร', 'ประกอบอาหารและการบริการ', 'culinary'],
            'home-economics'         => ['คหกรรม', 'home economics'],
            'food-innovation-2568'   => ['นวัตกรรม', 'ผู้ประกอบการอาหาร', 'innovation'],
            'nutrition-health-2568'  => ['โภชนาการ', 'ชะลอวัย', 'สุขภาพ', 'nutrition', 'anti-aging'],
            'bsc-culinary-tech'      => ['ปริญญาโท', 'มหาบัณฑิต', 'master'],
        ];

        foreach ($keywords as $slug => $words) {
            if ($this->has($m, $words)) {
                foreach ($this->curriculumIndex() as $item) {
                    if (trim($item['url'], '/') === 'curriculum/' . $slug) {
                        return ['slug' => $slug, 'name' => $item['title']];
                    }
                }
            }
        }
        return null;
    }

    // ---------- FAQ ----------

    /** ค้นคำถามที่พบบ่อยที่ตรงกับข้อความมากที่สุด */
    private function findFaq(string $msg): ?array
    {
        try {
            $rows = (new Database())->query(
                "SELECT question, answer FROM faq WHERE status='active' AND deleted_at IS NULL"
            )->all();
        } catch (Throwable $e) {
            return null;
        }

        $best = null;
        $bestScore = 0;
        $words = array_filter(preg_split('/\s+/u', mb_strtolower($msg)) ?: [], fn($w) => mb_strlen($w) >= 3);

        foreach ($rows as $r) {
            $q = mb_strtolower($r['question']);
            $a = mb_strtolower($r['answer']);
            $score = 0;
            foreach ($words as $w) {
                if (mb_strpos($q, $w) !== false)        { $score += 2; }   // ตรงในคำถาม
                elseif (mb_strpos($a, $w) !== false)    { $score += 1; }   // ตรงในคำตอบ
                elseif (Fuzzy::contains($q, $w))        { $score += 1; }   // สะกดผิดเล็กน้อยในคำถาม
            }
            if ($score > $bestScore) { $bestScore = $score; $best = $r; }
        }
        // ต้องตรงพอสมควรจึงจะถือว่าใช่
        return $bestScore >= 2 ? $best : null;
    }
}
