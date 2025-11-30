@echo off
REM =========================================
REM SISTEM AKUNTANSI SIBUKU - XAMPP SETUP SCRIPT
REM =========================================
REM Automated setup script for Windows/XAMPP
REM =========================================

echo =========================================
echo SISTEM AKUNTANSI SIBUKU - XAMPP SETUP
echo =========================================
echo.

REM Check if running as Administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo ✓ Running as Administrator
) else (
    echo ✗ Please run as Administrator
    pause
    exit /b 1
)

echo.
echo Checking prerequisites...

REM Check if XAMPP is installed
if exist "C:\xampp\mysql\bin\mysql.exe" (
    echo ✓ XAMPP MySQL found
) else (
    echo ✗ XAMPP not found. Please install XAMPP first.
    echo Download from: https://www.apachefriends.org/
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorLevel% == 0 (
    echo ✓ Composer found
) else (
    echo ✗ Composer not found. Please install Composer first.
    echo Download from: https://getcomposer.org/
    pause
    exit /b 1
)

REM Check if Node.js is installed (optional)
node --version >nul 2>&1
if %errorLevel% == 0 (
    echo ✓ Node.js found
    set NODE_INSTALLED=1
) else (
    echo ! Node.js not found (optional for frontend assets)
    set NODE_INSTALLED=0
)

echo.
echo Setting up project...

REM Create database
echo Creating database...
"C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS akuntansi_sibuku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorLevel% == 0 (
    echo ✓ Database created successfully
) else (
    echo ✗ Failed to create database
    pause
    exit /b 1
)

REM Import schema
echo Importing database schema...
"C:\xampp\mysql\bin\mysql.exe" -u root akuntansi_sibuku < database_schema.sql

if %errorLevel% == 0 (
    echo ✓ Schema imported successfully
) else (
    echo ✗ Failed to import schema
    pause
    exit /b 1
)

REM Import seed data
echo Importing seed data...
"C:\xampp\mysql\bin\mysql.exe" -u root akuntansi_sibuku < database_seed.sql

if %errorLevel% == 0 (
    echo ✓ Seed data imported successfully
) else (
    echo ✗ Failed to import seed data
    pause
    exit /b 1
)

REM Install PHP dependencies
echo Installing PHP dependencies...
composer install --no-dev --optimize-autoloader

if %errorLevel% == 0 (
    echo ✓ PHP dependencies installed
) else (
    echo ✗ Failed to install PHP dependencies
    pause
    exit /b 1
)

REM Install Node.js dependencies (if available)
if %NODE_INSTALLED% == 1 (
    echo Installing Node.js dependencies...
    npm install

    if %errorLevel% == 0 (
        echo ✓ Node.js dependencies installed
        echo Building frontend assets...
        npm run build

        if %errorLevel% == 0 (
            echo ✓ Frontend assets built
        ) else (
            echo ! Failed to build frontend assets (non-critical)
        )
    ) else (
        echo ! Failed to install Node.js dependencies (optional)
    )
) else (
    echo ! Skipping Node.js setup (not installed)
)

REM Generate application key
echo Generating application key...
php artisan key:generate

if %errorLevel% == 0 (
    echo ✓ Application key generated
) else (
    echo ✗ Failed to generate application key
    pause
    exit /b 1
)

REM Create storage link
echo Creating storage link...
php artisan storage:link

if %errorLevel% == 0 (
    echo ✓ Storage link created
) else (
    echo ! Failed to create storage link (may already exist)
)

REM Clear caches
echo Clearing application caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

if %errorLevel% == 0 (
    echo ✓ Caches cleared
) else (
    echo ! Failed to clear caches
)

REM Test database connection
echo Testing database connection...
php artisan tinker --execute="try { DB::connection()->getPdo(); echo '✓ Database connection successful'; } catch(Exception \$e) { echo '✗ Database connection failed: ' . \$e->getMessage(); exit(1); } exit();"

if %errorLevel% == 0 (
    echo ✓ Database connection test passed
) else (
    echo ✗ Database connection test failed
    pause
    exit /b 1
)

echo.
echo =========================================
echo SETUP COMPLETED SUCCESSFULLY!
echo =========================================
echo.
echo Your application is ready at:
echo http://localhost/akuntansi_sibuku
echo.
echo Default login credentials:
echo Email: admin@sibuku.com
echo Password: password123
echo.
echo ⚠️  IMPORTANT: Change the default password immediately!
echo.
echo Next steps:
echo 1. Start XAMPP (Apache + MySQL)
echo 2. Open http://localhost/akuntansi_sibuku in your browser
echo 3. Login with the credentials above
echo 4. Change the default password in Settings
echo.
echo For detailed documentation, see:
echo - README_MYSQL_SETUP.md
echo - XAMPP_SETUP_GUIDE.md
echo.
pause