<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrder($data) {
        $this->db->query('INSERT INTO orders (user_id, guest_email, total_price, status) VALUES (:user_id, :guest_email, :total_price, :status)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':guest_email', $data['guest_email']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':status', 'pending');

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addOrderItem($orderId, $productId, $quantity, $price) {
        $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    public function getOrdersByUserId($userId) {
        $this->db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getOrderItems($orderId) {
        $this->db->query('SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function getAllOrders() {
        $this->db->query('SELECT o.*, c.name as customer_name FROM orders o LEFT JOIN customers c ON o.user_id = c.id ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }

    public function updateOrderStatus($orderId, $status) {
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':id', $orderId);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
}
