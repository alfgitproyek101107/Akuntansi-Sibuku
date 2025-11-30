-- =========================================
-- SISTEM AKUNTANSI SIBUKU - MYSQL SEED DATA
-- =========================================
-- Default data untuk development dan production
-- =========================================

-- =========================================
-- INSERT DEFAULT PERMISSIONS (Spatie Laravel Permission)
-- =========================================
INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
-- Dashboard permissions
('view dashboard', 'web', NOW(), NOW()),
('view reports', 'web', NOW(), NOW()),

-- Account permissions
('view accounts', 'web', NOW(), NOW()),
('create accounts', 'web', NOW(), NOW()),
('edit accounts', 'web', NOW(), NOW()),
('delete accounts', 'web', NOW(), NOW()),
('reconcile accounts', 'web', NOW(), NOW()),

-- Transaction permissions
('view transactions', 'web', NOW(), NOW()),
('create transactions', 'web', NOW(), NOW()),
('edit transactions', 'web', NOW(), NOW()),
('delete transactions', 'web', NOW(), NOW()),
('approve transactions', 'web', NOW(), NOW()),

-- Category permissions
('view categories', 'web', NOW(), NOW()),
('create categories', 'web', NOW(), NOW()),
('edit categories', 'web', NOW(), NOW()),
('delete categories', 'web', NOW(), NOW()),

-- Transfer permissions
('view transfers', 'web', NOW(), NOW()),
('create transfers', 'web', NOW(), NOW()),
('edit transfers', 'web', NOW(), NOW()),
('delete transfers', 'web', NOW(), NOW()),

-- Product permissions
('view products', 'web', NOW(), NOW()),
('create products', 'web', NOW(), NOW()),
('edit products', 'web', NOW(), NOW()),
('delete products', 'web', NOW(), NOW()),
('manage inventory', 'web', NOW(), NOW()),

-- Customer permissions
('view customers', 'web', NOW(), NOW()),
('create customers', 'web', NOW(), NOW()),
('edit customers', 'web', NOW(), NOW()),
('delete customers', 'web', NOW(), NOW()),

-- User management permissions
('view users', 'web', NOW(), NOW()),
('create users', 'web', NOW(), NOW()),
('edit users', 'web', NOW(), NOW()),
('delete users', 'web', NOW(), NOW()),
('manage roles', 'web', NOW(), NOW()),

-- Branch permissions
('view branches', 'web', NOW(), NOW()),
('create branches', 'web', NOW(), NOW()),
('edit branches', 'web', NOW(), NOW()),
('delete branches', 'web', NOW(), NOW()),
('switch branches', 'web', NOW(), NOW()),

-- Tax permissions
('view tax settings', 'web', NOW(), NOW()),
('create tax settings', 'web', NOW(), NOW()),
('edit tax settings', 'web', NOW(), NOW()),
('delete tax settings', 'web', NOW(), NOW()),

-- Invoice permissions
('view invoices', 'web', NOW(), NOW()),
('create invoices', 'web', NOW(), NOW()),
('edit invoices', 'web', NOW(), NOW()),
('delete invoices', 'web', NOW(), NOW()),

-- Bill permissions
('view bills', 'web', NOW(), NOW()),
('create bills', 'web', NOW(), NOW()),
('edit bills', 'web', NOW(), NOW()),
('delete bills', 'web', NOW(), NOW()),

-- Chart of accounts permissions
('view chart of accounts', 'web', NOW(), NOW()),
('create chart of accounts', 'web', NOW(), NOW()),
('edit chart of accounts', 'web', NOW(), NOW()),
('delete chart of accounts', 'web', NOW(), NOW()),

-- Journal permissions
('view journals', 'web', NOW(), NOW()),
('create journals', 'web', NOW(), NOW()),
('post journals', 'web', NOW(), NOW()),
('void journals', 'web', NOW(), NOW()),

-- Settings permissions
('view settings', 'web', NOW(), NOW()),
('edit settings', 'web', NOW(), NOW()),
('system administration', 'web', NOW(), NOW());

-- =========================================
-- INSERT DEFAULT ROLES
-- =========================================
INSERT INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
('Super Admin', 'web', NOW(), NOW()),
('Admin', 'web', NOW(), NOW()),
('Manager', 'web', NOW(), NOW()),
('Accounting', 'web', NOW(), NOW()),
('User', 'web', NOW(), NOW());

-- =========================================
-- ASSIGN PERMISSIONS TO ROLES
-- =========================================

-- Super Admin - All permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id FROM `permissions` p, `roles` r WHERE r.name = 'Super Admin';

-- Admin - Most permissions except system administration
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id FROM `permissions` p, `roles` r
WHERE r.name = 'Admin'
AND p.name NOT IN ('system administration', 'delete users', 'manage roles');

