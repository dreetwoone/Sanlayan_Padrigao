<?php
class Controller {

    protected function view(string $view, array $data = []): void {
        // extract() turns ['name'=>'Paul'] into $name = 'Paul'
        extract($data);

        $file = BASE_PATH . '/app/views/' . $view . '.php';
        if (!file_exists($file)) {
            die("View not found: <strong>" . htmlspecialchars($view) . "</strong>");
        }
        require_once $file;
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireLogin(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin(): void {
        $this->requireLogin();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->redirect('dashboard');
        }
    }
}