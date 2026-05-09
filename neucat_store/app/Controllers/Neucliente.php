<?php
class Neucliente extends Controller {
    private $customerModel;

    public function __construct() {
        $this->customerModel = $this->model('Customer');
    }

    // ── Página de cadastro ───────────────────────────────────────────────────
    public function register() {
        if (!empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT);
            exit;
        }

        $data = [
            'title'      => 'Cadastro NeuCustomer – Neucat',
            'error'      => '',
            'success'    => '',
            'name'       => '',
            'email'      => '',
            'phone'      => '',
            'birth_date' => '',
            'gender'     => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name       = trim($_POST['name']             ?? '');
            $email      = trim($_POST['email']            ?? '');
            $phone      = trim($_POST['phone']            ?? '');
            $birth_date = trim($_POST['birth_date']       ?? '');
            $gender     = trim($_POST['gender']           ?? '');
            $password   = trim($_POST['password']         ?? '');
            $confirm    = trim($_POST['confirm_password'] ?? '');

            if (empty($name)) {
                $data['error'] = 'O nome é obrigatório.';
            } elseif (empty($email) && empty($phone)) {
                $data['error'] = 'Informe pelo menos um e-mail ou telefone.';
            } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'E-mail inválido.';
            } elseif (empty($birth_date)) {
                $data['error'] = 'A data de nascimento é obrigatória.';
            } elseif (empty($gender)) {
                $data['error'] = 'Selecione o sexo.';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'A senha deve ter pelo menos 6 caracteres.';
            } elseif ($password !== $confirm) {
                $data['error'] = 'As senhas não coincidem.';
            } elseif ($this->customerModel->emailOrPhoneExists($email, $phone)) {
                $data['error'] = 'Este e-mail ou telefone já está cadastrado.';
            } else {
                $customerData = [
                    'name'       => $name,
                    'email'      => $email,
                    'phone'      => $phone,
                    'birth_date' => $birth_date,
                    'gender'     => $gender,
                    'password'   => password_hash($password, PASSWORD_DEFAULT)
                ];
                if ($this->customerModel->register($customerData)) {
                    $data['success'] = 'Cadastro realizado com sucesso! Faça login para acessar os preços exclusivos.';
                } else {
                    $data['error'] = 'Erro ao criar conta. Tente novamente.';
                }
            }

            $data['name']       = htmlspecialchars($name);
            $data['email']      = htmlspecialchars($email);
            $data['phone']      = htmlspecialchars($phone);
            $data['birth_date'] = htmlspecialchars($birth_date);
            $data['gender']     = htmlspecialchars($gender);
        }

        $this->view('neucliente/register', $data);
    }

    // ── Página de login ──────────────────────────────────────────────────────
    public function login() {
        if (!empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT);
            exit;
        }

        $data = [
            'title'    => 'Login NeuCustomer – Neucat',
            'error'    => '',
            'login_id' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login_id = trim($_POST['login_id'] ?? '');
            $password = trim($_POST['password']  ?? '');

            $customer = null;
            if (filter_var($login_id, FILTER_VALIDATE_EMAIL)) {
                $customer = $this->customerModel->findByEmail($login_id);
            } else {
                $customer = $this->customerModel->findByPhone($login_id);
            }

            if ($customer && password_verify($password, $customer->password)) {
                $_SESSION['customer_logged_in'] = true;
                $_SESSION['customer_id']        = $customer->id;
                $_SESSION['customer_name']      = $customer->name;
                header('Location: ' . URLROOT);
                exit;
            } else {
                $data['error']    = 'Credenciais inválidas. Verifique seu e-mail/telefone e senha.';
                $data['login_id'] = htmlspecialchars($login_id);
            }
        }

        $this->view('neucliente/login', $data);
    }

    // ── Perfil do Usuário ───────────────────────────────────────────────────
    public function profile() {
        if (empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT . '/neucliente/login');
            exit;
        }

        $customer = $this->customerModel->getCustomerById($_SESSION['customer_id']);
        $data = [
            'title'    => 'Meu Perfil – Neucat',
            'customer' => $customer,
            'success'  => isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : ''
        ];
        unset($_SESSION['flash_message']);

        $this->view('neucliente/profile', $data);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['customer_logged_in'])) {
            $data = [
                'id'           => $_SESSION['customer_id'],
                'name'         => trim($_POST['name'] ?? ''),
                'phone'        => trim($_POST['phone'] ?? ''),
                'birth_date'   => trim($_POST['birth_date'] ?? ''),
                'gender'       => trim($_POST['gender'] ?? ''),
                'address_line' => trim($_POST['address_line'] ?? ''),
                'city'         => trim($_POST['city'] ?? ''),
                'state'        => trim($_POST['state'] ?? ''),
                'zip'          => trim($_POST['zip'] ?? '')
            ];

            $this->customerModel->updateProfile($data);
            $_SESSION['customer_name'] = $data['name'];
            $_SESSION['flash_message'] = 'Perfil atualizado com sucesso!';
            header('Location: ' . URLROOT . '/neucliente/profile');
            exit;
        }
    }

    // ── Meus Pedidos ────────────────────────────────────────────────────────
    public function orders() {
        if (empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT . '/neucliente/login');
            exit;
        }

        $orderModel = $this->model('Order');
        $orders = $orderModel->getOrdersByUserId($_SESSION['customer_id']);
        
        foreach ($orders as $order) {
            $order->items = $orderModel->getOrderItems($order->id);
        }

        $data = [
            'title'  => 'Meus Pedidos – Neucat',
            'orders' => $orders
        ];

        $this->view('neucliente/orders', $data);
    }

    // ── Meus Favoritos ──────────────────────────────────────────────────────
    public function favorites() {
        if (empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT . '/neucliente/login');
            exit;
        }

        $productModel = $this->model('Product');
        $favorites = $productModel->getFavorites($_SESSION['customer_id']);

        $data = [
            'title'     => 'Meus Favoritos – Neucat',
            'favorites' => $favorites
        ];

        $this->view('neucliente/favorites', $data);
    }

    // ── Logout ───────────────────────────────────────────────────────────────
    public function logout() {
        unset(
            $_SESSION['customer_logged_in'],
            $_SESSION['customer_id'],
            $_SESSION['customer_name']
        );
        header('Location: ' . URLROOT);
        exit;
    }
}