-- Manager - Management permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id FROM `permissions` p, `roles` r
WHERE r.name = 'Manager'
AND p.name IN (
    'view dashboard', 'view reports',
    'view accounts', 'view transactions', 'view transfers',
    'view products', 'view customers',
    'view invoices', 'view bills',
    'create transactions', 'create transfers', 'create invoices',
    'edit transactions', 'edit transfers', 'edit invoices',
    'approve transactions'
);

-- Accounting - Accounting specific permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id FROM `permissions` p, `roles` r
WHERE r.name = 'Accounting'
AND p.name IN (
    'view dashboard', 'view reports',
    'view accounts', 'create accounts', 'edit accounts', 'reconcile accounts',
    'view transactions', 'create transactions', 'edit transactions', 'delete transactions',
    'view transfers', 'create transfers', 'edit transfers', 'delete transfers',
    'view categories', 'create categories', 'edit categories', 'delete categories',
    'view chart of accounts', 'create chart of accounts', 'edit chart of accounts',
    'view journals', 'create journals', 'post journals', 'void journals',
    'view tax settings', 'create tax settings', 'edit tax settings',
    'approve transactions'
);

-- User - Basic permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id FROM `permissions` p, `roles` r
WHERE r.name = 'User'
AND p.name IN (
    'view dashboard',
    'view accounts', 'view transactions', 'view transfers',
    'view products', 'view customers',
    'view invoices', 'view bills',
    'create transactions', 'create transfers'
);

