# 🚀 Media Organisasi - One-Command Deployment Guide

Panduan lengkap untuk deploy aplikasi Media Organisasi dengan satu perintah ke server aaPanel + Cloudflare.

## 📋 Prerequisites

### Di Lokal (VSCode/Windows)
1. **Git Bash** atau **WSL** terinstall
2. **OpenSSH** client
3. **rsync** terinstall
4. **SSH Key** sudah dikonfigurasi

### Di Server (aaPanel)
1. **aaPanel** terinstall
2. **Website** sudah dibuat di aaPanel
3. **Database MySQL** sudah dibuat
4. **PHP 8.1+** dengan ekstensi yang dibutuhkan
5. **Composer** terinstall
6. **Node.js & npm** terinstall (opsional)

## ⚙️ Setup Awal

### 1. Konfigurasi SSH Access

**Generate SSH Key (di lokal):**
```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

# Copy ke server
ssh-copy-id user@your_server_ip
```

**Atau manual copy public key:**
```bash
# Copy isi file id_rsa.pub
cat ~/.ssh/id_rsa.pub

# Paste ke server di ~/.ssh/authorized_keys
```

### 2. Edit File Konfigurasi

Edit file `deploy.config` dengan informasi server Anda:

```bash
# Server Configuration
SERVER_HOST="your_server_ip_or_domain"
SERVER_USER="root"  # atau username aaPanel Anda
SERVER_PATH="/www/wwwroot/your_domain.com"  # path aaPanel

# Database Configuration
DB_NAME="media_organisasi"
DB_USER="your_db_user"
DB_PASS="your_db_password"

# Cloudflare Configuration (opsional)
CLOUDFLARE_ZONE_ID="your_cloudflare_zone_id"
CLOUDFLARE_API_TOKEN="your_cloudflare_api_token"
DOMAIN="your_domain.com"
```

### 3. Test Koneksi

```bash
# Test koneksi
./deploy.sh test

# Jika berhasil, akan muncul: [SUCCESS] Connection test passed
```

## 🚀 Deployment

### Deploy Lengkap (Satu Perintah)

```bash
# Deploy semua perubahan
./deploy.sh

# Atau explicit
./deploy.sh main
```

**Yang dilakukan script:**
1. ✅ Backup database otomatis
2. ✅ Sync semua file ke server
3. ✅ Install dependencies PHP
4. ✅ Build assets (jika ada)
5. ✅ Run migrations database
6. ✅ Clear & cache konfigurasi
7. ✅ Set permissions yang benar
8. ✅ Purge Cloudflare cache (jika dikonfigurasi)

### Deploy Sebagian (untuk testing)

```bash
# Backup database saja
./deploy.sh backup

# Sync file saja
./deploy.sh files

# Deploy di server saja
./deploy.sh server

# Purge Cloudflare saja
./deploy.sh cloudflare
```

### Cek Konfigurasi

```bash
# Lihat konfigurasi saat ini
./deploy.sh config
```

## 🔄 Workflow Development

### Setelah Perubahan Kode

1. **Edit file di VSCode**
2. **Test lokal** (opsional)
3. **Deploy dengan satu perintah:**
   ```bash
   ./deploy.sh
   ```
4. **Website langsung live!** 🎉

### Jika Ada Perubahan Database

1. **Buat migration baru:**
   ```bash
   php artisan make:migration nama_migration
   ```

2. **Deploy seperti biasa:**
   ```bash
   ./deploy.sh
   ```
   *(Migration akan otomatis dijalankan)*

## 🛠️ Troubleshooting

### Error: "Cannot connect to server"
```bash
# Cek SSH key
ssh -T user@server_ip

# Regenerate SSH key jika perlu
ssh-keygen -t rsa -b 4096
ssh-copy-id user@server_ip
```

### Error: "rsync not found"
```bash
# Install rsync
# Windows: Install Git Bash
# macOS: brew install rsync
# Ubuntu: sudo apt install rsync
```

### Error: "Database backup failed"
- Cek kredensial database di `deploy.config`
- Pastikan user database punya akses backup

### Error: "Composer not found"
- Install Composer di server aaPanel
- Atau comment baris composer di script

## 📁 Struktur File

```
project/
├── deploy.sh          # Script deployment utama
├── deploy.config      # Konfigurasi server
├── DEPLOYMENT_README.md # Dokumentasi ini
└── ... (kode aplikasi)
```

## 🔒 Keamanan

- **Jangan commit** `deploy.config` ke Git
- **Gunakan SSH key** instead of password
- **Backup database** otomatis sebelum deploy
- **Test di staging** sebelum production

## 📞 Support

Jika ada masalah:
1. Cek log error di terminal
2. Test koneksi SSH manual
3. Verifikasi konfigurasi di `deploy.config`
4. Cek dokumentasi aaPanel

---

**🎉 Selamat! Sekarang Anda bisa deploy dengan satu perintah saja!**

```bash
./deploy.sh
```

Website Anda akan langsung live setelah deployment selesai! 🚀
