# 🚀 **SISTEM AKUNTANSI SIBUKU - XAMPP SETUP GUIDE**

## 📋 **OVERVIEW**
Panduan lengkap setup Sistem Akuntansi Sibuku menggunakan XAMPP (Apache + MySQL + PHP) untuk development environment.

---

## 🛠️ **PRASYARAT**

### **Software yang Dibutuhkan:**
- ✅ **XAMPP** (terbaru - versi 8.2+ recommended)
- ✅ **Composer** (untuk dependency management)
- ✅ **Node.js & NPM** (untuk frontend assets - optional)
- ✅ **Git** (untuk cloning repository)

### **System Requirements:**
- **OS**: Windows 7/8/10/11
- **RAM**: Minimum 4GB (8GB recommended)
- **Storage**: Minimum 5GB free space
- **Browser**: Chrome/Firefox/Edge (terbaru)

---

## 📦 **INSTALASI STEP BY STEP**

### **Step 1: Install XAMPP**
```bash
# Download XAMPP dari https://www.apachefriends.org/
# Install dengan komponen default (Apache, MySQL, PHP, phpMyAdmin)
# Jalankan XAMPP Control Panel sebagai Administrator
```

### **Step 2: Start XAMPP Services**
1. **Buka XAMPP Control Panel**
2. **Start Apache** (Port 80)
3. **Start MySQL** (Port 3306)
4. **Verifikasi**: Buka `http://localhost` di browser

### **Step 3: Setup Project**
```bash
# 1. Buat folder project di htdocs
cd C:\xampp\htdocs
mkdir akuntansi_sibuku
cd akuntansi_sibuku

# 2. Clone atau extract project files
# (Copy semua files project ke folder ini)

# 3. Install PHP dependencies
composer install --no-dev

# 4. Install Node.js dependencies (optional)
npm install
npm run build
```

### **Step 4: Database Setup**

#### **Option A: Import SQL Files (Recommended)**
```bash
# 1. Buka phpMyAdmin: http://localhost/phpmyadmin

# 2. Create database
# - Klik "New" di sidebar kiri
# - Database name: akuntansi_sibuku
# - Collation: utf8mb4_unicode_ci
# - Klik "Create"

# 3. Import schema
# - Pilih database "akuntansi_sibuku"
# - Klik tab "Import"
# - Pilih file "database_schema.sql"
# - Klik "Go"

# 4. Import seed data
# - Pilih file "database_seed.sql"
# - Klik "Go"
```

#### **Option B: Via Command Line**
```bash
# Buka Command Prompt sebagai Administrator
cd C:\xampp\mysql\bin

# Login ke MySQL
mysql -u root -p

# Create database
CREATE DATABASE akuntansi_sibuku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Import files
mysql -u root akuntansi_sibuku < C:\xampp\htdocs\akuntansi_sibuku\database_schema.sql
mysql -u root akuntansi_sibuku < C:\xampp\htdocs\akuntansi_sibuku\database_seed.sql
```

### **Step 5: Environment Configuration**
```bash
# 1. Copy .env file
copy .env.example .env

# 2. Generate application key
php artisan key:generate

# 3. Configure .env for XAMPP (sudah dikonfigurasi)
# File .env sudah diatur untuk XAMPP dengan:
# - DB_HOST=127.0.0.1
# - DB_PORT=3306
# - DB_USERNAME=root
# - DB_PASSWORD= (kosong)
```

### **Step 6: Final Setup**
```bash
# Set permissions (Windows - biasanya sudah OK)
# Create storage link
php artisan storage:link

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production (optional)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ⚙️ **FILE .ENV UNTUK XAMPP**

File `.env` sudah dikonfigurasi khusus untuk XAMPP:

```env
# =========================================
# SISTEM AKUNTANSI SIBUKU - ENVIRONMENT CONFIG
# =========================================
# Konfigurasi untuk MySQL/XAMPP
# =========================================

# Application Configuration
APP_NAME="Sistem Akuntansi Sibuku"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost/akuntansi_sibuku

