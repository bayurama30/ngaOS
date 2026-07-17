# NgaOS - Panduan Deploy ke cPanel

## Informasi Server
- **Domain:** ngaos.io
- **cPanel:** https://ucapjanji.com/cpanel
- **Username:** ucaw3285
- **Upload Path:** /home/ucaw3285/public_html/ngaos.io

## Langkah-langkah Deploy

### 1. Login ke cPanel
Buka https://ucapjanji.com/cpanel dan login dengan kredensial yang diberikan.

### 2. Buat Database MySQL
1. Di cPanel, cari **"MySQL Databases"**
2. Buat database baru:
   - Database Name: `ucaw3285_ngaos`
3. Buat user database:
   - Username: `ucaw3285_ngaos`
   - Password: (buat password yang kuat, simpan!)
4. Assign user ke database dengan **All Privileges**

### 3. Upload File
1. Di cPanel, buka **File Manager**
2. Navigate ke `/home/ucaw3285/public_html/ngaos.io`
3. Upload file `ngaos-deploy.tar.gz` yang sudah dibuat
4. Klik kanan file → **Extract**
5. Setelah extract, hapus file `ngaos-deploy.tar.gz`

### 4. Setup Environment
1. Di File Manager, rename `.env.production` menjadi `.env`
2. Edit file `.env`:
   - Update `DB_PASSWORD` dengan password database yang dibuat
   - Generate APP_KEY baru (lihat langkah 5)

### 5. Jalankan Perintah di Terminal cPanel
1. Di cPanel, cari **"Terminal"** atau **"SSH Access"**
2. Jalankan perintah berikut:

```bash
cd /home/ucaw3285/public_html/ngaos.io

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 6. Konfigurasi Domain
1. Di cPanel, cari **"Domains"** atau **"Subdomains"**
2. Edit domain `ngaos.io`
3. Set Document Root ke: `/home/ucaw3285/public_html/ngaos.io/public`

### 7. Setup Cron Job
1. Di cPanel, cari **"Cron Jobs"**
2. Tambahkan cron job:
   - Schedule: `* * * * *` (setiap menit)
   - Command: `cd /home/ucaw3285/public_html/ngaos.io && php artisan schedule:run >> /dev/null 2>&1`

### 8. Test Akses
Buka browser dan akses https://ngaos.io

## Troubleshooting

### Jika muncul error 500:
1. Cek file `.env` sudah benar
2. Pastikan folder `storage` dan `bootstrap/cache` writable (755)
3. Cek error log di cPanel → **Error Log**

### Jika halaman kosong:
1. Pastikan Document Root mengarah ke `/public`
2. Cek file `.htaccess` ada di folder public

### Jika database error:
1. Pastikan kredensial database benar di `.env`
2. Pastikan database dan user sudah dibuat
3. Pastikan user memiliki akses ke database

## File yang Sudah Disiapkan
- `ngaos-deploy.tar.gz` - Paket deployment (upload ini ke cPanel)
- `.env.production` - Template environment (rename ke `.env`)
- `deploy.sh` - Script deployment (opsional, jika terminal tersedia)
