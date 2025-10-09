# 🌐 Apache Setup Guide untuk aaPanel + Laravel

Panduan lengkap setup Apache di aaPanel untuk menjalankan project Media Organisasi dengan performa optimal.

---

## 📋 Apache di aaPanel

aaPanel mendukung Apache sebagai alternatif web server. Apache lebih cocok untuk shared hosting dan memiliki banyak modul yang kompatibel dengan aplikasi PHP.

### Keunggulan Apache:
- ✅ Kompatibilitas tinggi dengan PHP applications
- ✅ .htaccess support (penting untuk Laravel)
- ✅ Banyak modul tersedia
- ✅ Mudah konfigurasi untuk shared hosting

---

## 🎯 Step 1: Install Apache di aaPanel

### Via aaPanel Dashboard:

1. **Login ke aaPanel**
   - Buka `http://IP_SERVER:8888`
   - Login dengan username & password

2. **Software Store → Web Server**
   - Pilih **Apache**
   - Klik **Install**
   - Tunggu proses install selesai

3. **Install PHP Extensions:**
   - Software Store → PHP Extensions
   - Install extensions berikut:
     - `fileinfo`
     - `exif`
     - `mysqli`
     - `pdo_mysql`
     - `mbstring`
     - `openssl`
     - `tokenizer`
     - `xml`
     - `ctype`
     - `json`
     - `bcmath`
     - `curl`
     - `gd`
     - `zip`

---

## 🎯 Step 2: Konfigurasi Apache untuk Laravel

### Via aaPanel Website Settings:

1. **Website → Settings → Configuration**
2. **Pilih Apache configuration**
3. **Replace dengan konfigurasi berikut:**

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /www/wwwroot/yourdomain.com/public

    <Directory /www/wwwroot/yourdomain.com/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Laravel specific configurations
    <IfModule mod_rewrite.c>
        RewriteEngine On

        # Handle Authorization Header
        RewriteCond %{HTTP:Authorization} .
        RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

        # Redirect Trailing Slashes If Not A Folder...
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_URI} (.+)/$
        RewriteRule ^ %1 [L,R=301]

        # Send Requests To Front Controller...
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [L]
    </IfModule>

    # Security Headers
    <IfModule mod_headers.c>
        Header always set X-Frame-Options SAMEORIGIN
        Header always set X-XSS-Protection "1; mode=block"
        Header always set X-Content-Type-Options nosniff
        Header always set Referrer-Policy "no-referrer-when-downgrade"
        Header always set Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'"
    </IfModule>

    # PHP Configuration
    <FilesMatch \.php$>
        SetHandler "proxy:fcgi://127.0.0.1:9000"
    </FilesMatch>

    # Cache Static Files
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpg "access plus 1 year"
        ExpiresByType image/jpeg "access plus 1 year"
        ExpiresByType image/gif "access plus 1 year"
        ExpiresByType image/png "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType application/pdf "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 year"
        ExpiresByType application/x-javascript "access plus 1 year"
        ExpiresByType application/x-shockwave-flash "access plus 1 month"
        ExpiresByType image/x-icon "access plus 1 year"
    </IfModule>

    # Compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/plain
        AddOutputFilterByType DEFLATE text/html
        AddOutputFilterByType DEFLATE text/xml
        AddOutputFilterByType DEFLATE text/css
        AddOutputFilterByType DEFLATE application/xml
        AddOutputFilterByType DEFLATE application/xhtml+xml
        AddOutputFilterByType DEFLATE application/rss+xml
        AddOutputFilterByType DEFLATE application/javascript
        AddOutputFilterByType DEFLATE application/x-javascript
    </IfModule>

    # Logs
    ErrorLog /www/wwwlogs/yourdomain.com_error.log
    CustomLog /www/wwwlogs/yourdomain.com_access.log combined
</VirtualHost>
```

### Penjelasan Konfigurasi:

- **DocumentRoot**: Point ke folder `public` Laravel
- **AllowOverride All**: Mengaktifkan `.htaccess`
- **mod_rewrite**: Untuk URL rewriting Laravel
- **Security Headers**: Perlindungan keamanan
- **mod_expires**: Cache static files
- **mod_deflate**: Kompresi response
- **FCGI**: Koneksi ke PHP-FPM

---

## 🎯 Step 3: Setup PHP-FPM

### Di aaPanel:

1. **Software Store → PHP Manager**
2. **Pilih PHP 8.1+**
3. **Klik "Settings"**
4. **Konfigurasi FPM:**

```ini
; PHP-FPM Configuration
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

; Memory limit
memory_limit = 256M

; Upload settings
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300

; Laravel specific
opcache.enable = 1
opcache.memory_consumption = 256
opcache.max_accelerated_files = 7963
opcache.revalidate_freq = 0
```

---

## 🎯 Step 4: Laravel .htaccess Configuration

### Pastikan file `.htaccess` ada di folder `public`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🎯 Step 5: Permissions & Ownership

### Set correct permissions:

```bash
# Di server via SSH
cd /www/wwwroot/yourdomain.com

