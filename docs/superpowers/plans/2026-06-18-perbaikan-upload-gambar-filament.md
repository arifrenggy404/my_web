# Perbaikan Upload Gambar Filament/Livewire Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengatasi error "failed to upload" pada Filament/Livewire saat upload gambar sampul proyek di Railway dengan mengonfigurasi limit upload Nginx & PHP, Trust Proxies, serta persistent storage symlink.

**Architecture:** 
1. Mengonfigurasi trust proxies di Laravel agar scheme HTTPS dari reverse proxy Railway dikenali dengan benar (menghindari error validasi tanda tangan Livewire).
2. Menaikkan limit upload Nginx (`client_max_body_size`) dan PHP (`upload_max_filesize`, `post_max_size`) agar mampu menerima file gambar ukuran besar.
3. Menghubungkan direktori `storage/app/public` ke volume persisten `/var/www/html/database_persistent/storage` dan menjalankan `php artisan storage:link` pada saat entrypoint dijalankan agar gambar yang diupload tidak hilang saat redeploy.

**Tech Stack:** Laravel 13, Nginx, PHP-FPM, Docker, Railway

## Global Constraints
- Jalankan semua pengujian lokal untuk memastikan tidak ada perubahan yang merusak fungsionalitas utama.
- Pertahankan styling dan integrasi tema cyberpunk yang sudah ada.
- Gunakan bahasa Indonesia untuk komunikasi.

---

### Task 1: Konfigurasi Trust Proxies di Laravel

**Files:**
- Modify: [bootstrap/app.php](file:///home/arifrenggy00/my_web/bootstrap/app.php)

**Interfaces:**
- Mengubah middleware setup agar mempercayai proxy Railway (`*`) sehingga request signature Livewire valid saat menggunakan HTTPS.

- [ ] **Step 1: Edit `bootstrap/app.php` untuk menambahkan `trustProxies`**

Tambahkan `$middleware->trustProxies(at: '*');` di dalam closure middleware:
```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
```

- [ ] **Step 2: Jalankan pengujian lokal phpunit**

Run: `composer test` atau `php artisan test`
Expected: Seluruh test suite (13 tests) tetap lulus (PASS).

- [ ] **Step 3: Commit perubahan**

```bash
git add bootstrap/app.php
git commit -m "config: trust all proxies in bootstrap/app.php for SSL termination"
```

---

### Task 2: Konfigurasi PHP Upload Limits

**Files:**
- Create: [docker/uploads.ini](file:///home/arifrenggy00/my_web/docker/uploads.ini)
- Modify: [Dockerfile](file:///home/arifrenggy00/my_web/Dockerfile)

**Interfaces:**
- Menambahkan konfigurasi PHP `uploads.ini` untuk meningkatkan batas file upload di PHP-FPM dari 2M menjadi 64M.

- [ ] **Step 1: Buat file `docker/uploads.ini`**

Isi file dengan:
```ini
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 256M
```

- [ ] **Step 2: Modifikasi `Dockerfile` untuk menyalin file konfigurasi PHP**

Buka [Dockerfile](file:///home/arifrenggy00/my_web/Dockerfile) dan tambahkan baris berikut di bawah penyalinan konfigurasi Nginx (sekitar baris 33-35):
```dockerfile
# Salin konfigurasi PHP untuk upload limit
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
```

- [ ] **Step 3: Commit perubahan**

```bash
git add docker/uploads.ini Dockerfile
git commit -m "config: increase PHP upload limits to 64M"
```

---

### Task 3: Konfigurasi Nginx Upload Limits

**Files:**
- Modify: [docker/nginx.conf](file:///home/arifrenggy00/my_web/docker/nginx.conf)

**Interfaces:**
- Mengatur `client_max_body_size` pada Nginx agar memperbolehkan payload upload hingga 64MB (mengatasi HTTP 413).

- [ ] **Step 1: Modifikasi `docker/nginx.conf`**

Buka [docker/nginx.conf](file:///home/arifrenggy00/my_web/docker/nginx.conf) dan tambahkan `client_max_body_size 64M;` di dalam blok `server`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/html/public;

    client_max_body_size 64M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
```

- [ ] **Step 2: Commit perubahan**

```bash
git add docker/nginx.conf
git commit -m "config: increase nginx client_max_body_size to 64M"
```

---

### Task 4: Setup Persistent Storage dan Simbolik Link di Entrypoint

**Files:**
- Modify: [docker/entrypoint.sh](file:///home/arifrenggy00/my_web/docker/entrypoint.sh)

**Interfaces:**
- Mengubah skrip inisialisasi kontainer (`entrypoint.sh`) untuk memastikan symlink storage dibuat secara otomatis dan diarahkan ke volume persistent Railway agar upload tetap ada setelah redeploy.

- [ ] **Step 1: Edit `docker/entrypoint.sh`**

Buka [docker/entrypoint.sh](file:///home/arifrenggy00/my_web/docker/entrypoint.sh) dan tambahkan logika pembuatan folder penyimpanan persisten dan symlink sebelum migrasi database (sekitar baris 10-15):
```bash
# Atur kepemilikan dan izin akses volume database agar bisa diakses oleh www-data
echo "Mengatur izin akses direktori database..."
chown -R www-data:www-data /var/www/html/database_persistent
chmod -R 775 /var/www/html/database_persistent

# Setup persistent storage uploads
echo "Mengonfigurasi persistent storage uploads..."
mkdir -p /var/www/html/database_persistent/storage
chown -R www-data:www-data /var/www/html/database_persistent/storage
chmod -R 775 /var/www/html/database_persistent/storage

# Link Laravel's public storage folder to the persistent volume
if [ ! -L /var/www/html/storage/app/public ]; then
    echo "Membuat symlink dari storage/app/public ke database_persistent/storage..."
    rm -rf /var/www/html/storage/app/public
    ln -s /var/www/html/database_persistent/storage /var/www/html/storage/app/public
    chown -h www-data:www-data /var/www/html/storage/app/public
fi

# Buat symbolic link Laravel untuk akses public
echo "Membuat symbolic link storage:link..."
php artisan storage:link --force
```

- [ ] **Step 2: Commit perubahan**

```bash
git add docker/entrypoint.sh
git commit -m "config: setup persistent storage symlink and storage:link in entrypoint.sh"
```

---

### Task 5: Push ke GitHub & Verifikasi di Railway

**Files:**
- None (deployment and remote checks)

**Interfaces:**
- Push kode terbaru ke remote master branch agar memicu deploy ulang di Railway.
- Melakukan verifikasi runtime.

- [ ] **Step 1: Push ke GitHub**

Run: `git push origin master` (atau branch utama yang terhubung ke Railway)
Expected: Push sukses.

- [ ] **Step 2: Pantau deploy di Railway**

Run: `railway status`
Tunggu hingga deployment selesai dan statusnya "Online".

- [ ] **Step 3: Verifikasi fungsi upload**
Minta user untuk mencoba kembali upload gambar proyek baru lewat panel admin `/admin/proyeks` (atau alamat admin panel yang bersangkutan).
