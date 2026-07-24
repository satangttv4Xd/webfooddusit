<?php
/**
 * SitemapController — สร้าง sitemap.xml แบบ dynamic
 * รวมหน้าคงที่ + เนื้อหาจากฐานข้อมูล (ข่าว/หลักสูตร/กิจกรรม/แกลเลอรี)
 */
class SitemapController extends Controller
{
    public function index(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $urls = [];

        // ---------- หน้าหลัก (static) ----------
        $static = [
            ''             => '1.0',
            'about'        => '0.8',
            'history'      => '0.7',
            'orgstructure' => '0.6',
            'deans'        => '0.6',
            'curriculum'   => '0.8',
            'programs'     => '0.8',
            'journal'      => '0.6',
            'sdugoal'      => '0.5',
            'news'         => '0.8',
            'events'       => '0.7',
            'gallery'      => '0.6',
            'downloads'    => '0.6',
            'contact'      => '0.7',
        ];
        foreach ($static as $path => $priority) {
            $urls[] = ['loc' => BASE_URL . $path, 'priority' => $priority, 'changefreq' => 'weekly'];
        }

        // ---------- หลักสูตร (curriculum) ----------
        $curriculumSlugs = [
            'bsc-culinary-tech', 'culinary-tech-service', 'home-economics',
            'food-innovation-2568', 'nutrition-health-2568', 'food-industry',
            'nutrition-fitness', 'food-business-culinary-arts-2569',
        ];
        foreach ($curriculumSlugs as $s) {
            $urls[] = ['loc' => BASE_URL . 'curriculum/' . $s, 'priority' => '0.6', 'changefreq' => 'monthly'];
        }

        // ---------- เนื้อหาจากฐานข้อมูล ----------
        $db = new Database();
        $this->collect($db, $urls, "SELECT slug, updated_at FROM news WHERE status='published' AND deleted_at IS NULL", 'news/detail/', '0.7');
        $this->collect($db, $urls, "SELECT slug, updated_at FROM programs WHERE status='active' AND deleted_at IS NULL", 'programs/detail/', '0.7');
        $this->collect($db, $urls, "SELECT slug, updated_at FROM events WHERE status='active' AND deleted_at IS NULL", 'events/detail/', '0.6');
        $this->collect($db, $urls, "SELECT slug, updated_at FROM gallery_albums", 'gallery/album/', '0.5');

        // ---------- สร้าง XML ----------
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            if (!empty($u['lastmod'])) {
                echo '    <lastmod>' . htmlspecialchars($u['lastmod'], ENT_XML1) . "</lastmod>\n";
            }
            echo '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            echo '    <priority>' . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
        exit;
    }

    /** ดึง slug จาก DB มาต่อ URL (ข้ามถ้าตาราง/คอลัมน์มีปัญหา) */
    private function collect(Database $db, array &$urls, string $sql, string $prefix, string $priority): void
    {
        try {
            foreach ($db->query($sql)->all() as $row) {
                if (empty($row['slug'])) continue;
                $urls[] = [
                    'loc'        => BASE_URL . $prefix . $row['slug'],
                    'lastmod'    => !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at'])) : null,
                    'priority'   => $priority,
                    'changefreq' => 'weekly',
                ];
            }
        } catch (\Throwable $e) {
            // ข้ามเงียบ ๆ ถ้า query ล้มเหลว
        }
    }
}
