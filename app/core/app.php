<?php
class App {

    public function __construct() {
        session_start();
        $this->route();
    }

    private function route(): void {

        $url = '';

        if (!empty($_GET['url'])) {
            $url = $_GET['url'];

        } else {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

            $basePath = rtrim(dirname($scriptName), '/');
            $url = str_replace($basePath, '', $requestUri);
            $url = ltrim($url, '/');

            if (strpos($url, '?') !== false) {
                $url = substr($url, 0, strpos($url, '?'));
            }

            $url = str_replace('index.php', '', $url);
            $url = trim($url, '/');
        }

        $parts = $url ? array_values(array_filter(explode('/', $url))) : [];

        $controllerName = !empty($parts[0])
            ? ucfirst(strtolower($parts[0])) . 'Controller'
            : 'AuthController';

        $file = BASE_PATH . '/app/controllers/' . $controllerName . '.php';

        if (!file_exists($file)) {
            $this->notFound("Controller not found: $controllerName");
            return;
        }

        require_once $file;
        $controller = new $controllerName();

        $method = !empty($parts[1]) ? strtolower($parts[1]) : 'index';

        if ($controllerName === 'AuthController' && $method === 'index') {
            $method = 'login';
        }

        if (!method_exists($controller, $method)) {
            $this->notFound("Method not found: $controllerName::$method()");
            return;
        }

        $params = array_slice($parts, 2);
        call_user_func_array([$controller, $method], $params);
    }

    private function notFound(string $detail = ''): void {
        http_response_code(404);
        echo "
        <div style='font-family:sans-serif;background:#111;color:#e8e8e8;
                    padding:60px 40px;text-align:center;min-height:100vh'>
            <h2 style='color:#ff6b2b'>404 — Page Not Found</h2>
            <p style='color:#7a7a7a'>$detail</p>
            <a href='".BASE_URL."/auth/login' style='color:#0cd13e;font-size:14px'>← Go to Login</a>
        </div>";
    }
}