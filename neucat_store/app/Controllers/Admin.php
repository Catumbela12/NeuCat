<?php
class Admin extends Controller {
    private $productModel;
    private $customerModel;
    private $categoryModel;
    private $orderModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdminLogin();
        $this->productModel  = $this->model('Product');
        $this->customerModel = $this->model('Customer');
        $this->categoryModel = $this->model('Category');
        $this->orderModel    = $this->model('Order');
    }

    private function requireAdminLogin() {
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index() {
        $products  = $this->productModel->getProducts();
        $customers = $this->customerModel->getAll();
        $orders    = $this->orderModel->getAllOrders();
        
        $data = [
            'title'     => 'Painel Admin – Neucat',
            'products'  => $products,
            'customers' => $customers,
            'orders'    => $orders
        ];
        $this->view('admin/dashboard', $data);
    }

    // --- Product Management ---

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id'      => !empty($_POST['category_id']) ? trim($_POST['category_id']) : null,
                'name'             => trim($_POST['name']),
                'description'      => trim($_POST['description']),
                'price'            => trim($_POST['price']),
                'promotional_price'=> !empty($_POST['promotional_price']) ? trim($_POST['promotional_price']) : null,
                'stock_quantity'   => !empty($_POST['stock_quantity']) ? trim($_POST['stock_quantity']) : 0,
                'image'            => 'placeholder.webp'
            ];

            // Primary Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir    = APPROOT . "/../public/img/";
                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $new_filename  = uniqid() . '.' . $file_extension;
                $target_file   = $target_dir . $new_filename;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $data['image'] = $new_filename;
                }
            }

            $productId = $this->productModel->addProduct($data);

            if ($productId) {
                // Handle Multiple Images
                if (!empty($_FILES['gallery_images']['name'][0])) {
                    $total = count($_FILES['gallery_images']['name']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_FILES['gallery_images']['error'][$i] == 0) {
                            $file_ext = pathinfo($_FILES["gallery_images"]["name"][$i], PATHINFO_EXTENSION);
                            $new_img_name = uniqid() . '_' . $i . '.' . $file_ext;
                            $target_file = APPROOT . "/../public/img/" . $new_img_name;
                            if (move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$i], $target_file)) {
                                $this->productModel->addProductImage($productId, $new_img_name);
                            }
                        }
                    }
                }
                header('Location: ' . URLROOT . '/admin');
            } else {
                die('Erro ao adicionar produto.');
            }
        } else {
            $categories = $this->categoryModel->getCategories();
            $data = [
                'title'      => 'Adicionar Produto – Neucat',
                'categories' => $categories,
                'product'    => (object)[
                    'id' => '', 'category_id' => '', 'name' => '', 'description' => '',
                    'price' => '', 'promotional_price' => '', 'stock_quantity' => '', 'image' => ''
                ]
            ];
            $this->view('admin/product_form', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id'               => $id,
                'category_id'      => !empty($_POST['category_id']) ? trim($_POST['category_id']) : null,
                'name'             => trim($_POST['name']),
                'description'      => trim($_POST['description']),
                'price'            => trim($_POST['price']),
                'promotional_price'=> !empty($_POST['promotional_price']) ? trim($_POST['promotional_price']) : null,
                'stock_quantity'   => !empty($_POST['stock_quantity']) ? trim($_POST['stock_quantity']) : 0,
                'image'            => trim($_POST['old_image'])
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir    = APPROOT . "/../public/img/";
                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $new_filename  = uniqid() . '.' . $file_extension;
                $target_file   = $target_dir . $new_filename;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $data['image'] = $new_filename;
                }
            }

            if ($this->productModel->updateProduct($data)) {
                // Handle Multiple Images
                if (!empty($_FILES['gallery_images']['name'][0])) {
                    $total = count($_FILES['gallery_images']['name']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_FILES['gallery_images']['error'][$i] == 0) {
                            $file_ext = pathinfo($_FILES["gallery_images"]["name"][$i], PATHINFO_EXTENSION);
                            $new_img_name = uniqid() . '_' . $i . '.' . $file_ext;
                            $target_file = APPROOT . "/../public/img/" . $new_img_name;
                            if (move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$i], $target_file)) {
                                $this->productModel->addProductImage($id, $new_img_name);
                            }
                        }
                    }
                }
                header('Location: ' . URLROOT . '/admin');
            } else {
                die('Erro ao atualizar produto.');
            }
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getCategories();
            $gallery = $this->productModel->getProductImages($id);
            
            $data = [
                'title'      => 'Editar Produto – Neucat',
                'product'    => $product,
                'categories' => $categories,
                'gallery'    => $gallery
            ];
            $this->view('admin/product_form', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->deleteProduct($id)) {
                header('Location: ' . URLROOT . '/admin');
            } else {
                die('Erro ao deletar produto.');
            }
        }
    }

    public function deleteImage($imageId, $productId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->productModel->deleteProductImage($imageId);
            header('Location: ' . URLROOT . '/admin/edit/' . $productId);
        }
    }

    // --- Category Management ---

    public function categories() {
        $categories = $this->categoryModel->getCategories();
        $data = [
            'title'      => 'Categorias – Neucat',
            'categories' => $categories
        ];
        $this->view('admin/categories', $data);
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            if (!empty($name)) {
                $this->categoryModel->addCategory($name);
            }
            header('Location: ' . URLROOT . '/admin/categories');
        }
    }

    public function editCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            if (!empty($name)) {
                $this->categoryModel->updateCategory($id, $name);
            }
            header('Location: ' . URLROOT . '/admin/categories');
        }
    }

    public function deleteCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->categoryModel->deleteCategory($id);
            header('Location: ' . URLROOT . '/admin/categories');
        }
    }

    // --- Orders Management ---
    public function updateOrderStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = trim($_POST['status']);
            $this->orderModel->updateOrderStatus($id, $status);
            header('Location: ' . URLROOT . '/admin');
        }
    }
}