# Localization
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

# Database (XAMPP MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akuntansi_sibuku
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=7200
CACHE_STORE=file

# Queue
QUEUE_CONNECTION=database

# Mail (Development)
MAIL_MAILER=log

# Security Settings
APP_MAX_LOGIN_ATTEMPTS=5
APP_LOCKOUT_DURATION=15
APP_SESSION_TIMEOUT=7200
APP_PASSWORD_MIN_LENGTH=8

# Business Settings
APP_DEFAULT_TAX_RATE=11.00
APP_ENABLE_INVENTORY=true
APP_ENABLE_MULTI_BRANCH=true
APP_ENABLE_APPROVALS=true
```

---

## 🌐 **AKSES APLIKASI**

### **URL Access:**
- **Main Application**: `http://localhost/akuntansi_sibuku`
- **phpMyAdmin**: `http://localhost/phpmyadmin`
- **XAMPP Dashboard**: `http://localhost/xampp`

### **Default Login Credentials:**
- **URL**: `http://localhost/akuntansi_sibuku/login`
- **Email**: `admin@sibuku.com`
- **Password**: `password123`
- ⚠️ **UBAH PASSWORD SEGERA SETELAH LOGIN PERTAMA!**

---

## 🔧 **TROUBLESHOOTING XAMPP**

### **1. Port Conflicts**
```bash
# Jika Apache/MySQL tidak start karena port conflict:
# 1. Stop IIS: net stop was /y
# 2. Stop SQL Server: net stop mssqlserver /y
# 3. Stop Skype/other apps using port 80/3306
```

### **2. MySQL Connection Issues**
```bash
# Test MySQL connection
mysql -u root -p

# If connection fails:
# 1. Check MySQL service is running in XAMPP
# 2. Verify root password (default: empty)
# 3. Check firewall blocking port 3306
```

### **3. PHP Extensions Missing**
```bash
# Check PHP extensions in XAMPP
# 1. Open XAMPP Control Panel
# 2. Click "Config" -> "PHP (php.ini)"
# 3. Ensure these extensions are enabled:
#    - extension=pdo_mysql
#    - extension=mysql
#    - extension=mbstring
#    - extension=xml
#    - extension=curl
#    - extension=zip
#    - extension=gd
#    - extension=intl
#    - extension=bcmath
```

### **4. Permission Issues**
```bash
# Windows permission issues:
# 1. Run Command Prompt as Administrator
# 2. Run XAMPP as Administrator
# 3. Check folder permissions on htdocs
```

### **5. Laravel Errors**
```bash
# Common Laravel issues:
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check Laravel logs:
tail storage/logs/laravel.log
```

---

## 📊 **VERIFIKASI INSTALASI**

### **Step 1: Test Database Connection**
```bash
# Test Laravel database connection
php artisan tinker

# In tinker:
DB::connection()->getPdo();
echo "Database connected successfully!";

# Check tables exist:
\DB::select('SHOW TABLES');
exit
```

### **Step 2: Test Application**
1. **Buka browser**: `http://localhost/akuntansi_sibuku`
2. **Login** dengan credentials default
3. **Test features**:
   - ✅ Dashboard loads
   - ✅ Menu navigation works
   - ✅ Create sample transaction
   - ✅ Generate report

### **Step 3: Test Key Features**
```bash
# Test artisan commands
php artisan --version
php artisan route:list | head -20
php artisan migrate:status
```

---

## 🔄 **SCHEDULED TASKS (CRON JOBS)**

### **Setup Cron Jobs di Windows:**
```bash
# 1. Create batch file: C:\xampp\htdocs\akuntansi_sibuku\cron.bat
@echo off
cd C:\xampp\htdocs\akuntansi_sibuku
php artisan schedule:run >> storage/logs/cron.log 2>&1

# 2. Setup Windows Task Scheduler:
# - Search "Task Scheduler"
# - Create Basic Task
# - Name: "Laravel Schedule Run"
# - Trigger: Daily, every 1 day, repeat every 1 minute
# - Action: Start a program
# - Program: C:\xampp\htdocs\akuntansi_sibuku\cron.bat
```

