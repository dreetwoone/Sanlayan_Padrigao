<?php
require_once BASE_PATH . '/app/models/usermodel.php';

class AuthController extends Controller {

    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    public function login(): void {
        if (isset($_SESSION['user_id'])) { $this->redirect('dashboard'); }

        $error = '';
        $prefill = htmlspecialchars($_GET['user'] ?? '');
        $registered = isset($_GET['registered']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $error = 'Please enter your username and password.';
            } else {
                $user = $this->users->findByUsernameOrEmail($username);
                if ($user && password_verify($password, $user['password'])) {
                    if ($user['status'] === 'banned')   { $error = 'Your account has been suspended.'; }
                    elseif ($user['status'] === 'pending') { $error = 'Your account is pending approval.'; }
                    else {
                        $_SESSION['user_id']   = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_role'] = $user['role'];
                        $this->redirect($user['role'] === 'admin' ? 'admin' : 'dashboard');
                    }
                } else {
                    $error = 'Invalid username or password.';
                    $prefill = htmlspecialchars($username);
                }
            }
        }

        $this->view('auth/login', [
            'error'          => $error,
            'prefillUsername'=> $prefill,
            'justRegistered' => $registered,
        ]);
    }

    public function register(): void {
        if (isset($_SESSION['user_id'])) { $this->redirect('dashboard'); }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password))    { $error = 'Please fill in all fields.'; }
            elseif (strlen($username) < 3)               { $error = 'Username must be at least 3 characters.'; }
            elseif (strlen($password) < 6)               { $error = 'Password must be at least 6 characters.'; }
            elseif ($this->users->nameExists($username)) { $error = 'That username is already taken.'; }
            else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $email  = strtolower(str_replace(' ', '', $username)) . '@profit.local';
                $this->users->create($username, $email, $hashed);
                $this->redirect('auth/login?registered=1&user=' . urlencode($username));
            }
        }
        $this->view('auth/register', ['error' => $error]);
    }

    public function logout(): void {
        $_SESSION = []; session_destroy();
        $this->redirect('auth/login');
    }
}