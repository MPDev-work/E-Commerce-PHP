-- Solis Skin database + admin product options migration
-- Import this file in phpMyAdmin after selecting the Import tab.
CREATE DATABASE IF NOT EXISTS webstore_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webstore_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  pwd VARCHAR(255) NOT NULL,
  roles ENUM('admin', 'client') NOT NULL DEFAULT 'client'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS skin_types (
  skin_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  skin_type_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sizes (
  size_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  size_name VARCHAR(50) NOT NULL UNIQUE,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  p_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  p_title VARCHAR(180) NOT NULL,
  p_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  p_qty INT UNSIGNED NOT NULL DEFAULT 0,
  description TEXT NULL,
  p_image VARCHAR(255) NULL,
  category_id INT UNSIGNED NULL
) ENGINE=InnoDB;

-- These ALTER statements upgrade your existing product table safely.
ALTER TABLE products ADD COLUMN IF NOT EXISTS category_id INT UNSIGNED NULL;

CREATE TABLE IF NOT EXISTS product_skin_types (
  product_id INT UNSIGNED NOT NULL,
  skin_type_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (product_id, skin_type_id),
  CONSTRAINT fk_product_skin_type_product FOREIGN KEY (product_id) REFERENCES products(p_id) ON DELETE CASCADE,
  CONSTRAINT fk_product_skin_type_type FOREIGN KEY (skin_type_id) REFERENCES skin_types(skin_type_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_sizes (
  product_id INT UNSIGNED NOT NULL,
  size_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (product_id, size_id),
  CONSTRAINT fk_product_size_product FOREIGN KEY (product_id) REFERENCES products(p_id) ON DELETE CASCADE,
  CONSTRAINT fk_product_size_size FOREIGN KEY (size_id) REFERENCES sizes(size_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Each account has a separate persistent shopping cart.
CREATE TABLE IF NOT EXISTS user_carts (
  cart_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_user_carts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_cart_items (
  cart_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cart_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  selected_skin_type VARCHAR(100) NOT NULL DEFAULT '',
  selected_size VARCHAR(50) NOT NULL DEFAULT '',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_user_cart_items_cart FOREIGN KEY (cart_id) REFERENCES user_carts(cart_id) ON DELETE CASCADE,
  CONSTRAINT fk_user_cart_items_product FOREIGN KEY (product_id) REFERENCES products(p_id) ON DELETE CASCADE,
  UNIQUE KEY unique_cart_product_option (cart_id, product_id, selected_skin_type, selected_size)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  order_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(180) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS store_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO categories (category_name, description) VALUES
  ('Cleansers', 'Oil, balm, gel, and foam cleansers for a fresh start.'),
  ('Toners & Essences', 'Hydrating and balancing preparation for your routine.'),
  ('Serums & Treatments', 'Targeted formulas for hydration, brightening, and barrier care.'),
  ('Moisturisers', 'Creams and gels that seal in lasting hydration.'),
  ('Sunscreen', 'Daily broad-spectrum sun protection.'),
  ('Masks & Exfoliators', 'Weekly renewal and intensive care.'),
  ('Lip Care', 'Nourishing lip treatments and everyday protection.');

INSERT IGNORE INTO skin_types (skin_type_name) VALUES
  ('All skin types'), ('Normal'), ('Dry'), ('Oily'), ('Combination'), ('Sensitive'), ('Acne-prone'), ('Mature');

INSERT IGNORE INTO sizes (size_name, sort_order) VALUES
  ('15ml', 10), ('30ml', 20), ('50ml', 30), ('75ml', 40), ('100ml', 50), ('150ml', 60), ('200ml', 70), ('300ml', 80);

INSERT INTO store_settings (setting_key, setting_value) VALUES
  ('store_name', 'Solis Skin'), ('currency', 'USD')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- After registration, promote your administrator account if needed:
-- UPDATE users SET roles = 'admin' WHERE email = 'your-admin@email.com';
