<?php
class Customer {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        $this->db->query('INSERT INTO customers (name, email, phone, birth_date, gender, password) VALUES (:name, :email, :phone, :birth_date, :gender, :password)');
        $this->db->bind(':name',       $data['name']);
        $this->db->bind(':email',      $data['email']);
        $this->db->bind(':phone',      $data['phone']);
        $this->db->bind(':birth_date', $data['birth_date']);
        $this->db->bind(':gender',     $data['gender']);
        $this->db->bind(':password',   $data['password']);
        return $this->db->execute();
    }

    public function findByEmail($email) {
        $this->db->query('SELECT * FROM customers WHERE email = :email LIMIT 1');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function findByPhone($phone) {
        $this->db->query('SELECT * FROM customers WHERE phone = :phone LIMIT 1');
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }

    public function emailOrPhoneExists($email, $phone) {
        $this->db->query('SELECT id FROM customers WHERE email = :email OR (phone IS NOT NULL AND phone != \'\' AND phone = :phone) LIMIT 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }

    public function getAll() {
        $this->db->query('SELECT id, name, email, phone, birth_date, gender, created_at FROM customers ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getCustomerById($id) {
        $this->db->query('SELECT id, name, email, phone, birth_date, gender, address_line, city, state, zip, created_at FROM customers WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateProfile($data) {
        $this->db->query('UPDATE customers SET name = :name, phone = :phone, birth_date = :birth_date, gender = :gender, address_line = :address_line, city = :city, state = :state, zip = :zip WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':birth_date', $data['birth_date']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':address_line', $data['address_line']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':zip', $data['zip']);
        
        return $this->db->execute();
    }
}
