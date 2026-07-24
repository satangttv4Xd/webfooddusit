<?php
/**
 * RobotsController — สร้าง robots.txt แบบ dynamic (ชี้ sitemap ตาม BASE_URL อัตโนมัติ)
 */
class RobotsController extends Controller
{
    public function index(): void
    {
        header('Content-Type: text/plain; charset=utf-8');

        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            'Sitemap: ' . BASE_URL . 'sitemap.xml',
        ];
        echo implode("\n", $lines);
        exit;
    }
}
