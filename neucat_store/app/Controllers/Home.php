<?php
class Home extends Controller {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $reviewModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->orderModel = $this->model('Order');
        $this->reviewModel = $this->model('Review');
    }

    public function index() {
        $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
        $search = isset($_GET['q']) ? trim($_GET['q']) : null;
        
        $products = $this->productModel->getProducts($categoryId, $search);
        $categories = $this->categoryModel->getCategories();
        $bestsellers = $this->productModel->getBestSellers();
        $newArrivals = $this->productModel->getNewArrivals();

        $data = [
            'title' => 'Neucat - Elegância em Preto e Dourado',
            'products' => $products,
            'categories' => $categories,
            'bestsellers' => $bestsellers,
            'newArrivals' => $newArrivals,
            'categoryId' => $categoryId,
            'search' => $search
        ];
        $this->view('home/index', $data);
    }

    public function product($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . URLROOT);
            exit;
        }

        $gallery = $this->productModel->getProductImages($id);
        $reviews = $this->reviewModel->getReviewsByProductId($id);
        $avgRating = $this->reviewModel->getAverageRating($id);

        $isFavorite = false;
        if (!empty($_SESSION['customer_logged_in'])) {
            $isFavorite = $this->productModel->isFavorite($_SESSION['customer_id'], $id);
        }

        $data = [
            'title' => $product->name . ' - Neucat',
            'product' => $product,
            'gallery' => $gallery,
            'reviews' => $reviews,
            'avgRating' => $avgRating,
            'isFavorite' => $isFavorite
        ];

        $this->view('home/product', $data);
    }

    // Toggle Favorite
    public function toggleFavorite($productId) {
        if (empty($_SESSION['customer_logged_in'])) {
            header('Location: ' . URLROOT . '/neucliente/login');
            exit;
        }

        $userId = $_SESSION['customer_id'];
        if ($this->productModel->isFavorite($userId, $productId)) {
            $this->productModel->removeFavorite($userId, $productId);
        } else {
            $this->productModel->addFavorite($userId, $productId);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // Search Autocomplete Endpoint
    public function search_autocomplete() {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (empty($q)) {
            echo json_encode([]);
            exit;
        }

        $products = $this->productModel->getProducts(null, $q);
        $results = [];
        foreach ($products as $p) {
            $results[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->promotional_price ? $p->promotional_price : $p->price,
                'image' => URLROOT . '/img/' . $p->image
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    // Store Review Submission
    public function review() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['customer_logged_in'])) {
            $data = [
                'user_id' => $_SESSION['customer_id'],
                'product_id' => !empty($_POST['product_id']) ? $_POST['product_id'] : null,
                'rating' => $_POST['rating'],
                'comment' => trim($_POST['comment'])
            ];
            $this->reviewModel->addReview($data);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // Checkout / Direct Purchase Mock
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'] ?? 1;
            $product = $this->productModel->getProductById($productId);

            if ($product && $product->stock_quantity >= $quantity) {
                $price = $product->promotional_price ? $product->promotional_price : $product->price;
                $totalPrice = $price * $quantity;

                $orderData = [
                    'user_id' => !empty($_SESSION['customer_logged_in']) ? $_SESSION['customer_id'] : null,
                    'guest_email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                    'total_price' => $totalPrice
                ];

                $orderId = $this->orderModel->createOrder($orderData);

                if ($orderId) {
                    $this->orderModel->addOrderItem($orderId, $productId, $quantity, $price);
                    $this->productModel->updateStock($productId, $quantity);

                    $_SESSION['flash_message'] = 'Compra realizada com sucesso!';
                    header('Location: ' . URLROOT);
                    exit;
                }
            } else {
                $_SESSION['flash_message'] = 'Estoque insuficiente para este produto.';
                header('Location: ' . URLROOT . '/home/product/' . $productId);
                exit;
            }
        }
    }
}
