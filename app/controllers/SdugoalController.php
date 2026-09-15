<?php
/**
 * SdugoalController — SDU Goal : 2568–2571 | The Power of SDU
 * แสดง/ให้ดาวน์โหลดไฟล์ PDF จากโฟลเดอร์ /pdf ของโปรเจกต์
 */
class SdugoalController extends Controller
{
    private string $pdfDir;

    public function __construct()
    {
        $this->pdfDir = ROOT_PATH . '/pdf';
    }

    public function index(): void
    {
        $files = [];
        foreach (glob($this->pdfDir . '/*.pdf') ?: [] as $path) {
            $name = basename($path);
            // แสดงเฉพาะเอกสาร SDU Goal เท่านั้น (ไฟล์หลักสูตรอื่นในโฟลเดอร์ไม่นำมาแสดง)
            if (stripos($name, 'sdu') === false && stripos($name, 'goal') === false) {
                continue;
            }
            $files[] = [
                'name'  => $name,
                'size'  => $this->humanSize(filesize($path)),
            ];
        }

        $this->view('public/sdugoal', [
            'title'           => 'SDU Goal : 2568–2571 | The Power of SDU',
            'metaDescription' => 'SDU Goal : 2568–2571 | The Power of SDU เป้าหมายและทิศทางการพัฒนามหาวิทยาลัยสวนดุสิต ดาวน์โหลดเอกสารได้',
            'files'           => $files,
        ]);
    }

    /** เปิดดู PDF ในเบราว์เซอร์ (inline) — ใช้กับ iframe พรีวิว */
    public function open(string $file = ''): void
    {
        $this->stream($file, false);
    }

    /** ดาวน์โหลด PDF (attachment) */
    public function download(string $file = ''): void
    {
        $this->stream($file, true);
    }

    /** ส่งไฟล์ PDF อย่างปลอดภัย */
    private function stream(string $file, bool $attachment): void
    {
        $file = basename($file); // กัน path traversal
        $path = $this->pdfDir . '/' . $file;

        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'pdf' || !is_file($path)) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบไฟล์']);
            return;
        }

        $disposition = $attachment ? 'attachment' : 'inline';
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="' . $file . '"');
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: public, max-age=3600');
        readfile($path);
        exit;
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return number_format($bytes / 1024, 0) . ' KB';
        return $bytes . ' B';
    }
}
