# 🚀 SISTEM AKUNTANSI SIBUKU - PRODUCTION DEPLOYMENT

## 📋 Prerequisites

### System Requirements
- **PHP**: 8.1 or higher
- **MySQL/MariaDB**: 5.7 or higher
- **Web Server**: Apache/Nginx with mod_rewrite
- **Composer**: Latest version
- **Node.js**: 16+ (for assets compilation)

### Server Requirements
- **RAM**: Minimum 2GB
- **Storage**: Minimum 5GB
- **PHP Extensions**:
  - pdo_mysql
  - mbstring
  - openssl
  - tokenizer
  - xml
  - ctype
  - json
  - bcmath
  - fileinfo

---

## 🛠️ DEPLOYMENT STEPS

### 1. Server Preparation

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y apache2 mysql-server php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-fileinfo

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 2. Database Setup

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE akuntansi_sibuku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sibuku_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON akuntansi_sibuku.* TO 'sibuku_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Clone or upload application files
cd /var/www/html
git clone https://github.com/your-repo/akuntansi-sibuku.git
cd akuntansi-sibuku

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm install
npm run build

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/akuntansi-sibuku
sudo chmod -R 755 /var/www/html/akuntansi-sibuku
sudo chmod -R 775 /var/www/html/akuntansi-sibuku/storage
sudo chmod -R 775 /var/www/html/akuntansi-sibuku/bootstrap/cache
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Edit .env file with production values
nano .env
```

**Production .env Configuration:**
```env
APP_NAME="Sistem Akuntansi Sibuku"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akuntansi_sibuku
DB_USERNAME=sibuku_user
DB_PASSWORD=your_secure_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@sibuku.com
MAIL_FROM_NAME="Sistem Akuntansi Sibuku"
```

### 5. Database Migration & Seeding

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed production data
php artisan db:seed --class=ProductionSeeder

# OR import SQL files directly
mysql -u sibuku_user -p akuntansi_sibuku < database_schema_production.sql
mysql -u sibuku_user -p akuntansi_sibuku < database_seed_production.sql
```

### 6. Web Server Configuration

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/akuntansi_sibuku/public

    <Directory /var/www/html/akuntansi_sibuku/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sibuku_error.log
    CustomLog ${APACHE_LOG_DIR}/sibuku_access.log combined
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/akuntansi_sibuku/public;

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
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. SSL Configuration (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL certificate
sudo certbot --apache -d yourdomain.com

# Set up auto-renewal
sudo crontab -e
# Add this line:
# 0 12 * * * /usr/bin/certbot renew --quiet
```

### 8. Final Setup

```bash
# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set up cron jobs for automated tasks
crontab -e
# Add these lines:
# * * * * * cd /var/www/html/akuntansi_sibuku && php artisan schedule:run >> /dev/null 2>&1
# 0 2 * * * cd /var/www/html/akuntansi_sibuku && php artisan demo:reset >> /dev/null 2>&1

# Restart services
sudo systemctl restart apache2
sudo systemctl restart mysql
```

---

## 🔐 SECURITY CHECKLIST

### Pre-Deployment
- [ ] Change default database password
- [ ] Set strong application key
- [ ] Configure proper file permissions
- [ ] Disable debug mode in production
- [ ] Set up SSL certificate

### Post-Deployment
- [ ] Test all user logins
- [ ] Verify demo mode functionality
- [ ] Check branch isolation
- [ ] Test report generation
- [ ] Verify email functionality

---

## 📊 PRODUCTION MONITORING

### Key Metrics to Monitor
- **Application Performance**: Response times, error rates
- **Database Performance**: Query execution times, connection counts
- **User Activity**: Login attempts, active sessions
- **Storage Usage**: Database size, log file sizes
- **Demo Usage**: Demo account activity, reset frequency

### Log Files to Monitor
```
/var/log/apache2/sibuku_error.log
/var/log/apache2/sibuku_access.log
/var/www/html/akuntansi_sibuku/storage/logs/laravel.log
```

---

## 🔧 MAINTENANCE TASKS

### Daily Tasks
```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset demo data (if needed)
php artisan demo:reset
```

### Weekly Tasks
```bash
# Backup database
mysqldump -u sibuku_user -p akuntansi_sibuku > backup_$(date +%Y%m%d).sql

# Clear old logs
php artisan log:clear
```

### Monthly Tasks
```bash
# Update dependencies
composer update --no-dev
npm update && npm run build

# Database optimization
php artisan db:monitor
```

---

## 🚨 TROUBLESHOOTING

### Common Issues

#### 1. Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/html/akuntansi_sibuku
sudo chmod -R 755 /var/www/html/akuntansi_sibuku
sudo chmod -R 775 /var/www/html/akuntansi_sibuku/storage
```

#### 2. Database Connection Issues
```bash
# Check MySQL service
sudo systemctl status mysql

# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

#### 3. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/html/akuntansi_sibuku/storage/logs/laravel.log

# Check PHP error logs
tail -f /var/log/php8.1-fpm.log
```

#### 4. Demo Mode Not Working
```bash
# Check demo user exists
php artisan tinker
User::where('demo_mode', true)->first();

# Reset demo data
php artisan demo:reset
```

---

## 📞 SUPPORT & CONTACT

### Emergency Contacts
- **Technical Support**: admin@sibuku.com
- **System Administrator**: superadmin@sibuku.com
- **Documentation**: https://docs.sibuku.com

### System Information
- **Version**: 1.0.0 Production
- **Release Date**: 2025-11-22
- **PHP Version**: 8.1+
- **Laravel Version**: 11.x
- **Database**: MySQL 5.7+

---

## ✅ DEPLOYMENT CHECKLIST

- [ ] Server requirements met
- [ ] Database created and configured
- [ ] Application files uploaded
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Database migrated and seeded
- [ ] Web server configured
- [ ] SSL certificate installed
- [ ] Permissions set correctly
- [ ] Application tested
- [ ] Monitoring set up
- [ ] Backup strategy implemented

---

**🎉 Your Akuntansi Sibuku system is now ready for production!**