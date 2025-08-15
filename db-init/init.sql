CREATE DATABASE IF NOT EXISTS simple_api;
USE simple_api;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL
);

INSERT INTO users (name, email, address) VALUES
('Testi Teppo', 'testi.teppo@example.com', 'Testikatu 1'),
('Aku Ankka', 'aku.ankka@example.com', 'Ankkalinna 2'),
('Teppo Tulppu', 'teppo.tulppu@example.com', 'Ankkalinna 3');
