# 🚀 SISTEM AKUNTANSI SIBUKU - MYSQL SETUP GUIDE

## 📋 **OVERVIEW**
Panduan lengkap untuk setup Sistem Akuntansi Sibuku dengan MySQL database untuk production deployment.

---

## 🛠️ **PRASYARAT SISTEM**

### **Server Requirements:**
- **PHP**: 8.2 atau lebih tinggi
- **MySQL**: 8.0 atau lebih tinggi (MariaDB 10.6+)
- **Web Server**: Apache/Nginx
- **RAM**: Minimum 2GB (4GB recommended)
- **Storage**: Minimum 10GB free space

### **PHP Extensions:**
```bash
# Required extensions
php-mysql
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-intl
php-bcmath
```

---

## 📦 **INSTALASI STEP BY STEP**

### **Step 1: Download & Extract**
```bash
# Clone repository
git clone https://github.com/your-repo/akuntansi-sibuku.git
cd akuntansi-sibuku

# Atau download ZIP dan extract
unzip akuntansi-sibuku.zip
cd akuntansi-sibuku
```

### **Step 2: Install Dependencies**
```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies (optional, for asset compilation)
npm install
npm run build
```

### **Step 3: Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### **Step 4: Database Setup**

#### **Option A: Import SQL Files (Recommended)**
```bash
# Create MySQL database
mysql -u root -p
CREATE DATABASE akuntansi_sibuku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Import schema
mysql -u root -p akuntansi_sibuku < database_schema.sql

# Import seed data
mysql -u root -p akuntansi_sibuku < database_seed.sql
```

#### **Option B: Laravel Migrations**
```bash
# Configure .env database settings
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=akuntansi_sibuku
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed data
php artisan db:seed
```

### **Step 5: Storage & Permissions**
```bash
# Create storage link
php artisan storage:link

# Set proper permissions (Linux/Unix)
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/

# Windows (if using IIS)
# icacls storage /grant "IIS_IUSRS":(OI)(CI)F /T
```

### **Step 6: Final Setup**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set application permissions
chmod +x artisan
```

---

## ⚙️ **KONFIGURASI ENVIRONMENT**

### **File .env Utama:**
```env
# Application
APP_NAME="Sistem Akuntansi Sibuku"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akuntansi_sibuku
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=7200

# Queue (optional)
QUEUE_CONNECTION=database

# Mail (configure SMTP)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Sistem Akuntansi Sibuku"

# Security
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

### **MySQL Optimization (.my.cnf atau my.ini):**
```ini
[mysqld]
# Basic Settings
innodb_buffer_pool_size=256M
innodb_log_file_size=64M
max_connections=200
query_cache_size=64M
query_cache_type=1

# Performance Settings
innodb_flush_log_at_trx_commit=2
innodb_flush_method=O_DIRECT
innodb_thread_concurrency=8

# Security Settings
sql_mode=STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
```

---

## 🌐 **WEB SERVER CONFIGURATION**

### **Apache (httpd.conf atau .htaccess):**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/akuntansi-sibuku/public

    <Directory /path/to/akuntansi-sibuku/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/akuntansi_error.log
    CustomLog ${APACHE_LOG_DIR}/akuntansi_access.log combined
</VirtualHost>
```

### **Nginx (nginx.conf):**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/akuntansi-sibuku/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🔒 **SECURITY HARDENING**

### **Step 1: SSL Certificate**
```bash
# Using Let's Encrypt (Certbot)
sudo certbot --apache -d yourdomain.com

# Or manual SSL configuration
# Configure SSL in Apache/Nginx config
```

### **Step 2: Security Headers**
Tambahkan ke `.htaccess` atau nginx config:
```
# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'"
```

### **Step 3: Database Security**
```sql
-- Create dedicated database user
CREATE USER 'sibuku_user'@'localhost' IDENTIFIED BY 'secure_password_123';
GRANT ALL PRIVILEGES ON akuntansi_sibuku.* TO 'sibuku_user'@'localhost';
FLUSH PRIVILEGES;

