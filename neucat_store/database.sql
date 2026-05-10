CREATE DATABASE IF NOT EXISTS neucat_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE neucat_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    promotional_price DECIMAL(10,2) DEFAULT NULL,
    image VARCHAR(255) DEFAULT 'placeholder.webp'
);

INSERT INTO products (name, description, price, promotional_price, image) VALUES 
('Colar Dourado Elegance', 'Colar premium com acabamento em ouro 18k.', 199.90, 149.90, 'gold_necklace.webp'),
('Relógio Black Prestige', 'Relógio minimalista preto com detalhes dourados.', 349.90, NULL, 'black_watch.webp'),
('Brincos Neucat', 'Brincos exclusivos da coleção Neucat.', 129.90, NULL, 'earrings.webp'),
('Pulseira Gold Noir', 'Pulseira de couro preto com fecho em ouro.', 159.90, 119.90, 'bracelet.webp');
