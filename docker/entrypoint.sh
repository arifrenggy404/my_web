#!/bin/sh
set -e

# Konfigurasi port untuk Railway
PORT=${PORT:-80}
echo "Mengonfigurasi Nginx untuk mendengarkan port $PORT..."
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/sites-available/default
sed -i "s/listen \[::\]:80;/listen \[::\]:${PORT};/g" /etc/nginx/sites-available/default

# Inisialisasi database SQLite jika belum ada
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Membuat database SQLite baru..."
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Jalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force

# Seed database jika tabel proyek masih kosong
PROYEK_COUNT=$(php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo App\Models\Proyek::count();")
if [ "$PROYEK_COUNT" -eq 0 ]; then
    echo "Menjalankan database seeder..."
    php artisan db:seed --force
fi

# Optimasi Laravel untuk produksi
echo "Mengoptimalkan konfigurasi Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan supervisord
echo "Menjalankan PHP-FPM dan Nginx..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
