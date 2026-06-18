# Rencana Implementasi Setup Proyek

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Melakukan inisialisasi dan setup awal proyek Portofolio Cyberpunk dengan koneksi database MySQL secara lokal.

**Architecture:** Melakukan instalasi dependensi PHP dan Node.js secara lokal, mengonfigurasi berkas `.env` untuk koneksi MySQL lokal default, menjalankan key generator, menjalankan migrasi database dengan seeder, dan memverifikasi dengan melakukan build aset frontend.

**Tech Stack:** PHP 8.3, Composer, Node.js, npm, Laravel 13, MySQL, Vite

## Global Constraints

- PHP versi 8.3 ke atas harus terpasang.
- Node.js versi 18 ke atas harus terpasang.
- Layanan database MySQL lokal harus aktif pada port 3306 dengan kredensial default (`root`, tanpa password, database `laravel`).
- Semua perintah dijalankan di dalam direktori root `/home/arifrenggy00/my_web`.

---

### Task 1: Instalasi Dependensi PHP dan Node.js

**Files:**
- Modify: `package.json` (memverifikasi versi dependensi)
- Modify: `composer.json` (memverifikasi versi dependensi)

**Interfaces:**
- Consumes: Tidak ada
- Produces: Direktori `vendor/` dan `node_modules/` berisi pustaka yang dibutuhkan.

- [ ] **Step 1: Jalankan instalasi dependensi PHP menggunakan Composer**

Run: `composer install`
Expected: Instalasi selesai dengan sukses dan direktori `vendor` dibuat.

- [ ] **Step 2: Jalankan instalasi dependensi Node.js menggunakan npm**

Run: `npm install`
Expected: Instalasi selesai dengan sukses dan direktori `node_modules` dibuat.

- [ ] **Step 3: Commit perubahan lock files (jika ada)**

Run: `git add composer.lock package-lock.json && git commit -m "chore: update dependency lock files after installation"`
Expected: Commit berhasil atau "nothing to commit".

---

### Task 2: Salin dan Konfigurasi Environment File `.env`

**Files:**
- Create: `.env`

**Interfaces:**
- Consumes: `.env.example`
- Produces: Berkas `.env` dengan konfigurasi MySQL lokal yang valid dan application key terisi.

- [ ] **Step 1: Salin berkas `.env.example` menjadi `.env`**

Run: `cp .env.example .env`
Expected: Berkas `.env` berhasil dibuat di root proyek.

- [ ] **Step 2: Ubah konfigurasi database di `.env` ke MySQL**

Modifikasi baris 23-28 pada berkas `.env` menjadi:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
Gunakan tool edit file untuk melakukan modifikasi ini secara presisi.

- [ ] **Step 3: Hasilkan kunci aplikasi (Application Key)**

Run: `php artisan key:generate`
Expected: Output menampilkan "Application key set successfully." dan baris `APP_KEY` di `.env` terisi.

- [ ] **Step 4: Commit berkas konfigurasi**

Run: `git add .env.example && git commit -m "chore: verify .env setup template"`
Expected: Commit berhasil atau "nothing to commit" (berkas `.env` sendiri diabaikan oleh `.gitignore`).

---

### Task 3: Migrasi Database dan Seeding

**Files:**
- Modify: `.env`
- Test: Koneksi database MySQL

**Interfaces:**
- Consumes: Berkas `.env` dan skema migrasi di direktori `database/migrations/`.
- Produces: Tabel database MySQL `laravel` dengan data awal terisi.

- [ ] **Step 1: Jalankan migrasi dan seeder database**

Run: `php artisan migrate --seed --no-interaction`
*Catatan: Jika database `laravel` belum dibuat di MySQL, jalankan perintah tanpa `--no-interaction` atau pastikan database dibuat terlebih dahulu jika ditanyakan oleh Laravel.*
Run alternatif: `php artisan migrate --seed`
Expected: Database dibuat (jika belum ada), seluruh tabel berhasil dimigrasi, dan data seeder dimasukkan ke database.

- [ ] **Step 2: Commit status**

Run: `git status`
Expected: Menampilkan working tree bersih (kecuali untuk file rencana yang sedang dibuat).

---

### Task 4: Kompilasi Aset Frontend dan Verifikasi

**Files:**
- Modify: `public/build/` (setelah proses build selesai)

**Interfaces:**
- Consumes: Berkas source code di `resources/js/` dan konfigurasi `vite.config.js`.
- Produces: Aset terkompilasi di direktori `public/build/`.

- [ ] **Step 1: Jalankan kompilasi aset frontend**

Run: `npm run build`
Expected: Proses build selesai tanpa error dan menghasilkan berkas di `public/build/manifest.json`.

- [ ] **Step 2: Jalankan testing Laravel untuk memastikan backend berfungsi**

Run: `php artisan test`
Expected: Semua unit/feature test bawaan lolos (PASS).
