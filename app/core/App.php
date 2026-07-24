<?php
/**
 * ============================================================
 *  App (Core / Router)
 * ------------------------------------------------------------
 *  ตัวจัดเส้นทาง (Router) แบบ Friendly URL
 *  รูปแบบ URL:  /controller/method/param1/param2 ...
 *  ตัวอย่าง:
 *     /                      -> HomeController@index
 *     /news                  -> NewsController@index
 *     /news/detail/5         -> NewsController@detail(5)
 *     /admin/news            -> Admin\NewsController@index
 *     /admin/news/edit/3     -> Admin\NewsController@edit(3)
 * ============================================================
 */

class App
{
    private string $controllerName = DEFAULT_CONTROLLER;
    private string $method = DEFAULT_METHOD;
    private array  $params = [];
    private bool   $isAdmin = false;

    public function run(): void
    {
        $url = $this->parseUrl();

        // ---------- ตรวจว่าเป็นเส้นทางฝั่ง admin หรือไม่ ----------
        if (!empty($url) && strtolower($url[0]) === 'admin') {
            $this->isAdmin = true;
            array_shift($url); // ตัด 'admin' ออก
            $controllerDir = APP_PATH . '/controllers/admin/';
            $namespacePrefix = 'Admin\\';
            $this->controllerName = 'DashboardController';
        } else {
            $controllerDir = APP_PATH . '/controllers/';
            $namespacePrefix = '';
        }

        // ---------- หา Controller ----------
        if (!empty($url[0])) {
            $candidate = ucfirst($url[0]) . 'Controller';
            if (file_exists($controllerDir . $candidate . '.php')) {
                $this->controllerName = $candidate;
                array_shift($url);
            } elseif ($this->isAdmin) {
                // ฝั่ง admin: ถ้าไม่ตรง controller ให้ถือว่าเป็น method ของ Dashboard
                $this->controllerName = 'DashboardController';
            }
        }

        $controllerFile = $controllerDir . $this->controllerName . '.php';
        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }
        require_once $controllerFile;

        $className = $namespacePrefix . $this->controllerName;
        if (!class_exists($className)) {
            // เผื่อ controller ไม่ได้ประกาศ namespace
            $className = $this->controllerName;
        }
        $controller = new $className();

        // ---------- หา Method ----------
        if (!empty($url[0]) && method_exists($controller, $url[0])) {
            $this->method = $url[0];
            array_shift($url);
        }

        if (!method_exists($controller, $this->method)) {
            $this->notFound();
            return;
        }

        // ---------- พารามิเตอร์ที่เหลือ ----------
        $this->params = $url ? array_values($url) : [];

        // เรียกใช้งาน controller@method พร้อมพารามิเตอร์
        call_user_func_array([$controller, $this->method], $this->params);
    }

    /**
     * แยก URL ออกเป็นส่วน ๆ จากพารามิเตอร์ 'url' ที่ .htaccess ส่งมา
     */
    private function parseUrl(): array
    {
        if (!isset($_GET['url'])) {
            return [];
        }
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return explode('/', $url);
    }

    /**
     * แสดงหน้า 404
     */
    private function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404', ['title' => 'ไม่พบหน้าที่ต้องการ'], $this->isAdmin ? null : 'public');
    }
}