# Set ownership
sudo chown -R www:www .

# Set permissions
sudo chmod -R 755 .
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Laravel specific
sudo chmod 644 .env
sudo chmod 644 artisan
```

---

## 🎯 Step 6: SSL Configuration (Let's Encrypt)

### Via aaPanel:

1. **Website → SSL**
2. **Pilih "Let's Encrypt"**
3. **Add domain:** `yourdomain.com`
4. **Include www:** ✅
5. **Auto renewal:** ✅
6. **Apply**

### Apache SSL Configuration:

```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /www/wwwroot/yourdomain.com/public

    SSLEngine on
    SSLCertificateFile /www/server/panel/vhost/cert/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /www/server/panel/vhost/cert/yourdomain.com/privkey.pem

    <Directory /www/wwwroot/yourdomain.com/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # ... (same configuration as port 80)

    # HTTP to HTTPS redirect
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
```

---

## 🎯 Step 7: Testing & Optimization

### Test Apache Configuration:

```bash
# Test syntax
sudo apachectl configtest

# Restart Apache
sudo systemctl restart httpd

# Check status
sudo systemctl status httpd
```

### Test Laravel:

```bash
# Di folder project
cd /www/wwwroot/yourdomain.com

# Test artisan
php artisan --version

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Test route
php artisan route:list | head -10
```

### Performance Optimization:

```bash
# Enable mod_rewrite
sudo a2enmod rewrite

# Enable mod_headers
sudo a2enmod headers

# Enable mod_expires
sudo a2enmod expires

# Enable mod_deflate
sudo a2enmod deflate

# Restart Apache
sudo systemctl restart httpd
```

---

## 🔧 Troubleshooting Apache + Laravel

### Common Issues:

**1. 500 Internal Server Error:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Apache logs
tail -f /www/wwwlogs/yourdomain.com_error.log

# Check PHP-FPM logs
tail -f /www/server/php/81/var/log/php-fpm.log
```

**2. 403 Forbidden:**
```bash
# Check permissions
ls -la /www/wwwroot/yourdomain.com/public

# Check ownership
sudo chown -R www:www /www/wwwroot/yourdomain.com
```

**3. .htaccess not working:**
```bash
# Check AllowOverride in Apache config
grep -i "AllowOverride" /www/server/panel/vhost/apache/yourdomain.com.conf
```

**4. PHP not executing:**
```bash
# Check PHP-FPM status
sudo systemctl status php-fpm-81

# Test PHP
php -v
```

### Apache Modules Check:

```bash
# List loaded modules
sudo apachectl -M

# Required modules:
# - rewrite_module
# - headers_module
# - expires_module
# - deflate_module
# - proxy_module
# - proxy_fcgi_module
```

---

## 📊 Monitoring Apache

### Access Logs:

```bash
# Real-time access log
tail -f /www/wwwlogs/yourdomain.com_access.log

# Error log
tail -f /www/wwwlogs/yourdomain.com_error.log
```

### Performance Monitoring:

```bash
# Apache status
sudo apachectl status

# PHP-FPM status
sudo systemctl status php-fpm-81

# Memory usage
free -h
df -h
```

### aaPanel Monitoring:

- **Website → Logs** - View access/error logs
- **Software Store → Apache** - Check Apache status
- **Performance** - Monitor server resources

---

## 🚀 Deployment dengan Apache

### Update deploy.sh untuk Apache:

Script deployment sudah include auto-detection untuk Apache. Pastikan konfigurasi di `deploy.config` benar:

```bash
SERVER_PATH="/www/wwwroot/yourdomain.com"
```

### Post-deployment:

```bash
# Restart Apache setelah deploy
sudo systemctl restart httpd

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
```

---

## 🎯 Apache vs Nginx Comparison

| Feature | Apache | Nginx |
|---------|--------|-------|
| .htaccess | ✅ Native | ❌ Limited |
| Shared Hosting | ✅ Excellent | ⚠️ Requires config |
| PHP Integration | ✅ Easy | ⚠️ Requires PHP-FPM |
| Resource Usage | ⚠️ Higher | ✅ Lower |
| Static Files | ⚠️ Good | ✅ Excellent |
| Dynamic Content | ✅ Excellent | ⚠️ Good |

**Rekomendasi:** Apache lebih cocok untuk Laravel karena native .htaccess support dan kemudahan konfigurasi.

---

## 🎉 Apache Setup Complete!

Apache sekarang terkonfigurasi optimal untuk Laravel di aaPanel. Website Media Organisasi siap untuk production dengan performa tinggi dan keamanan yang baik.

**Next Steps:**
1. Setup Cloudflare Tunnel
2. Deploy project
3. Test semua functionality
4. Monitor performance

**🔥 Apache + Laravel = Perfect Match!**
