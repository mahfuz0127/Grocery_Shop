CREATE DATABASE IF NOT EXISTS grocery CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE grocery;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NULL,
  name VARCHAR(150) NOT NULL,
  barcode VARCHAR(60) NULL,
  unit VARCHAR(20) NOT NULL DEFAULT 'pcs',
  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  sell_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (barcode), INDEX (name),
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice_no VARCHAR(30) NOT NULL UNIQUE,
  customer_name VARCHAR(100) NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  discount DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  paid DECIMAL(10,2) NOT NULL,
  change_due DECIMAL(10,2) NOT NULL DEFAULT 0,
  payment_method VARCHAR(20) NOT NULL DEFAULT 'Cash',
  sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (sale_date)
);

CREATE TABLE sale_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  product_id INT NULL,
  name VARCHAR(150) NOT NULL,
  qty DECIMAL(10,2) NOT NULL,
  cost_price DECIMAL(10,2) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Default login: admin / admin123  (change it after first login)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$12$nnGg3QHAnsZMlwB/DrgKT.usPPRH/E2gDtkePsFqJRjWqR4R1Pwhm');

INSERT INTO categories (name) VALUES ('Grains'), ('Dairy'), ('Beverages'), ('Snacks');

INSERT INTO products (category_id, name, barcode, unit, cost_price, sell_price, stock) VALUES
(1, 'Rice 1kg',      '1001', 'kg',  50.00,  60.00, 100),
(1, 'Lentil 1kg',    '1002', 'kg', 110.00, 130.00,  50),
(2, 'Milk 1L',       '2001', 'pcs', 80.00,  95.00,  30),
(3, 'Cola 500ml',    '3001', 'pcs', 30.00,  40.00,  60),
(4, 'Potato Chips',  '4001', 'pcs', 15.00,  25.00,   4);