-- Remove anonymous users and test databases
DELETE FROM mysql.user WHERE User = '';
DROP DATABASE IF EXISTS test;
```

### **Step 4: File Permissions**
```bash
# Secure sensitive files
chmod 600 .env
chmod 600 storage/logs/*.log
chmod 644 storage/framework/cache/data/*
```

---

## 🔄 **SCHEDULED TASKS & CRON JOBS**

### **Setup Cron Jobs:**
```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd /path/to/akuntansi-sibuku && php artisan schedule:run >> /dev/null 2>&1
```

### **Scheduled Commands:**
- **Every 5 minutes**: Unlock expired accounts
- **Daily**: Process recurring templates
- **Weekly**: Generate backup reports

### **Queue Worker (Optional):**
```bash
# For background processing
php artisan queue:work --sleep=3 --tries=3 --max-jobs=1000
```

---

## 📊 **MONITORING & LOGGING**

### **Log Configuration:**
```php
// config/logging.php
'channels' => [
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'error'),
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'error'),
        'days' => 30,
    ],
],
```

### **Monitoring Commands:**
```bash
# Check application status
php artisan tinker
>>> echo "Application is running";

# Monitor queues
php artisan queue:status

# Clear expired cache
php artisan cache:clear-expired
```

---

## 🔧 **MAINTENANCE & BACKUP**

### **Automated Backup Script:**
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backups"
DB_NAME="akuntansi_sibuku"
DB_USER="sibuku_user"
DB_PASS="your_password"

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /path/to/akuntansi-sibuku/storage/

# Clean old backups (keep last 30 days)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

### **Maintenance Commands:**
```bash
# Weekly maintenance
php artisan tinker
>>> \App\Models\User::where('locked_until', '<', now())->update(['locked_until' => null]);

# Database optimization
php artisan tinker
>>> DB::statement('OPTIMIZE TABLE users, accounts, transactions, products');

# Clear old logs
php artisan tinker
>>> \Illuminate\Support\Facades\Storage::delete(\Illuminate\Support\Facades\Storage::files('logs'));
```

---

## 🚨 **TROUBLESHOOTING**

### **Common Issues:**

#### **1. Database Connection Error**
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection
mysql -u sibuku_user -p akuntansi_sibuku

# Check .env configuration
php artisan config:clear
```

#### **2. Permission Errors**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/

# SELinux issues (CentOS/RHEL)
sudo setsebool -P httpd_can_network_connect 1
```

#### **3. 500 Internal Server Error**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check PHP errors
php -l app/Http/Controllers/DashboardController.php

# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```

#### **4. Slow Performance**
```bash
# Check MySQL slow queries
mysql -e "SHOW PROCESSLIST;"

# Optimize database
php artisan tinker
>>> DB::statement('ANALYZE TABLE transactions, accounts, products');

# Check memory usage
php artisan tinker
>>> echo 'Memory: ' . memory_get_peak_usage(true) / 1024 / 1024 . ' MB';
```

---

## 📞 **SUPPORT & CONTACT**

### **Default Login Credentials:**
- **Email**: admin@sibuku.com
- **Password**: password123
- ⚠️ **CHANGE PASSWORD IMMEDIATELY AFTER FIRST LOGIN!**

### **System Information:**
- **Version**: 1.0.0
- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0+
- **PHP**: 8.2+

### **Documentation:**
- 📖 [User Manual](docs/user-manual.md)
- 🛠️ [API Documentation](docs/api.md)
- 🔒 [Security Guide](docs/security.md)

---

## ✅ **POST-INSTALLATION CHECKLIST**

- [ ] ✅ Database imported successfully
- [ ] ✅ Environment configured
- [ ] ✅ Storage permissions set
- [ ] ✅ Application key generated
- [ ] ✅ Default admin account accessible
- [ ] ✅ SSL certificate installed
- [ ] ✅ Cron jobs configured
- [ ] ✅ Backup script scheduled
- [ ] ✅ Security hardening applied
- [ ] ✅ Performance monitoring enabled

---

## 🎯 **FINAL NOTES**

### **Production Readiness:**
✅ **Database**: MySQL with proper indexing and optimization
✅ **Security**: Enterprise-grade security measures
✅ **Performance**: Optimized queries and caching
✅ **Scalability**: Multi-tenant architecture ready
✅ **Monitoring**: Comprehensive logging and monitoring
✅ **Backup**: Automated backup procedures

### **Next Steps:**
1. **Change Default Password** - Immediate priority
2. **Configure Email** - For notifications and reports
3. **Set up Monitoring** - Server monitoring and alerts
4. **Configure Backups** - Regular automated backups
5. **Security Audit** - Regular security assessments

---

**🎉 SISTEM AKUNTANSI SIBUKU SIAP DIGUNAKAN DALAM PRODUCTION ENVIRONMENT!**

**Support**: Jika mengalami masalah, periksa log files dan dokumentasi troubleshooting di atas.