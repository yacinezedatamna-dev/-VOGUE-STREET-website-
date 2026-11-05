CREATE DATABASE IF NOT EXISTS nova_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nova_store;
CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS categories (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name_ar VARCHAR(120) NOT NULL,
 name_fr VARCHAR(120) NOT NULL,
 slug VARCHAR(140) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS products (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id BIGINT UNSIGNED NULL,
 name_ar VARCHAR(180) NOT NULL,
 name_fr VARCHAR(180) NOT NULL,
 description_ar TEXT NULL,
 description_fr TEXT NULL,
 price DECIMAL(12,2) NOT NULL,
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 image_url VARCHAR(1000) NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL,
 INDEX idx_products_active(is_active,created_at)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS orders (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NULL,
 order_number VARCHAR(32) NOT NULL UNIQUE,
 customer_name VARCHAR(120) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 address VARCHAR(255) NOT NULL,
 city VARCHAR(100) NOT NULL,
 notes TEXT NULL,
 total_amount DECIMAL(12,2) NOT NULL,
 payment_method VARCHAR(40) NOT NULL DEFAULT 'cash_on_delivery',
 status ENUM('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_orders_status(status,created_at)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS order_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_id BIGINT UNSIGNED NOT NULL,
 product_id BIGINT UNSIGNED NULL,
 product_name VARCHAR(180) NOT NULL,
 unit_price DECIMAL(12,2) NOT NULL,
 quantity INT UNSIGNED NOT NULL,
 line_total DECIMAL(12,2) NOT NULL,
 FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO categories(name_ar,name_fr,slug) VALUES
('إلكترونيات','Électronique','electronics'),
('إكسسوارات','Accessoires','accessories'),
('المنزل والمعيشة','Maison & quotidien','home'),
('الأزياء','Mode','fashion');

INSERT INTO products(category_id,name_ar,name_fr,description_ar,description_fr,price,stock,image_url)
SELECT id,'سماعات لاسلكية','Écouteurs sans fil','صوت واضح وتصميم مريح للاستخدام اليومي.','Un son clair et un design confortable.',4500,15,'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80'
FROM categories WHERE slug='electronics' AND NOT EXISTS(SELECT 1 FROM products WHERE name_fr='Écouteurs sans fil');
INSERT INTO products(category_id,name_ar,name_fr,description_ar,description_fr,price,stock,image_url)
SELECT id,'ساعة أنيقة','Montre élégante','ساعة بتصميم عصري يناسب كل يوم.','Une montre au style moderne.',7200,10,'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80'
FROM categories WHERE slug='accessories' AND NOT EXISTS(SELECT 1 FROM products WHERE name_fr='Montre élégante');
INSERT INTO products(category_id,name_ar,name_fr,description_ar,description_fr,price,stock,image_url)
SELECT id,'حقيبة يومية','Sac du quotidien','حقيبة عملية بتصميم بسيط.','Un sac pratique au design sobre.',5900,8,'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=900&q=80'
FROM categories WHERE slug='fashion' AND NOT EXISTS(SELECT 1 FROM products WHERE name_fr='Sac du quotidien');
INSERT INTO products(category_id,name_ar,name_fr,description_ar,description_fr,price,stock,image_url)
SELECT id,'مصباح مكتبي','Lampe de bureau','إضاءة مريحة للمكتب.','Un éclairage agréable pour le bureau.',3800,12,'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80'
FROM categories WHERE slug='home' AND NOT EXISTS(SELECT 1 FROM products WHERE name_fr='Lampe de bureau');
