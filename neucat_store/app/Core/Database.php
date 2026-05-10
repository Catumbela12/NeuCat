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
            // Conecta diretamente ao banco para maior performance
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Se falhar porque o banco não existe, tentamos criar (apenas em desenvolvimento ou primeira execução)
            if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
                $this->setupDatabase($options);
            } else {
                $this->error = $e->getMessage();
                die("Erro na Base de Dados: " . $this->error);
            }
        }
    }

    /**
     * Cria o banco de dados e as tabelas. 
     * Chamado automaticamente apenas se o banco não for encontrado.
     */
    private function setupDatabase($options) {
        try {
            $dsn_no_db = 'mysql:host=' . $this->host . ';port=' . $this->port . ';charset=utf8mb4';
            $temp_dbh = new PDO($dsn_no_db, $this->user, $this->pass, $options);
            $temp_dbh->exec("CREATE DATABASE IF NOT EXISTS `" . $this->dbname . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Agora conecta ao banco criado
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);

            // Cria as tabelas
            $this->createTables();
        } catch (PDOException $e) {
            die("Erro crítico na inicialização do Banco: " . $e->getMessage());
        }
    }

    private function createTables() {
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

        // Tabela de clientes
        $this->dbh->exec("CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(100) UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Outras tabelas omitidas aqui para brevidade, mas devem estar no seu SQL de migração.
        // Como o usuário já tem as tabelas criadas no Railway, o foco é parar de rodar os comandos desnecessários.
        
        // Insere categorias iniciais se estiverem vazias
        $stmt = $this->dbh->query("SELECT COUNT(*) FROM categories");
        if ($stmt->fetchColumn() == 0) {
            $this->dbh->exec("INSERT INTO categories (name) VALUES ('Moda'), ('Beleza'), ('Masculino'), ('Feminino'), ('Eletrônicos')");
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