-- =========================================
-- INSERT DEFAULT CATEGORIES
-- =========================================
INSERT INTO `categories` (`user_id`, `name`, `type`, `color`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
-- Income Categories
(NULL, 'Penjualan Produk', 'income', '#10B981', 'fas fa-shopping-cart', 1, NOW(), NOW()),
(NULL, 'Penjualan Jasa', 'income', '#3B82F6', 'fas fa-tools', 1, NOW(), NOW()),
(NULL, 'Pendapatan Lain', 'income', '#8B5CF6', 'fas fa-plus-circle', 1, NOW(), NOW()),
(NULL, 'Diskon Diberikan', 'income', '#F59E0B', 'fas fa-tags', 1, NOW(), NOW()),

-- Expense Categories
(NULL, 'Pembelian Bahan Baku', 'expense', '#EF4444', 'fas fa-boxes', 1, NOW(), NOW()),
(NULL, 'Biaya Operasional', 'expense', '#F97316', 'fas fa-cogs', 1, NOW(), NOW()),
(NULL, 'Biaya Transportasi', 'expense', '#EC4899', 'fas fa-truck', 1, NOW(), NOW()),
(NULL, 'Biaya Utilitas', 'expense', '#06B6D4', 'fas fa-lightbulb', 1, NOW(), NOW()),
(NULL, 'Biaya Marketing', 'expense', '#84CC16', 'fas fa-bullhorn', 1, NOW(), NOW()),
(NULL, 'Biaya Administrasi', 'expense', '#6366F1', 'fas fa-file-alt', 1, NOW(), NOW()),
(NULL, 'Biaya Lain-lain', 'expense', '#64748B', 'fas fa-ellipsis-h', 1, NOW(), NOW());

-- =========================================
-- INSERT DEFAULT PRODUCT CATEGORIES
-- =========================================
INSERT INTO `product_categories` (`user_id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(NULL, 'Barang Konsumsi', 'Produk untuk konsumsi sehari-hari', 1, NOW(), NOW()),
(NULL, 'Barang Elektronik', 'Produk elektronik dan gadget', 1, NOW(), NOW()),
(NULL, 'Barang Fashion', 'Pakaian dan aksesoris', 1, NOW(), NOW()),
(NULL, 'Barang Rumah Tangga', 'Produk untuk kebutuhan rumah tangga', 1, NOW(), NOW()),
(NULL, 'Barang Lainnya', 'Kategori produk lainnya', 1, NOW(), NOW());

-- =========================================
-- INSERT DEFAULT TAX SETTINGS
-- =========================================
INSERT INTO `tax_settings` (`user_id`, `name`, `rate`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(NULL, 'PPN 11%', 11.00, 'percent', 1, NOW(), NOW()),
(NULL, 'PPN 10%', 10.00, 'percent', 1, NOW(), NOW()),
(NULL, 'PPh 21%', 21.00, 'percent', 1, NOW(), NOW()),
(NULL, 'PPh 23%', 23.00, 'percent', 1, NOW(), NOW()),
(NULL, 'PPH 25%', 25.00, 'percent', 1, NOW(), NOW());

-- =========================================
-- INSERT DEFAULT APP SETTINGS
-- =========================================
INSERT INTO `app_settings` (`key`, `value`, `type`, `is_public`, `created_at`, `updated_at`) VALUES
('app_name', '"Sistem Akuntansi Sibuku"', 'string', 1, NOW(), NOW()),
('app_version', '"1.0.0"', 'string', 1, NOW(), NOW()),
('company_name', 'null', 'string', 0, NOW(), NOW()),
('company_address', 'null', 'string', 0, NOW(), NOW()),
('company_phone', 'null', 'string', 0, NOW(), NOW()),
('company_email', 'null', 'string', 0, NOW(), NOW()),
('default_currency', '"IDR"', 'string', 1, NOW(), NOW()),
('date_format', '"d/m/Y"', 'string', 1, NOW(), NOW()),
('timezone', '"Asia/Jakarta"', 'string', 1, NOW(), NOW()),
('session_timeout', '7200', 'integer', 0, NOW(), NOW()),
('max_login_attempts', '5', 'integer', 0, NOW(), NOW()),
('lockout_duration', '15', 'integer', 0, NOW(), NOW()),
('enable_notifications', 'true', 'boolean', 0, NOW(), NOW()),
('backup_frequency', '"daily"', 'string', 0, NOW(), NOW()),
('enable_audit_trail', 'true', 'boolean', 0, NOW(), NOW());

-- =========================================
-- INSERT SAMPLE DATA FOR DEVELOPMENT
-- =========================================

-- Sample User (password: password123 - in production, use proper hashing)
-- Note: In production, use Laravel's Hash::make() for password hashing
INSERT INTO `users` (`name`, `email`, `password`, `email_verified_at`, `branch_id`, `user_role_id`, `created_at`, `updated_at`) VALUES
('Super Admin', 'admin@sibuku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), 1, 1, NOW(), NOW());

-- Assign Super Admin role to user
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\Models\\User', u.id FROM `roles` r, `users` u WHERE r.name = 'Super Admin' AND u.email = 'admin@sibuku.com';

-- Sample Accounts
INSERT INTO `accounts` (`user_id`, `branch_id`, `name`, `type`, `balance`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kas Toko', 'cash', 5000000.00, 1, NOW(), NOW()),
(1, 1, 'Bank Mandiri', 'bank', 15000000.00, 1, NOW(), NOW()),
(1, 1, 'Bank BCA', 'bank', 8000000.00, 1, NOW(), NOW()),
(1, 1, 'Dana E-wallet', 'ewallet', 2000000.00, 1, NOW(), NOW());

-- Sample Products
INSERT INTO `products` (`user_id`, `product_category_id`, `name`, `sku`, `buy_price`, `sell_price`, `stock_quantity`, `min_stock`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Beras Premium 5kg', 'BR001', 65000.00, 75000.00, 50, 10, 'Beras premium berkualitas tinggi', 1, NOW(), NOW()),
(1, 1, 'Minyak Goreng 2L', 'MG001', 28000.00, 32000.00, 30, 5, 'Minyak goreng kemasan 2 liter', 1, NOW(), NOW()),
(1, 2, 'Charger HP Samsung', 'CH001', 45000.00, 55000.00, 20, 3, 'Charger original Samsung', 1, NOW(), NOW()),
(1, 3, 'Kaos Polos Hitam', 'KP001', 35000.00, 45000.00, 25, 5, 'Kaos polos katun premium', 1, NOW(), NOW());

-- Sample Customers
INSERT INTO `customers` (`user_id`, `name`, `email`, `phone`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PT. Maju Jaya', 'contact@majujaya.com', '021-12345678', 'business', 'active', NOW(), NOW()),
(1, 'Ahmad Surya', 'ahmad@email.com', '08123456789', 'individual', 'active', NOW(), NOW()),
(1, 'CV. Berkah Abadi', 'info@berkahabadi.com', '022-98765432', 'business', 'active', NOW(), NOW());

-- Sample Transactions
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `amount`, `description`, `date`, `type`, `branch_id`, `created_at`, `updated_at`) VALUES
-- Income transactions
(1, 1, 1, 150000.00, 'Penjualan beras premium', '2024-01-15', 'income', 1, NOW(), NOW()),
(1, 2, 1, 320000.00, 'Penjualan minyak goreng', '2024-01-16', 'income', 1, NOW(), NOW()),
(1, 3, 2, 550000.00, 'Penjualan jasa service', '2024-01-17', 'income', 1, NOW(), NOW()),

-- Expense transactions
(1, 1, 5, 75000.00, 'Pembelian bahan baku', '2024-01-14', 'expense', 1, NOW(), NOW()),
(1, 2, 6, 125000.00, 'Biaya operasional toko', '2024-01-15', 'expense', 1, NOW(), NOW()),
(1, 1, 7, 50000.00, 'Biaya transportasi', '2024-01-16', 'expense', 1, NOW(), NOW());

-- =========================================
-- CREATE VIEWS FOR COMMON QUERIES
-- =========================================

-- View for account balances with transaction summary
CREATE VIEW `account_balances` AS
SELECT
    a.id,
    a.user_id,
    a.name,
    a.type,
    a.balance as current_balance,
    COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0) as total_income,
    COALESCE(SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0) as total_expense,
    COALESCE(COUNT(t.id), 0) as transaction_count,
    MAX(t.date) as last_transaction_date
FROM `accounts` a
LEFT JOIN `transactions` t ON a.id = t.account_id
GROUP BY a.id, a.user_id, a.name, a.type, a.balance;

-- View for monthly financial summary
CREATE VIEW `monthly_financial_summary` AS
SELECT
    user_id,
    DATE_FORMAT(date, '%Y-%m') as month_year,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as net_profit,
    COUNT(*) as transaction_count
FROM `transactions`
GROUP BY user_id, DATE_FORMAT(date, '%Y-%m');

-- View for product stock status
CREATE VIEW `product_stock_status` AS
SELECT
    p.id,
    p.name,
    p.sku,
    p.stock_quantity,
    p.min_stock,
    pc.name as category_name,
    CASE
        WHEN p.stock_quantity <= 0 THEN 'Out of Stock'
        WHEN p.stock_quantity <= p.min_stock THEN 'Low Stock'
        ELSE 'In Stock'
    END as stock_status,
    p.stock_quantity - p.min_stock as stock_above_minimum
FROM `products` p
LEFT JOIN `product_categories` pc ON p.product_category_id = pc.id
WHERE p.is_active = 1;

-- =========================================
-- CREATE STORED PROCEDURES
-- =========================================

-- Procedure to get account ledger
DELIMITER $$
CREATE PROCEDURE `get_account_ledger`(
    IN account_id BIGINT,
    IN start_date DATE,
    IN end_date DATE
)
BEGIN
    SELECT
        t.date,
        t.description,
        CASE
            WHEN t.type = 'income' THEN t.amount
            ELSE 0
        END as debit,
        CASE
            WHEN t.type = 'expense' THEN t.amount
            ELSE 0
        END as credit,
        @balance := @balance + (CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END) as balance
    FROM `transactions` t
    CROSS JOIN (SELECT @balance := (SELECT balance FROM `accounts` WHERE id = account_id)) as init_balance
    WHERE t.account_id = account_id
    AND t.date BETWEEN start_date AND end_date
    ORDER BY t.date, t.created_at;
END$$
DELIMITER ;

-- Procedure to calculate profit & loss
DELIMITER $$
CREATE PROCEDURE `calculate_profit_loss`(
    IN user_id BIGINT,
    IN start_date DATE,
    IN end_date DATE
)
BEGIN
    SELECT
        SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END) as total_revenue,
        SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END) as total_expenses,
        SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END) as net_profit,
        COUNT(*) as total_transactions
    FROM `transactions` t
    WHERE t.user_id = user_id
    AND t.date BETWEEN start_date AND end_date;
END$$
DELIMITER ;

-- =========================================
-- CREATE TRIGGERS FOR DATA INTEGRITY
-- =========================================

-- Trigger to prevent negative account balance (optional - can be disabled for overdraft)
DELIMITER $$
CREATE TRIGGER `prevent_negative_balance`
BEFORE UPDATE ON `accounts`
FOR EACH ROW
BEGIN
    IF NEW.balance < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Account balance cannot be negative';
    END IF;
END$$
DELIMITER ;

-- Trigger to update product stock on transaction creation
DELIMITER $$
CREATE TRIGGER `update_stock_on_transaction`
AFTER INSERT ON `transactions`
FOR EACH ROW
BEGIN
    IF NEW.product_id IS NOT NULL AND NEW.type = 'income' THEN
        UPDATE `products`
        SET stock_quantity = stock_quantity - 1,
            updated_at = NOW()
        WHERE id = NEW.product_id;
    END IF;
END$$
DELIMITER ;

-- =========================================
-- FINAL NOTES
-- =========================================
/*
USAGE INSTRUCTIONS:

1. Import this file into MySQL:
   mysql -u username -p database_name < database_seed.sql

2. Update Laravel .env file:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=akuntansi_sibuku
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

3. Run Laravel migrations (if needed):
   php artisan migrate

4. Seed additional data (optional):
   php artisan db:seed

5. Create storage link:
   php artisan storage:link

6. Clear cache:
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear

DEFAULT LOGIN:
- Email: admin@sibuku.com
- Password: password123

SECURITY NOTES:
- Change default password immediately after first login
- Configure proper backup procedures
- Set up SSL certificates for production
- Configure firewall and security groups
- Regular security updates and monitoring

PERFORMANCE NOTES:
- Consider adding database indexes for frequently queried columns
- Set up database replication for high availability
- Configure query caching and optimization
- Monitor slow queries and optimize as needed
*/