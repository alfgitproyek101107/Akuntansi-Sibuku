#!/bin/bash

# =========================================
# SISTEM AKUNTANSI SIBUKU - PRODUCTION SETUP SCRIPT
# =========================================
# Automated Production Deployment Script
# Created: 2025-11-22
# Version: 1.0.0
# =========================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration variables
APP_NAME="akuntansi_sibuku"
DB_NAME="akuntansi_sibuku"
DB_USER="sibuku_user"
DB_PASS="Sibuku2025!"
DOMAIN_NAME=""
ADMIN_EMAIL="admin@sibuku.com"

# Functions
print_header() {
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}  $1${NC}"
    echo -e "${BLUE}========================================${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

check_root() {
    if [[ $EUID -eq 0 ]]; then
        print_error "This script should not be run as root"
        exit 1
    fi
}

check_os() {
    if [[ "$OSTYPE" != "linux-gnu"* ]]; then
        print_error "This script is designed for Linux systems only"
        exit 1
    fi
}

install_dependencies() {
    print_header "INSTALLING SYSTEM DEPENDENCIES"

    # Update system
    print_warning "Updating system packages..."
    sudo apt update && sudo apt upgrade -y

    # Install required packages
    print_warning "Installing required packages..."
    sudo apt install -y \
        apache2 \
        mysql-server \
        php8.1 \
        php8.1-cli \
        php8.1-common \
        php8.1-mysql \
        php8.1-zip \
        php8.1-gd \
        php8.1-mbstring \
        php8.1-curl \
        php8.1-xml \
        php8.1-bcmath \
        php8.1-fileinfo \
        curl \
        wget \
        git \
        unzip \
        nodejs \
        npm

    print_success "System dependencies installed"
}

setup_database() {
    print_header "SETTING UP DATABASE"

    # Secure MySQL installation
    print_warning "Securing MySQL installation..."
    sudo mysql_secure_installation

    # Create database and user
    print_warning "Creating database and user..."
    sudo mysql -e "
        CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
        GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
        FLUSH PRIVILEGES;
    "

    print_success "Database setup completed"
}

setup_application() {
    print_header "SETTING UP APPLICATION"

    # Create application directory
    if [ ! -d "/var/www/html/${APP_NAME}" ]; then
        print_warning "Creating application directory..."
        sudo mkdir -p /var/www/html/${APP_NAME}
        sudo chown -R $USER:$USER /var/www/html/${APP_NAME}
    fi

    # Copy application files (assuming files are in current directory)
    print_warning "Copying application files..."
    cp -r . /var/www/html/${APP_NAME}/
    cd /var/www/html/${APP_NAME}

    # Install PHP dependencies
    print_warning "Installing PHP dependencies..."
    if [ -f "composer.json" ]; then
        curl -sS https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
        composer install --no-dev --optimize-autoloader
    fi

    # Install Node.js dependencies
    print_warning "Installing Node.js dependencies..."
    if [ -f "package.json" ]; then
        npm install
        npm run build
    fi

    print_success "Application setup completed"
}

configure_environment() {
    print_header "CONFIGURING ENVIRONMENT"

    cd /var/www/html/${APP_NAME}

    # Copy environment file
    if [ -f ".env.example" ]; then
        cp .env.example .env
    fi

    # Configure .env file
    print_warning "Configuring .env file..."
    cat > .env << EOF
# =========================================
# SISTEM AKUNTANSI SIBUKU - PRODUCTION CONFIG
# =========================================

APP_NAME="Sistem Akuntansi Sibuku"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost/${APP_NAME}

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASS}

# Cache & Session
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=log
MAIL_FROM_ADDRESS="${ADMIN_EMAIL}"
MAIL_FROM_NAME="Sistem Akuntansi Sibuku"

# Timezone
APP_TIMEZONE=Asia/Jakarta
EOF

    # Generate application key
    print_warning "Generating application key..."
    php artisan key:generate

    print_success "Environment configuration completed"
}

