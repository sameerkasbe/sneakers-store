CREATE DATABASE IF NOT EXISTS sneaker_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sneaker_store;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS order_items; DROP TABLE IF EXISTS orders; DROP TABLE IF EXISTS products; DROP TABLE IF EXISTS categories; DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL UNIQUE,password VARCHAR(32) NOT NULL,role ENUM('admin','customer') NOT NULL DEFAULT 'customer',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE categories(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(80) NOT NULL UNIQUE);
CREATE TABLE products(id INT AUTO_INCREMENT PRIMARY KEY,category_id INT NULL,name VARCHAR(150) NOT NULL,description TEXT,price DECIMAL(10,2) NOT NULL,image VARCHAR(255) DEFAULT NULL,stock INT NOT NULL DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL);
CREATE TABLE orders(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,total DECIMAL(10,2) NOT NULL,status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE order_items(id INT AUTO_INCREMENT PRIMARY KEY,order_id INT NOT NULL,product_id INT NULL,product_name VARCHAR(150) NOT NULL,price DECIMAL(10,2) NOT NULL,quantity INT NOT NULL,FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL);
INSERT INTO users(name,email,password,role) VALUES('Store Admin','admin@sneakerstore.com',MD5('admin123'),'admin');
INSERT INTO categories(name) VALUES('Running'),('Lifestyle'),('Basketball'),('Training');
INSERT INTO products(category_id,name,description,price,image,stock) VALUES
(2,'Classic Street Low','A clean everyday sneaker with a comfortable fit.',2499,NULL,12),
(1,'Runner Flex','Lightweight running shoes for daily movement.',3299,NULL,8),
(3,'Court High','High-top basketball-inspired sneakers.',3999,NULL,6),
(4,'Move Trainer','Versatile trainers for workouts and everyday wear.',2799,NULL,10);
