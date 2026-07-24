<?php
/**
 * JournalController — วารสารวัฒนธรรมอาหารไทย (Journal of Thai Food Culture)
 * หน้าแสดงรายการฉบับวารสาร (ตอนนี้เป็นข้อมูลตัวอย่าง / mock data)
 */
class JournalController extends Controller
{
    public function index(): void
    {
        $desc = 'Journal of Thai Food Culture is Humanities and Social Sciences Journal '
              . 'which publish original article, review article, and book review. '
              . 'The following areas are covered in the Journal are home economics, '
              . 'culinary technology and services, food product development, '
              . 'food service business, gastronomy and related fields.';

        // ข้อมูลตัวอย่าง (mock) — ภายหลังเชื่อมฐานข้อมูล/ThaiJo ได้
        $issues = [
            ['vol' => 7, 'no' => 1, 'year' => 2025, 'period' => 'January – June 2025',    'desc' => $desc, 'link' => '#'],
            ['vol' => 7, 'no' => 2, 'year' => 2025, 'period' => 'July – December 2025',    'desc' => $desc, 'link' => '#'],
            ['vol' => 6, 'no' => 1, 'year' => 2024, 'period' => 'January – June 2024',     'desc' => $desc, 'link' => '#'],
            ['vol' => 6, 'no' => 2, 'year' => 2024, 'period' => 'July – December 2024',    'desc' => $desc, 'link' => '#'],
            ['vol' => 5, 'no' => 1, 'year' => 2023, 'period' => 'January – June 2023',     'desc' => $desc, 'link' => '#'],
            ['vol' => 5, 'no' => 2, 'year' => 2023, 'period' => 'July – December 2023',    'desc' => $desc, 'link' => '#'],
        ];

        $this->view('public/journal', [
            'title'           => 'วารสารวัฒนธรรมอาหารไทย',
            'metaDescription' => 'วารสารวัฒนธรรมอาหารไทย (Journal of Thai Food Culture) วารสารวิชาการด้านมนุษยศาสตร์และสังคมศาสตร์ โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ISSN 2774-0544',
            'issues'          => $issues,
        ]);
    }
}
