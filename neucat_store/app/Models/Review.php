<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addReview($data) {
        $this->db->query('INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        return $this->db->execute();
    }

    public function getReviewsByProductId($productId) {
        $this->db->query('SELECT r.*, c.name as customer_name FROM reviews r JOIN customers c ON r.user_id = c.id WHERE r.product_id = :product_id ORDER BY r.created_at DESC');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function getStoreReviews() {
        $this->db->query('SELECT r.*, c.name as customer_name FROM reviews r JOIN customers c ON r.user_id = c.id WHERE r.product_id IS NULL ORDER BY r.created_at DESC');
        return $this->db->resultSet();
    }

    public function getAverageRating($productId = null) {
        if ($productId) {
            $this->db->query('SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = :product_id');
            $this->db->bind(':product_id', $productId);
        } else {
            $this->db->query('SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id IS NULL');
        }
        $result = $this->db->single();
        return $result ? number_format((float)$result->avg_rating, 1, '.', '') : 0;
    }
}
