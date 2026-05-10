<?php
class Auth extends Controller {

    public function index() {
        header('Location: ' . URLROOT . '/auth/login');
        exit;
    }

    public function login() {
        // Already logged in as admin
        if (!empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }

        $data = [
            'title'    => 'Login Admin – Neucat',
            'error'    => '',
            'username' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS_HASH)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user']      = $username;
                header('Location: ' . URLROOT . '/admin');
                exit;
            } else {
                $data['error']    = 'Usuário ou senha incorretos.';
                $data['username'] = htmlspecialchars($username);
            }
        }

        $this->view('auth/login', $data);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
        exit;
    }
}
