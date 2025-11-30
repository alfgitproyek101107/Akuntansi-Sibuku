-- =========================================
-- SISTEM AKUNTANSI SIBUKU - PRODUCTION SEED DATA
-- =========================================
-- Initial Data for Production Deployment
-- Created: 2025-11-22
-- Version: 1.0.0 Production
-- =========================================

USE `akuntansi_sibuku`;

-- =========================================
-- USER ROLES DATA
-- =========================================
INSERT INTO `user_roles` (`id`, `name`, `display_name`, `description`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Super Admin', 'Full system access with all permissions', '["*"]', NOW(), NOW()),
(2, 'admin', 'Administrator', 'Administrative access to manage users and settings', '["manage_users", "manage_settings", "view_reports"]', NOW(), NOW()),
(3, 'branch_manager', 'Branch Manager', 'Manage branch operations and staff', '["manage_branch", "view_reports", "manage_inventory"]', NOW(), NOW()),
(4, 'kasir', 'Kasir', 'Handle cash transactions and sales', '["create_transactions", "view_inventory"]', NOW(), NOW()),
(5, 'inventory_manager', 'Inventory Manager', 'Manage products and stock levels', '["manage_products", "manage_inventory", "view_reports"]', NOW(), NOW()),
(6, 'auditor', 'Auditor', 'View and audit financial reports', '["view_reports", "audit_transactions"]', NOW(), NOW()),
(7, 'user', 'User', 'Basic user access', '["view_own_data"]', NOW(), NOW());

-- =========================================
-- BRANCHES DATA
-- =========================================
INSERT INTO `branches` (`id`, `code`, `name`, `address`, `phone`, `email`, `manager_name`, `establishment_date`, `is_active`, `is_head_office`, `is_demo`, `settings`, `created_at`, `updated_at`) VALUES
(1000, 'HO001', 'Kantor Pusat Jakarta', 'Jl. Sudirman No. 1, Jakarta Pusat, DKI Jakarta 10220', '+62-21-1234567', 'admin@sibuku.com', 'Super Admin', '2025-01-01', 1, 1, 0, '{"currency": "IDR", "timezone": "Asia/Jakarta"}', NOW(), NOW()),
(1001, 'BR001', 'Cabang Bandung', 'Jl. Asia Afrika No. 123, Bandung, Jawa Barat 40111', '+62-22-9876543', 'bandung@sibuku.com', 'Manager Bandung', '2025-01-15', 1, 0, 0, '{"currency": "IDR", "timezone": "Asia/Jakarta"}', NOW(), NOW()),
(1002, 'BR002', 'Cabang Surabaya', 'Jl. Tunjungan No. 456, Surabaya, Jawa Timur 60275', '+62-31-5678901', 'surabaya@sibuku.com', 'Manager Surabaya', '2025-02-01', 1, 0, 0, '{"currency": "IDR", "timezone": "Asia/Jakarta"}', NOW(), NOW()),
(999, 'DEMO', 'Demo Cabang', 'Jl. Demo No. 123, Jakarta', '+62-21-1234567', 'demo@company.com', 'Demo Manager', '2025-01-01', 1, 0, 1, '{"currency": "IDR", "timezone": "Asia/Jakarta"}', NOW(), NOW());

-- =========================================
-- USERS DATA
-- =========================================
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `branch_id`, `user_role_id`, `demo_mode`, `failed_login_attempts`, `locked_until`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1000, 1, 0, 0, NULL, NULL, NOW(), NOW()),
(2, 'Admin Pusat', 'admin.pusat@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1000, 2, 0, 0, NULL, NULL, NOW(), NOW()),
(3, 'Manager Bandung', 'manager.bandung@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1001, 3, 0, 0, NULL, NULL, NOW(), NOW()),
(4, 'Kasir Surabaya', 'kasir.surabaya@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1002, 4, 0, 0, NULL, NULL, NOW(), NOW()),
(5, 'Staff Inventory', 'inventory@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1000, 5, 0, 0, NULL, NULL, NOW(), NOW()),
(6, 'Auditor', 'auditor@sibuku.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPjlpR8VxK0C6', 1000, 6, 0, 0, NULL, NULL, NOW(), NOW()),
(7, 'Demo User', 'demo@example.com', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 999, 2, 1, 0, NULL, NULL, NOW(), NOW());

-- =========================================
-- USER BRANCHES DATA
-- =========================================
INSERT INTO `user_branches` (`id`, `user_id`, `branch_id`, `role_name`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1000, 'super_admin', 1, 1, NOW(), NOW()),
(2, 1, 1001, 'super_admin', 0, 1, NOW(), NOW()),
(3, 1, 1002, 'super_admin', 0, 1, NOW(), NOW()),
(4, 2, 1000, 'admin', 1, 1, NOW(), NOW()),
(5, 3, 1001, 'branch_manager', 1, 1, NOW(), NOW()),
(6, 4, 1002, 'kasir', 1, 1, NOW(), NOW()),
(7, 5, 1000, 'inventory_manager', 1, 1, NOW(), NOW()),
(8, 6, 1000, 'auditor', 1, 1, NOW(), NOW()),
(9, 7, 999, 'admin', 1, 1, NOW(), NOW());

-- =========================================
-- ACCOUNTS DATA (HO)
-- =========================================
INSERT INTO `accounts` (`id`, `user_id`, `name`, `code`, `type`, `balance`, `description`, `is_active`, `branch_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kas Kecil', '1101', 'asset', 5000000.00, 'Kas untuk operasional harian', 1, 1000, NULL, NOW(), NOW()),
(2, 1, 'Bank BCA', '1102', 'asset', 25000000.00, 'Rekening bank utama', 1, 1000, NULL, NOW(), NOW()),
(3, 1, 'Bank Mandiri', '1103', 'asset', 15000000.00, 'Rekening bank sekunder', 1, 1000, NULL, NOW(), NOW()),
(4, 1, 'Piutang Usaha', '1201', 'asset', 5000000.00, 'Piutang dari penjualan', 1, 1000, NULL, NOW(), NOW()),
(5, 1, 'Persediaan Barang', '1301', 'asset', 20000000.00, 'Nilai persediaan produk', 1, 1000, NULL, NOW(), NOW()),
(6, 1, 'Penjualan Produk', '4101', 'revenue', 0.00, 'Pendapatan dari penjualan produk', 1, 1000, NULL, NOW(), NOW()),
(7, 1, 'Jasa Konsultasi', '4102', 'revenue', 0.00, 'Pendapatan dari jasa konsultasi', 1, 1000, NULL, NOW(), NOW()),
(8, 1, 'Harga Pokok Penjualan', '5101', 'expense', 0.00, 'Biaya pembelian produk terjual', 1, 1000, NULL, NOW(), NOW()),
(9, 1, 'Gaji Karyawan', '5201', 'expense', 0.00, 'Gaji dan tunjangan karyawan', 1, 1000, NULL, NOW(), NOW()),
(10, 1, 'Sewa Kantor', '5202', 'expense', 0.00, 'Biaya sewa kantor', 1, 1000, NULL, NOW(), NOW()),
(11, 1, 'Utilitas', '5203', 'expense', 0.00, 'Biaya listrik, air, telepon', 1, 1000, NULL, NOW(), NOW()),
(12, 1, 'Transportasi', '5204', 'expense', 0.00, 'Biaya transportasi dan perjalanan', 1, 1000, NULL, NOW(), NOW()),
(13, 1, 'Entertainment', '5205', 'expense', 0.00, 'Biaya entertainment dan promosi', 1, 1000, NULL, NOW(), NOW());

-- =========================================
-- ACCOUNTS DATA (Bandung Branch)
-- =========================================
INSERT INTO `accounts` (`id`, `user_id`, `name`, `code`, `type`, `balance`, `description`, `is_active`, `branch_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(14, 3, 'Kas Kecil', '1101', 'asset', 3000000.00, 'Kas untuk operasional harian', 1, 1001, NULL, NOW(), NOW()),
(15, 3, 'Bank BRI', '1102', 'asset', 12000000.00, 'Rekening bank cabang', 1, 1001, NULL, NOW(), NOW()),
(16, 3, 'Piutang Usaha', '1201', 'asset', 2000000.00, 'Piutang dari penjualan', 1, 1001, NULL, NOW(), NOW()),
(17, 3, 'Persediaan Barang', '1301', 'asset', 8000000.00, 'Nilai persediaan produk', 1, 1001, NULL, NOW(), NOW()),
(18, 3, 'Penjualan Produk', '4101', 'revenue', 0.00, 'Pendapatan dari penjualan produk', 1, 1001, NULL, NOW(), NOW()),
(19, 3, 'Harga Pokok Penjualan', '5101', 'expense', 0.00, 'Biaya pembelian produk terjual', 1, 1001, NULL, NOW(), NOW()),
(20, 3, 'Beban Operasional', '5201', 'expense', 0.00, 'Biaya operasional cabang', 1, 1001, NULL, NOW(), NOW());

-- =========================================
-- ACCOUNTS DATA (Surabaya Branch)
-- =========================================
INSERT INTO `accounts` (`id`, `user_id`, `name`, `code`, `type`, `balance`, `description`, `is_active`, `branch_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(21, 4, 'Kas Kecil', '1101', 'asset', 4000000.00, 'Kas untuk operasional harian', 1, 1002, NULL, NOW(), NOW()),
(22, 4, 'Bank BNI', '1102', 'asset', 18000000.00, 'Rekening bank cabang', 1, 1002, NULL, NOW(), NOW()),
(23, 4, 'Piutang Usaha', '1201', 'asset', 3000000.00, 'Piutang dari penjualan', 1, 1002, NULL, NOW(), NOW()),
(24, 4, 'Persediaan Barang', '1301', 'asset', 12000000.00, 'Nilai persediaan produk', 1, 1002, NULL, NOW(), NOW()),
(25, 4, 'Penjualan Produk', '4101', 'revenue', 0.00, 'Pendapatan dari penjualan produk', 1, 1002, NULL, NOW(), NOW()),
(26, 4, 'Harga Pokok Penjualan', '5101', 'expense', 0.00, 'Biaya pembelian produk terjual', 1, 1002, NULL, NOW(), NOW()),
(27, 4, 'Beban Operasional', '5201', 'expense', 0.00, 'Biaya operasional cabang', 1, 1002, NULL, NOW(), NOW());

-- =========================================
-- CATEGORIES DATA (HO)
-- =========================================
INSERT INTO `categories` (`id`, `name`, `type`, `description`, `color`, `is_active`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'Penjualan Produk', 'income', 'Pendapatan dari penjualan produk', '#10b981', 1, 1000, NOW(), NOW()),
(2, 'Jasa Konsultasi', 'income', 'Pendapatan dari jasa konsultasi', '#3b82f6', 1, 1000, NOW(), NOW()),
(3, 'Komisi Penjualan', 'income', 'Komisi dari penjualan', '#8b5cf6', 1, 1000, NOW(), NOW()),
(4, 'Beli Bahan Baku', 'expense', 'Pembelian bahan baku', '#ef4444', 1, 1000, NOW(), NOW()),
(5, 'Gaji Karyawan', 'expense', 'Gaji dan tunjangan karyawan', '#f59e0b', 1, 1000, NOW(), NOW()),
(6, 'Sewa Kantor', 'expense', 'Biaya sewa kantor', '#6b7280', 1, 1000, NOW(), NOW()),
(7, 'Utilitas', 'expense', 'Listrik, air, telepon', '#06b6d4', 1, 1000, NOW(), NOW()),
(8, 'Transportasi', 'expense', 'Transportasi dan perjalanan', '#84cc16', 1, 1000, NOW(), NOW()),
(9, 'Entertainment', 'expense', 'Entertainment dan promosi', '#ec4899', 1, 1000, NOW(), NOW());

-- =========================================
-- PRODUCT CATEGORIES DATA
-- =========================================
INSERT INTO `product_categories` (`id`, `user_id`, `name`, `description`, `is_active`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Elektronik', 'Produk elektronik dan gadget', 1, 1000, NOW(), NOW()),
(2, 1, 'Pakaian', 'Pakaian dan aksesoris', 1, 1000, NOW(), NOW()),
(3, 1, 'Makanan', 'Produk makanan dan minuman', 1, 1000, NOW(), NOW()),
(4, 1, 'Minuman', 'Berbagai jenis minuman', 1, 1000, NOW(), NOW()),
(5, 1, 'ATK', 'Alat tulis kantor', 1, 1000, NOW(), NOW()),
(6, 7, 'Elektronik', 'Produk elektronik dan gadget', 1, 999, NOW(), NOW()),
(7, 7, 'Pakaian', 'Pakaian dan aksesoris', 1, 999, NOW(), NOW()),
(8, 7, 'Makanan', 'Produk makanan dan minuman', 1, 999, NOW(), NOW()),
(9, 7, 'Minuman', 'Berbagai jenis minuman', 1, 999, NOW(), NOW());

-- =========================================
-- PRODUCTS DATA
-- =========================================
INSERT INTO `products` (`id`, `name`, `code`, `description`, `price`, `cost`, `stock`, `min_stock`, `unit`, `is_active`, `product_category_id`, `branch_id`, `tax_rate`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Gaming ASUS ROG', 'PROD001', 'Laptop gaming high performance', 15000000.00, 12000000.00, 5, 2, 'unit', 1, 1, 1000, 11.00, NOW(), NOW()),
(2, 'Kaos Polos Cotton', 'PROD002', 'Kaos polos katun premium', 75000.00, 50000.00, 50, 10, 'pcs', 1, 2, 1000, 11.00, NOW(), NOW()),
(3, 'Nasi Goreng Special', 'PROD003', 'Nasi goreng spesial dengan ayam', 25000.00, 15000.00, 100, 20, 'porsi', 1, 3, 1000, 11.00, NOW(), NOW()),
(4, 'Jus Jeruk Segar', 'PROD004', 'Jus jeruk perasan segar', 15000.00, 8000.00, 200, 30, 'gelas', 1, 4, 1000, 11.00, NOW(), NOW()),
(5, 'Pulpen Pilot', 'PROD005', 'Pulpen gel ink premium', 25000.00, 15000.00, 100, 20, 'pcs', 1, 5, 1000, 11.00, NOW(), NOW());

-- =========================================
-- CUSTOMERS DATA
-- =========================================
INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `city`, `province`, `postal_code`, `tax_id`, `credit_limit`, `is_active`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'PT. Maju Jaya', 'contact@majujaya.com', '+62-21-9876543', 'Jl. Sudirman No. 45', 'Jakarta Pusat', 'DKI Jakarta', '10220', '01.234.567.8-123.000', 50000000.00, 1, 1000, NOW(), NOW()),
(2, 'CV. Sukses Makmur', 'info@suksesmakmur.com', '+62-21-8765432', 'Jl. Thamrin No. 67', 'Jakarta Pusat', 'DKI Jakarta', '10230', '02.345.678.9-234.000', 30000000.00, 1, 1000, NOW(), NOW()),
(3, 'Toko Retail ABC', 'abc@retail.com', '+62-21-7654321', 'Jl. Malioboro No. 12', 'Yogyakarta', 'DI Yogyakarta', '55271', NULL, 15000000.00, 1, 1000, NOW(), NOW()),
(4, 'PT. Teknologi Nusantara', 'contact@teknusa.com', '+62-22-1234567', 'Jl. Asia Afrika No. 89', 'Bandung', 'Jawa Barat', '40111', '03.456.789.0-345.000', 75000000.00, 1, 1001, NOW(), NOW()),
(5, 'UD. Sumber Rejeki', 'info@sumberrejeki.com', '+62-31-9876543', 'Jl. Tunjungan No. 123', 'Surabaya', 'Jawa Timur', '60275', NULL, 25000000.00, 1, 1002, NOW(), NOW());

-- =========================================
-- TRANSACTIONS DATA (Sample)
-- =========================================
INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `category_id`, `product_id`, `amount`, `description`, `date`, `type`, `reference`, `tax_amount`, `tax_rate`, `reconciled`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, NULL, 5000000.00, 'Penjualan laptop ke PT. Maju Jaya', '2025-11-15', 'income', 'INV001', 550000.00, 11.00, 0, 1000, NOW(), NOW()),
(2, 1, 8, 4, NULL, 3000000.00, 'Pembelian bahan baku elektronik', '2025-11-10', 'expense', 'PO001', 330000.00, 11.00, 0, 1000, NOW(), NOW()),
(3, 1, 9, 5, NULL, 15000000.00, 'Gaji karyawan bulan November', '2025-11-01', 'expense', 'SLR001', 0.00, 0.00, 0, 1000, NOW(), NOW()),
(4, 1, 10, 6, NULL, 5000000.00, 'Sewa kantor bulan November', '2025-11-01', 'expense', 'RENT001', 550000.00, 11.00, 0, 1000, NOW(), NOW()),
(5, 1, 7, 2, NULL, 2000000.00, 'Jasa konsultasi IT', '2025-11-12', 'income', 'CONS001', 220000.00, 11.00, 0, 1000, NOW(), NOW());

-- =========================================
-- TRANSFERS DATA (Sample)
-- =========================================
INSERT INTO `transfers` (`id`, `from_account_id`, `to_account_id`, `amount`, `description`, `date`, `reference`, `user_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2000000.00, 'Transfer ke kas kecil untuk operasional', '2025-11-14', 'TRF001', 1, 1000, NOW(), NOW()),
(2, 3, 14, 1000000.00, 'Transfer ke cabang Bandung', '2025-11-13', 'TRF002', 1, 1000, NOW(), NOW());

-- =========================================
-- STOCK MOVEMENTS DATA (Sample)
-- =========================================
INSERT INTO `stock_movements` (`id`, `product_id`, `quantity`, `type`, `reference`, `description`, `date`, `user_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'in', 'PO001', 'Pembelian laptop ASUS ROG', '2025-11-10', 1, 1000, NOW(), NOW()),
(2, 1, -1, 'out', 'SO001', 'Penjualan laptop ke customer', '2025-11-15', 1, 1000, NOW(), NOW()),
(3, 2, 50, 'in', 'PO002', 'Pembelian kaos polos', '2025-11-08', 1, 1000, NOW(), NOW()),
(4, 3, 100, 'in', 'PO003', 'Persiapan bahan nasi goreng', '2025-11-09', 1, 1000, NOW(), NOW()),
(5, 4, 200, 'in', 'PO004', 'Persiapan jus jeruk', '2025-11-09', 1, 1000, NOW(), NOW());

-- =========================================
-- DEMO DATA (Branch 999)
-- =========================================
INSERT INTO `accounts` (`id`, `user_id`, `name`, `code`, `type`, `balance`, `description`, `is_active`, `branch_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(28, 7, 'Kas Kecil', '1101', 'asset', 5000000.00, 'Kas untuk operasional harian', 1, 999, NULL, NOW(), NOW()),
(29, 7, 'Bank BCA', '1102', 'asset', 25000000.00, 'Rekening bank utama', 1, 999, NULL, NOW(), NOW()),
(30, 7, 'Piutang Usaha', '1201', 'asset', 1500000.00, 'Piutang dari penjualan', 1, 999, NULL, NOW(), NOW()),
(31, 7, 'Persediaan Barang', '1301', 'asset', 8000000.00, 'Nilai persediaan produk', 1, 999, NULL, NOW(), NOW()),
(32, 7, 'Penjualan Produk', '4101', 'revenue', 0.00, 'Pendapatan dari penjualan produk', 1, 999, NULL, NOW(), NOW()),
(33, 7, 'Harga Pokok Penjualan', '5101', 'expense', 0.00, 'Biaya pembelian produk terjual', 1, 999, NULL, NOW(), NOW()),
(34, 7, 'Beban Operasional', '5201', 'expense', 0.00, 'Biaya operasional', 1, 999, NULL, NOW(), NOW());

INSERT INTO `categories` (`id`, `name`, `type`, `description`, `color`, `is_active`, `branch_id`, `created_at`, `updated_at`) VALUES
(10, 'Penjualan Produk', 'income', 'Pendapatan dari penjualan produk', '#10b981', 1, 999, NOW(), NOW()),
(11, 'Jasa Konsultasi', 'income', 'Pendapatan dari jasa konsultasi', '#3b82f6', 1, 999, NOW(), NOW()),
(12, 'Beli Bahan Baku', 'expense', 'Pembelian bahan baku', '#ef4444', 1, 999, NOW(), NOW()),
(13, 'Gaji Karyawan', 'expense', 'Gaji dan tunjangan karyawan', '#f59e0b', 1, 999, NOW(), NOW()),
(14, 'Sewa Kantor', 'expense', 'Biaya sewa kantor', '#6b7280', 1, 999, NOW(), NOW());



INSERT INTO `products` (`id`, `name`, `code`, `description`, `price`, `cost`, `stock`, `min_stock`, `unit`, `is_active`, `product_category_id`, `branch_id`, `tax_rate`, `created_at`, `updated_at`) VALUES
(6, 'Laptop Gaming', 'PROD001', 'Laptop gaming high performance', 15000000.00, 12000000.00, 5, 2, 'unit', 1, 6, 999, 11.00, NOW(), NOW()),
(7, 'Kaos Polos', 'PROD002', 'Kaos polos katun premium', 75000.00, 50000.00, 50, 10, 'pcs', 1, 7, 999, 11.00, NOW(), NOW()),
(8, 'Nasi Goreng Spesial', 'PROD003', 'Nasi goreng spesial dengan ayam', 25000.00, 15000.00, 100, 20, 'porsi', 1, 8, 999, 11.00, NOW(), NOW()),
(9, 'Jus Jeruk', 'PROD004', 'Jus jeruk perasan segar', 15000.00, 8000.00, 200, 30, 'gelas', 1, 9, 999, 11.00, NOW(), NOW());

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `city`, `province`, `postal_code`, `tax_id`, `credit_limit`, `is_active`, `branch_id`, `created_at`, `updated_at`) VALUES
(6, 'PT. Maju Jaya', 'contact@majujaya.com', '+62-21-9876543', 'Jl. Sudirman No. 45', 'Jakarta Pusat', 'DKI Jakarta', '10220', NULL, 50000000.00, 1, 999, NOW(), NOW()),
(7, 'CV. Sukses Makmur', 'info@suksesmakmur.com', '+62-21-8765432', 'Jl. Thamrin No. 67', 'Jakarta Pusat', 'DKI Jakarta', '10230', NULL, 30000000.00, 1, 999, NOW(), NOW()),
(8, 'Toko Retail ABC', 'abc@retail.com', '+62-21-7654321', 'Jl. Malioboro No. 12', 'Yogyakarta', 'DI Yogyakarta', '55271', NULL, 15000000.00, 1, 999, NOW(), NOW());

INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `category_id`, `product_id`, `amount`, `description`, `date`, `type`, `reference`, `tax_amount`, `tax_rate`, `reconciled`, `branch_id`, `created_at`, `updated_at`) VALUES
(6, 7, 32, 10, NULL, 5000000.00, 'Penjualan Laptop Gaming ke PT. Maju Jaya', '2025-11-15', 'income', 'INV001', 550000.00, 11.00, 0, 999, NOW(), NOW()),
(7, 7, 33, 12, NULL, 3000000.00, 'Pembelian bahan baku elektronik', '2025-11-10', 'expense', 'PO001', 330000.00, 11.00, 0, 999, NOW(), NOW()),
(8, 7, 34, 13, NULL, 15000000.00, 'Gaji karyawan bulan November', '2025-11-01', 'expense', 'SLR001', 0.00, 0.00, 0, 999, NOW(), NOW()),
(9, 7, 34, 14, NULL, 5000000.00, 'Sewa kantor bulan November', '2025-11-01', 'expense', 'RENT001', 550000.00, 11.00, 0, 999, NOW(), NOW()),
(10, 7, 32, 11, NULL, 2000000.00, 'Jasa konsultasi IT', '2025-11-12', 'income', 'CONS001', 220000.00, 11.00, 0, 999, NOW(), NOW());

INSERT INTO `transfers` (`id`, `from_account_id`, `to_account_id`, `amount`, `description`, `date`, `reference`, `user_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(3, 29, 28, 2000000.00, 'Transfer ke kas kecil untuk operasional', '2025-11-14', 'TRF001', 7, 999, NOW(), NOW());

INSERT INTO `stock_movements` (`id`, `product_id`, `quantity`, `type`, `reference`, `description`, `date`, `user_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(6, 6, 5, 'in', 'PO001', 'Pembelian laptop gaming', '2025-11-10', 7, 999, NOW(), NOW()),
(7, 6, -1, 'out', 'SO001', 'Penjualan laptop ke customer', '2025-11-15', 7, 999, NOW(), NOW()),
(8, 7, 50, 'in', 'PO002', 'Pembelian kaos polos', '2025-11-08', 7, 999, NOW(), NOW()),
(9, 8, 100, 'in', 'PO003', 'Persiapan bahan nasi goreng', '2025-11-09', 7, 999, NOW(), NOW()),
(10, 9, 200, 'in', 'PO004', 'Persiapan jus jeruk', '2025-11-09', 7, 999, NOW(), NOW());

-- =========================================
-- RESET AUTO_INCREMENT VALUES
-- =========================================
-- =========================================
-- CHART OF ACCOUNTS DATA
-- =========================================
INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `category`, `parent_id`, `balance`, `is_active`, `description`, `level`, `normal_balance`, `created_at`, `updated_at`) VALUES
(1, '1000', 'AKTIVA', 'asset', 'current_asset', NULL, 0.00, 1, 'Klasifikasi Aktiva', 1, 'debit', NOW(), NOW()),
(2, '1100', 'Kas dan Setara Kas', 'asset', 'current_asset', 1, 0.00, 1, 'Kas, bank, dan setara kas', 2, 'debit', NOW(), NOW()),
(3, '1200', 'Piutang', 'asset', 'current_asset', 1, 0.00, 1, 'Piutang usaha dan lainnya', 2, 'debit', NOW(), NOW()),
(4, '1300', 'Persediaan', 'asset', 'current_asset', 1, 0.00, 1, 'Persediaan barang dagang', 2, 'debit', NOW(), NOW()),
(5, '2000', 'KEWAJIBAN', 'liability', 'current_liability', NULL, 0.00, 1, 'Klasifikasi Kewajiban', 1, 'credit', NOW(), NOW()),
(6, '3000', 'MODAL', 'equity', 'owner_equity', NULL, 0.00, 1, 'Klasifikasi Modal', 1, 'credit', NOW(), NOW()),
(7, '4000', 'PENDAPATAN', 'revenue', 'sales_revenue', NULL, 0.00, 1, 'Klasifikasi Pendapatan', 1, 'credit', NOW(), NOW()),
(8, '5000', 'BEBAN', 'expense', 'operating_expense', NULL, 0.00, 1, 'Klasifikasi Beban', 1, 'debit', NOW(), NOW());

-- =========================================
-- SERVICES DATA
-- =========================================
INSERT INTO `services` (`id`, `user_id`, `product_category_id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Konsultasi IT', 'Layanan konsultasi teknologi informasi', 500000.00, NOW(), NOW()),
(2, 1, 1, 'Maintenance Komputer', 'Layanan perbaikan dan maintenance komputer', 300000.00, NOW(), NOW()),
(3, 1, 2, 'Desain Grafis', 'Layanan desain logo dan branding', 750000.00, NOW(), NOW()),
(4, 7, 6, 'Konsultasi IT Demo', 'Layanan konsultasi teknologi informasi (Demo)', 500000.00, NOW(), NOW());

-- =========================================
-- TAX SETTINGS DATA
-- =========================================
INSERT INTO `tax_settings` (`id`, `user_id`, `name`, `rate`, `type`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'PPN 11%', 11.00, 'percent', 1000, NOW(), NOW()),
(2, 1, 'PPh 21', 5.00, 'percent', 1000, NOW(), NOW()),
(3, 3, 'PPN 11%', 11.00, 'percent', 1001, NOW(), NOW()),
(4, 4, 'PPN 11%', 11.00, 'percent', 1002, NOW(), NOW()),
(5, 7, 'PPN 11%', 11.00, 'percent', 999, NOW(), NOW());

-- =========================================
-- SPATIE PERMISSION DATA
-- =========================================
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', NOW(), NOW()),
(2, 'admin', 'web', NOW(), NOW()),
(3, 'branch_manager', 'web', NOW(), NOW()),
(4, 'kasir', 'web', NOW(), NOW()),
(5, 'inventory_manager', 'web', NOW(), NOW()),
(6, 'auditor', 'web', NOW(), NOW()),
(7, 'user', 'web', NOW(), NOW());

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage_users', 'web', NOW(), NOW()),
(2, 'manage_settings', 'web', NOW(), NOW()),
(3, 'view_reports', 'web', NOW(), NOW()),
(4, 'manage_branch', 'web', NOW(), NOW()),
(5, 'create_transactions', 'web', NOW(), NOW()),
(6, 'view_inventory', 'web', NOW(), NOW()),
(7, 'manage_products', 'web', NOW(), NOW()),
(8, 'manage_inventory', 'web', NOW(), NOW()),
(9, 'audit_transactions', 'web', NOW(), NOW()),
(10, 'view_own_data', 'web', NOW(), NOW());

-- Assign permissions to roles
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
-- Super Admin - All permissions
(1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1), (9, 1), (10, 1),
-- Admin - Most permissions except full system settings
(1, 2), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2), (8, 2), (9, 2), (10, 2),
-- Branch Manager - Branch management permissions
(3, 3), (4, 3), (5, 3), (6, 3), (7, 3), (8, 3), (10, 3),
-- Kasir - Transaction permissions
(5, 4), (6, 4), (10, 4),
-- Inventory Manager - Inventory permissions
(3, 5), (6, 5), (7, 5), (8, 5), (10, 5),
-- Auditor - View permissions
(3, 6), (6, 6), (9, 6), (10, 6),
-- User - Basic permissions
(10, 7);

-- Assign roles to users
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),  -- Super Admin
(2, 'App\\Models\\User', 2),  -- Admin Pusat
(3, 'App\\Models\\User', 3),  -- Manager Bandung
(4, 'App\\Models\\User', 4),  -- Kasir Surabaya
(5, 'App\\Models\\User', 5),  -- Inventory Staff
(6, 'App\\Models\\User', 6),  -- Auditor
(2, 'App\\Models\\User', 7);  -- Demo User (admin role)

-- =========================================
-- RESET AUTO_INCREMENT VALUES
-- =========================================
-- =========================================
-- RESET AUTO_INCREMENT VALUES
-- =========================================
-- =========================================
-- RESET AUTO_INCREMENT VALUES
-- =========================================
-- =========================================
-- RESET AUTO_INCREMENT VALUES
-- =========================================
ALTER TABLE users AUTO_INCREMENT = 8;
ALTER TABLE user_roles AUTO_INCREMENT = 8;
ALTER TABLE branches AUTO_INCREMENT = 1003;
ALTER TABLE user_branches AUTO_INCREMENT = 10;
ALTER TABLE accounts AUTO_INCREMENT = 35;
ALTER TABLE categories AUTO_INCREMENT = 15;
ALTER TABLE chart_of_accounts AUTO_INCREMENT = 9;
ALTER TABLE product_categories AUTO_INCREMENT = 10;
ALTER TABLE products AUTO_INCREMENT = 10;
ALTER TABLE services AUTO_INCREMENT = 5;
ALTER TABLE customers AUTO_INCREMENT = 9;
ALTER TABLE tax_settings AUTO_INCREMENT = 6;
ALTER TABLE journal_entries AUTO_INCREMENT = 1;
ALTER TABLE journal_lines AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 11;
ALTER TABLE transfers AUTO_INCREMENT = 4;
ALTER TABLE stock_movements AUTO_INCREMENT = 11;
ALTER TABLE roles AUTO_INCREMENT = 8;
ALTER TABLE permissions AUTO_INCREMENT = 11;