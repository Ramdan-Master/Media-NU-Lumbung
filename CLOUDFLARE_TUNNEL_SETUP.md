# 🌐 Cloudflare Tunnel Setup untuk STB Server

Panduan setup Cloudflare Tunnel untuk mengonlinekan project Media Organisasi di server STB Anda.

## 📋 Yang Dibutuhkan

- ✅ Cloudflare Account aktif
- ✅ Domain yang sudah di-point ke Cloudflare
- ✅ Server STB dengan aaPanel
- ✅ Akses SSH ke server

## 🚀 Setup Cloudflare Tunnel

### 1. Install Cloudflared di Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Download cloudflared
wget https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb

# Install package
sudo dpkg -i cloudflared-linux-amd64.deb

# Verifikasi installasi
cloudflared version
```

### 2. Login ke Cloudflare

```bash
# Login ke akun Cloudflare
cloudflared tunnel login

# Browser akan terbuka, login dan authorize
# Setelah login berhasil, token akan tersimpan
```

### 3. Buat Tunnel Baru

```bash
# Buat tunnel dengan nama
cloudflared tunnel create media-organisasi-tunnel

# List tunnel untuk dapatkan ID
cloudflared tunnel list

# Output akan seperti:
# ID: 12345678-1234-1234-1234-123456789012
# Name: media-organisasi-tunnel
# Created: 2024-01-01T00:00:00Z
```

### 4. Konfigurasi Tunnel

**Buat file konfigurasi:**
```bash
nano ~/.cloudflared/config.yaml
```

**Isi dengan konfigurasi berikut:**
```yaml
tunnel: 12345678-1234-1234-1234-123456789012  # Ganti dengan tunnel ID Anda
credentials-file: /root/.cloudflared/12345678-1234-1234-1234-123456789012.json

ingress:
  - hostname: yourdomain.com
    service: http://localhost:80
  - hostname: admin.yourdomain.com
    service: http://localhost:80
  - hostname: api.yourdomain.com
    service: http://localhost:80
  - service: http_status:404
```

**Ganti:**
- `12345678-1234-1234-1234-123456789012` → Tunnel ID dari langkah 3
- `yourdomain.com` → Domain Anda
- `admin.yourdomain.com` → Subdomain untuk admin (opsional)
- `api.yourdomain.com` → Subdomain untuk API (opsional)

### 5. Setup DNS Records

```bash
# Buat DNS record untuk domain utama
cloudflared tunnel route dns media-organisasi-tunnel yourdomain.com

# Untuk subdomain admin
cloudflared tunnel route dns media-organisasi-tunnel admin.yourdomain.com

# Untuk subdomain API
cloudflared tunnel route dns media-organisasi-tunnel api.yourdomain.com
```

### 6. Test Tunnel

```bash
# Test tunnel (jalankan di background dengan Ctrl+C untuk stop)
cloudflared tunnel run media-organisasi-tunnel

# Jika berhasil, akan muncul:
# 2024-01-01T00:00:00Z INF Starting tunnel tunnelID=12345678-1234-1234-1234-123456789012
# 2024-01-01T00:00:00Z INF Registered tunnel connection connIndex=0 location=XXX
```

### 7. Setup sebagai Service (Recommended)

**Install sebagai systemd service:**
```bash
# Install service
sudo cloudflared service install

# Start service
sudo systemctl start cloudflared

# Enable auto-start saat boot
sudo systemctl enable cloudflared

# Cek status
sudo systemctl status cloudflared
```

## 🔧 Konfigurasi aaPanel

### Pastikan Website Listen di Port 80

1. **Login ke aaPanel**
2. **Masuk ke "Website" → "Settings"**
3. **Pastikan:**
   - Port: 80
   - SSL: Disabled (karena Cloudflare handle SSL)
   - PHP Version: 8.1+

### Konfigurasi Nginx/Apache

**Jika perlu custom config:**
```nginx
server {
    listen 80;
    server_name localhost;
    root /www/wwwroot/yourdomain.com;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/tmp/php-cgi-81.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🌐 Akses Website

Setelah setup selesai, website bisa diakses:

- **Frontend:** `https://yourdomain.com`
- **Admin Panel:** `https://admin.yourdomain.com/admin`
- **API:** `https://api.yourdomain.com/api` (jika ada)

## 🛠️ Troubleshooting

### Tunnel Tidak Connect

**Cek status service:**
```bash
sudo systemctl status cloudflared
sudo journalctl -u cloudflared -f
```

**Restart tunnel:**
```bash
sudo systemctl restart cloudflared
```

**Test manual:**
```bash
cloudflared tunnel run media-organisasi-tunnel
```

### DNS Tidak Resolve

**Cek DNS records di Cloudflare Dashboard:**
- Type: CNAME
- Name: yourdomain.com
- Target: your-tunnel-id.cfargotunnel.com

**Flush DNS lokal:**
```bash
# Windows
ipconfig /flushdns

# Linux
sudo systemd-resolve --flush-caches
```

### Website Tidak Bisa Diakses

**Cek aaPanel:**
- Pastikan website aktif
- Cek error logs di aaPanel
- Test local access: `curl http://localhost`

**Cek firewall:**
```bash
# Allow port 80
sudo ufw allow 80
sudo ufw allow 443
```

## 📊 Monitoring

### Cek Status Tunnel

```bash
# Status service
sudo systemctl status cloudflared

# Logs real-time
sudo journalctl -u cloudflared -f

# List active tunnels
cloudflared tunnel list
```

### Update Cloudflared

```bash
# Stop service
sudo systemctl stop cloudflared

# Update package
sudo dpkg -i cloudflared-linux-amd64.deb

# Start service
sudo systemctl start cloudflared
```

## 🔒 Security Best Practices

- ✅ **Gunakan HTTPS** (Cloudflare handle SSL)
- ✅ **Update cloudflared** regularly
- ✅ **Monitor logs** untuk aktivitas mencurigakan
- ✅ **Backup konfigurasi** tunnel
- ✅ **Gunakan strong passwords** untuk aaPanel

## 📞 Support

Jika ada masalah:

1. **Cek Cloudflare Dashboard** → Zero Trust → Access → Tunnels
2. **Verify DNS records** di DNS management
3. **Test tunnel connectivity** dengan `cloudflared tunnel ping`
4. **Check aaPanel logs** untuk web server errors
5. **Verify firewall rules** di server

---

**🎉 Server STB Anda sekarang online via Cloudflare Tunnel!**

Website bisa diakses dari mana saja tanpa perlu public IP! 🌍
