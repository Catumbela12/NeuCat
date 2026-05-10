<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProducts($categoryId = null, $search = null) {
        $sql = 'SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id';
        $params = [];
        $where = [];

        if ($categoryId) {
            $where[] = 'p.category_id = :category_id';
            $params[':category_id'] = $categoryId;
        }

        if ($search) {
            $where[] = '(p.name LIKE :search OR p.description LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY p.id DESC';

        $this->db->query($sql);

        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addProduct($data) {
        $this->db->query('INSERT INTO products (category_id, name, description, price, promotional_price, stock_quantity, image) VALUES (:category_id, :name, :description, :price, :promotional_price, :stock_quantity, :image)');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promotional_price', $data['promotional_price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateProduct($data) {
        $this->db->query('UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, promotional_price = :promotional_price, stock_quantity = :stock_quantity, image = :image WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promotional_price', $data['promotional_price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }

    public function updateStock($id, $qty) {
        $this->db->query('UPDATE products SET stock_quantity = stock_quantity - :qty WHERE id = :id AND stock_quantity >= :qty');
        $this->db->bind(':id', $id);
        $this->db->bind(':qty', $qty);
        return $this->db->execute();
    }

    public function deleteProduct($id) {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Additional Image Functions
    public function addProductImage($productId, $imagePath) {
        $this->db->query('INSERT INTO product_images (product_id, image) VALUES (:product_id, :image)');
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':image', $imagePath);
        return $this->db->execute();
    }

    public function getProductImages($productId) {
        $this->db->query('SELECT * FROM product_images WHERE product_id = :product_id');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function deleteProductImage($imageId) {
        $this->db->query('DELETE FROM product_images WHERE id = :id');
        $this->db->bind(':id', $imageId);
        return $this->db->execute();
    }

    // Favorites
    public function getFavorites($userId) {
        $this->db->query('SELECT p.* FROM products p JOIN favorites f ON p.id = f.product_id WHERE f.user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function addFavorite($userId, $productId) {
        $this->db->query('INSERT INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function removeFavorite($userId, $productId) {
        $this->db->query('DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }
    
    public function isFavorite($userId, $productId) {
        $this->db->query('SELECT id FROM favorites WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->single() ? true : false;
    }

    // Bestsellers & New Arrivals
    public function getBestSellers($limit = 4) {
        // Mocks bestsellers for now using random or order items logic
        $this->db->query('SELECT p.*, COUNT(oi.product_id) as sales FROM products p LEFT JOIN order_items oi ON p.id = oi.product_id GROUP BY p.id ORDER BY sales DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getNewArrivals($limit = 4) {
        $this->db->query('SELECT * FROM products ORDER BY id DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