### **Manual Testing:**
```bash
# Test scheduled commands manually
php artisan accounts:unlock-expired
php artisan app:process-recurring-templates
php artisan schedule:run
```

---

## 📁 **FOLDER STRUCTURE XAMPP**

```
C:\xampp\
├── htdocs\
│   └── akuntansi_sibuku\          # Your Laravel project
│       ├── app\
│       ├── bootstrap\
│       ├── config\
│       ├── database\
│       │   ├── database_schema.sql    # ✅ Created
│       │   └── database_seed.sql      # ✅ Created
│       ├── public\                    # Web root
│       ├── resources\
│       ├── routes\
│       ├── storage\
│       ├── tests\
│       ├── .env                       # ✅ Configured
│       ├── artisan
│       ├── composer.json
│       └── README_MYSQL_SETUP.md      # ✅ Created
├── mysql\                             # MySQL database files
│   └── data\
│       └── akuntansi_sibuku\          # Your database
└── php\                               # PHP binaries
```

---

## 🚀 **PRODUCTION DEPLOYMENT**

### **When Ready for Production:**
1. **Change .env settings**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Setup SSL Certificate** (Let's Encrypt recommended)

3. **Configure proper MySQL user**:
   ```sql
   CREATE USER 'sibuku_prod'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON akuntansi_sibuku.* TO 'sibuku_prod'@'localhost';
   FLUSH PRIVILEGES;
   ```

4. **Enable OPcache** in PHP configuration

5. **Setup backup automation**

---

## 📞 **SUPPORT & HELP**

### **Quick Commands:**
```bash
# Check PHP version
php --version

# Check Composer
composer --version

# Check Laravel
php artisan --version

# Check MySQL connection
php artisan tinker
DB::connection()->getPdo();
exit
```

### **Common Issues & Solutions:**

#### **Issue: "Class not found"**
```bash
composer dump-autoload
```

#### **Issue: "Permission denied"**
```bash
# Run Command Prompt as Administrator
# Check folder permissions
```

#### **Issue: "Database connection failed"**
```bash
# Check MySQL is running in XAMPP
# Verify database exists in phpMyAdmin
# Check .env database settings
```

#### **Issue: "Route not found"**
```bash
php artisan route:clear
php artisan route:cache
```

---

## ✅ **POST-INSTALLATION CHECKLIST**

- [ ] ✅ XAMPP installed and running
- [ ] ✅ Apache & MySQL services started
- [ ] ✅ Project files copied to htdocs/akuntansi_sibuku
- [ ] ✅ Composer dependencies installed
- [ ] ✅ Database created and imported
- [ ] ✅ .env file configured
- [ ] ✅ Application key generated
- [ ] ✅ Storage link created
- [ ] ✅ Application accessible at localhost
- [ ] ✅ Admin login works
- [ ] ✅ Sample data visible
- [ ] ✅ All menus functional

---

## 🎯 **FINAL NOTES**

### **Development URLs:**
- **Application**: `http://localhost/akuntansi_sibuku`
- **phpMyAdmin**: `http://localhost/phpmyadmin`
- **XAMPP Dashboard**: `http://localhost/xampp`

### **Important Security Steps:**
1. **Change default admin password** immediately
2. **Configure proper MySQL user** instead of root
3. **Set up SSL** for production
4. **Regular backups** of database
5. **Keep XAMPP updated**

### **Performance Tips:**
1. **Enable OPcache** in PHP settings
2. **Use SSD storage** for better performance
3. **Increase PHP memory_limit** if needed
4. **Monitor MySQL slow queries**

---

**🎉 SISTEM AKUNTANSI SIBUKU SIAP DIGUNAKAN DENGAN XAMPP!**

**Default Login:**
- URL: `http://localhost/akuntansi_sibuku/login`
- Email: `admin@sibuku.com`
- Password: `password123`

**Happy coding! 🚀**