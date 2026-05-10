<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $port = DB_PORT;
    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
        ];

        try {
            // Conecta sem especificar o banco para poder criá-lo se não existir
            $dsn_no_db = 'mysql:host=' . $this->host . ';port=' . $this->port . ';charset=utf8';
            $this->dbh = new PDO($dsn_no_db, $this->user, $this->pass, $options);

            // Cria o banco se não existir
            $this->dbh->exec("CREATE DATABASE IF NOT EXISTS `" . $this->dbname . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->dbh->exec("USE `" . $this->dbname . "`");

            // Cria a tabela de produtos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10,2),
                promotional_price DECIMAL(10,2) DEFAULT NULL,
                image VARCHAR(255)
            )");

            // Cria a tabela de clientes (NeuCustomer)
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS customers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(100) UNIQUE,
                phone VARCHAR(50) DEFAULT NULL,
                birth_date DATE DEFAULT NULL,
                gender CHAR(1) DEFAULT NULL,
                password VARCHAR(255) NOT NULL,
                address_line VARCHAR(255) DEFAULT NULL,
                city VARCHAR(100) DEFAULT NULL,
                state VARCHAR(100) DEFAULT NULL,
                zip VARCHAR(20) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Cria a tabela de categorias
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Cria a tabela de produtos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10,2),
                promotional_price DECIMAL(10,2) DEFAULT NULL,
                stock_quantity INT DEFAULT 0,
                image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
            )");

            // Tabela de imagens de produtos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS product_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                image VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )");

            // Tabela de favoritos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS favorites (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES customers(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )");

            // Tabela de pedidos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                guest_email VARCHAR(100) DEFAULT NULL,
                total_price DECIMAL(10,2) NOT NULL,
                status VARCHAR(50) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES customers(id) ON DELETE SET NULL
            )");

            // Tabela de itens do pedido
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )");

            // Tabela de avaliações da loja/produtos
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT DEFAULT NULL,
                rating INT NOT NULL CHECK(rating >= 1 AND rating <= 5),
                comment TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES customers(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )");

            // Garante categorias iniciais e ajusta produtos antigos (se necessário)
            try {
                // Tentativa de adicionar category_id e stock_quantity caso a tabela de produtos já existisse
                $this->dbh->exec("ALTER TABLE products ADD COLUMN category_id INT DEFAULT NULL AFTER id");
                $this->dbh->exec("ALTER TABLE products ADD COLUMN stock_quantity INT DEFAULT 10 AFTER promotional_price");
                $this->dbh->exec("ALTER TABLE products ADD CONSTRAINT fk_cat FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL");
            } catch (PDOException $e) {
                // Ignora se as colunas já existirem
            }

            try {
                // Tentativa de adicionar campos de endereço ao customer
                $this->dbh->exec("ALTER TABLE customers ADD COLUMN address_line VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE customers ADD COLUMN city VARCHAR(100) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE customers ADD COLUMN state VARCHAR(100) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE customers ADD COLUMN zip VARCHAR(20) DEFAULT NULL");
            } catch (PDOException $e) {
                // Ignora se as colunas já existirem
            }

            // Insere categorias iniciais
            $stmt = $this->dbh->query("SELECT COUNT(*) FROM categories");
            if ($stmt->fetchColumn() == 0) {
                $this->dbh->exec("INSERT INTO categories (name) VALUES ('Moda'), ('Beleza'), ('Masculino'), ('Feminino'), ('Eletrônicos')");
            }

            // Insere dados de exemplo se a tabela de produtos estiver vazia
            $stmt = $this->dbh->query("SELECT COUNT(*) FROM products");
            if ($stmt->fetchColumn() == 0) {
                $this->dbh->exec("INSERT INTO products (category_id, name, description, price, promotional_price, stock_quantity, image) VALUES
                (4, 'Colar Dourado Elegance', 'Colar premium com acabamento em ouro 18k.', 199.90, 149.90, 10, 'gold_necklace.webp'),
                (3, 'Relógio Black Prestige', 'Relógio minimalista preto com detalhes dourados.', 349.90, NULL, 5, 'black_watch.webp'),
                (4, 'Brincos Neucat', 'Brincos exclusivos da coleção Neucat.', 129.90, NULL, 20, 'earrings.webp'),
                (3, 'Pulseira Gold Noir', 'Pulseira de couro preto com fecho em ouro.', 159.90, 119.90, 15, 'bracelet.webp')");
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Erro na Base de Dados: " . $this->error);
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):  $type = PDO::PARAM_INT;  break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default:              $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