setup_database_schema() {
    print_header "SETTING UP DATABASE SCHEMA"

    cd /var/www/html/${APP_NAME}

    # Import schema
    if [ -f "database_schema_production.sql" ]; then
        print_warning "Importing database schema..."
        mysql -u${DB_USER} -p${DB_PASS} ${DB_NAME} < database_schema_production.sql
    else
        print_warning "Running Laravel migrations..."
        php artisan migrate --force
    fi

    # Import seed data
    if [ -f "database_seed_production.sql" ]; then
        print_warning "Importing seed data..."
        mysql -u${DB_USER} -p${DB_PASS} ${DB_NAME} < database_seed_production.sql
    else
        print_warning "Running Laravel seeders..."
        php artisan db:seed --force
    fi

    print_success "Database schema setup completed"
}

configure_web_server() {
    print_header "CONFIGURING WEB SERVER"

    # Create Apache virtual host
    print_warning "Creating Apache virtual host..."
    sudo tee /etc/apache2/sites-available/${APP_NAME}.conf > /dev/null << EOF
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html/${APP_NAME}/public

    <Directory /var/www/html/${APP_NAME}/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/${APP_NAME}_error.log
    CustomLog \${APACHE_LOG_DIR}/${APP_NAME}_access.log combined
</VirtualHost>
EOF

    # Enable site
    sudo a2ensite ${APP_NAME}.conf
    sudo a2enmod rewrite
    sudo systemctl restart apache2

    print_success "Web server configuration completed"
}

set_permissions() {
    print_header "SETTING FILE PERMISSIONS"

    cd /var/www/html/${APP_NAME}

    # Set proper ownership
    sudo chown -R www-data:www-data /var/www/html/${APP_NAME}

    # Set proper permissions
    sudo chmod -R 755 /var/www/html/${APP_NAME}
    sudo chmod -R 775 storage
    sudo chmod -R 775 bootstrap/cache

    print_success "File permissions set"
}

setup_cron_jobs() {
    print_header "SETTING UP CRON JOBS"

    # Add Laravel scheduler
    (crontab -l ; echo "* * * * * cd /var/www/html/${APP_NAME} && php artisan schedule:run >> /dev/null 2>&1") | crontab -

    # Add demo data reset (daily at 2 AM)
    (crontab -l ; echo "0 2 * * * cd /var/www/html/${APP_NAME} && php artisan demo:reset >> /dev/null 2>&1") | crontab -

    print_success "Cron jobs configured"
}

final_setup() {
    print_header "FINAL SETUP TASKS"

    cd /var/www/html/${APP_NAME}

    # Clear and cache configuration
    print_warning "Caching application configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Create storage link
    php artisan storage:link

    print_success "Final setup completed"
}

show_completion_message() {
    print_header "DEPLOYMENT COMPLETED SUCCESSFULLY!"

    echo -e "${GREEN}🎉 Your Akuntansi Sibuku system is now ready!${NC}"
    echo ""
    echo -e "${BLUE}Access your application at:${NC}"
    echo -e "  http://localhost/${APP_NAME}"
    echo ""
    echo -e "${BLUE}Default login credentials:${NC}"
    echo -e "  Super Admin: superadmin@sibuku.com / password"
    echo -e "  Demo User: demo@example.com / demo123"
    echo ""
    echo -e "${YELLOW}Next steps:${NC}"
    echo -e "  1. Configure domain name (optional)"
    echo -e "  2. Set up SSL certificate"
    echo -e "  3. Configure email settings"
    echo -e "  4. Set up regular backups"
    echo ""
    echo -e "${GREEN}For support, contact: admin@sibuku.com${NC}"
}

# Main execution
main() {
    print_header "SISTEM AKUNTANSI SIBUKU - PRODUCTION SETUP"
    echo "This script will set up the complete production environment."
    echo ""

    # Pre-flight checks
    check_root
    check_os

    # Get domain name (optional)
    read -p "Enter domain name (leave empty for localhost): " DOMAIN_NAME

    # Confirm execution
    read -p "This will install and configure the application. Continue? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_warning "Setup cancelled by user"
        exit 0
    fi

    # Execute setup steps
    install_dependencies
    setup_database
    setup_application
    configure_environment
    setup_database_schema
    configure_web_server
    set_permissions
    setup_cron_jobs
    final_setup

    # Show completion message
    show_completion_message
}

# Run main function
main "$@"