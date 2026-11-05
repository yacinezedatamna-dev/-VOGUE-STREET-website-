# NOVA STORE — Full-stack E-commerce

متجر إلكتروني عربي/فرنسي للسوق الجزائري، مبني بـ HTML5 وCSS3 وJavaScript وPHP 8+ وMySQL. العملة DZD والدفع عند الاستلام.

## Features
- Responsive storefront, product catalogue, search and product details.
- Session shopping cart and order checkout with database transaction and stock locking.
- Customer registration/login with secure password hashing.
- Admin dashboard: add/archive products and update order status.
- CSRF protection, PDO prepared statements, output escaping, secure session cookies, server security headers and role checks.

## Setup
1. Requirements: PHP 8.1+, MySQL 8+/MariaDB, PDO MySQL and mbstring.
2. Create a database/user. Import `database/schema.sql`.
3. Set environment variables on the hosting server: DB_HOST, DB_NAME, DB_USER, DB_PASS. Never commit secrets.
4. Set the web document root to `nova-store/public`.
5. Enable HTTPS. Use a least-privilege database user.
6. From the nova-store folder run `php scripts/create-admin.php` in a terminal to create the first admin account. Choose a unique email and a password of at least 12 characters.
7. For local testing, set DB environment variables and run `php -S 127.0.0.1:8000 -t public`.

## Before going live
Set PHP display_errors=Off and log_errors=On, configure server-level rate limiting/WAF, back up and test restoring MySQL, keep PHP/MySQL updated, and run a security scan plus independent penetration test. Review applicable privacy, returns, shipping and consumer rules in Algeria. This starter includes baseline protections but cannot guarantee zero vulnerabilities. No online card payment is configured.
