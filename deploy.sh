#!/bin/bash

# ===========================================
# Media Organisasi Auto Deployment Script
# Compatible with aaPanel + Cloudflare
# ===========================================

# Configuration - EDIT THESE VALUES
SERVER_HOST="your_server_ip_or_domain"
SERVER_USER="root"  # or your aaPanel user
SERVER_PATH="/www/wwwroot/your_domain.com"  # aaPanel default web root
DB_NAME="media_organisasi"
DB_USER="your_db_user"
DB_PASS="your_db_password"
CLOUDFLARE_ZONE_ID="your_cloudflare_zone_id"
CLOUDFLARE_API_TOKEN="your_cloudflare_api_token"
DOMAIN="your_domain.com"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if required tools are installed
check_requirements() {
    log_info "Checking requirements..."

    if ! command -v rsync &> /dev/null; then
        log_error "rsync is not installed. Please install it first."
        exit 1
    fi

    if ! command -v ssh &> /dev/null; then
        log_error "ssh is not installed. Please install OpenSSH."
        exit 1
    fi

    log_success "Requirements check passed"
}

# Test server connection
test_connection() {
    log_info "Testing server connection..."
    if ! ssh -o ConnectTimeout=10 -o BatchMode=yes $SERVER_USER@$SERVER_HOST "echo 'Connection successful'" &> /dev/null; then
        log_error "Cannot connect to server. Please check your SSH configuration."
        exit 1
    fi
    log_success "Server connection established"
}

# Create database backup
backup_database() {
    log_info "Creating database backup on server..."
    ssh $SERVER_USER@$SERVER_HOST "
        mkdir -p /www/backup
        mysqldump -u$DB_USER -p'$DB_PASS' $DB_NAME > /www/backup/backup_$(date +%Y%m%d_%H%M%S).sql
        if [ \$? -eq 0 ]; then
            echo 'Database backup created successfully'
        else
            echo 'Database backup failed'
            exit 1
        fi
    "
    if [ $? -eq 0 ]; then
        log_success "Database backup completed"
    else
        log_error "Database backup failed"
        exit 1
    fi
}

# Sync files to server
sync_files() {
    log_info "Syncing files to server..."

    # Exclude files that shouldn't be uploaded
    rsync -avz --delete \
        --exclude='.git/' \
        --exclude='.env' \
        --exclude='node_modules/' \
        --exclude='.vscode/' \
        --exclude='deploy.sh' \
        --exclude='*.log' \
        --exclude='storage/logs/*' \
        --exclude='storage/framework/cache/*' \
        --exclude='storage/framework/sessions/*' \
        --exclude='storage/framework/views/*' \
        --exclude='bootstrap/cache/*' \
        ./ $SERVER_USER@$SERVER_HOST:$SERVER_PATH/

    if [ $? -eq 0 ]; then
        log_success "File sync completed"
    else
        log_error "File sync failed"
        exit 1
    fi
}

# Run deployment commands on server
deploy_on_server() {
    log_info "Running deployment commands on server..."

    ssh $SERVER_USER@$SERVER_HOST "
        cd $SERVER_PATH

        # Set permissions
        chown -R www:www .
        chmod -R 755 storage bootstrap/cache
        chmod 644 .env

        # Install/update PHP dependencies
        if [ -f 'composer.json' ]; then
            /usr/local/php/bin/php /usr/local/bin/composer install --no-dev --optimize-autoloader
        fi

        # Install/update Node dependencies and build assets
        if [ -f 'package.json' ]; then
            npm install
            npm run build
        fi

        # Generate application key if not exists
        if ! grep -q 'APP_KEY=' .env; then
            /usr/local/php/bin/php artisan key:generate
        fi

        # Run database migrations
        /usr/local/php/bin/php artisan migrate --force

        # Clear and cache config
        /usr/local/php/bin/php artisan config:clear
        /usr/local/php/bin/php artisan cache:clear
        /usr/local/php/bin/php artisan view:clear
        /usr/local/php/bin/php artisan route:clear

        # Cache config for production
        /usr/local/php/bin/php artisan config:cache
        /usr/local/php/bin/php artisan route:cache
        /usr/local/php/bin/php artisan view:cache

        # Create storage link
        /usr/local/php/bin/php artisan storage:link

        # Set final permissions
        chown -R www:www .
        find . -type f -name '*.php' -exec chmod 644 {} \;
        find . -type d -exec chmod 755 {} \;

        echo 'Deployment completed successfully'
    "

    if [ $? -eq 0 ]; then
        log_success "Server deployment completed"
    else
        log_error "Server deployment failed"
        exit 1
    fi
}

# Purge Cloudflare cache
purge_cloudflare() {
    if [ -n "$CLOUDFLARE_API_TOKEN" ] && [ -n "$CLOUDFLARE_ZONE_ID" ]; then
        log_info "Purging Cloudflare cache..."

        curl -X POST "https://api.cloudflare.com/client/v4/zones/$CLOUDFLARE_ZONE_ID/purge_cache" \
            -H "Authorization: Bearer $CLOUDFLARE_API_TOKEN" \
            -H "Content-Type: application/json" \
            --data '{"purge_everything":true}' \
            --silent --show-error

        if [ $? -eq 0 ]; then
            log_success "Cloudflare cache purged"
        else
            log_warning "Cloudflare cache purge may have failed"
        fi
    else
        log_warning "Cloudflare credentials not configured, skipping cache purge"
    fi
}

# Main deployment function
main() {
    echo "🚀 Starting Media Organisasi Deployment"
    echo "====================================="

    check_requirements
    test_connection
    backup_database
    sync_files
    deploy_on_server
    purge_cloudflare

    echo ""
    log_success "🎉 Deployment completed successfully!"
    log_info "Your website should be live at: https://$DOMAIN"
    echo ""
    log_info "Don't forget to:"
    echo "  - Test your website functionality"
    echo "  - Check browser console for any errors"
    echo "  - Verify database connections"
    echo "  - Test admin panel login"
}

# Handle command line arguments
case "$1" in
    "test")
        check_requirements
        test_connection
        log_success "Connection test passed"
        ;;
    "backup")
        test_connection
        backup_database
        ;;
    "files")
        sync_files
        ;;
    "server")
        deploy_on_server
        ;;
    "cloudflare")
        purge_cloudflare
        ;;
    *)
        main
        ;;
esac
