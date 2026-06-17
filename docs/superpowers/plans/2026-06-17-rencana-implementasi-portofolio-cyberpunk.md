# Rencana Implementasi Sistem Portofolio Cyberpunk

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun sistem website portofolio interaktif bertema Cyberpunk/Cyber-Modern yang memiliki halaman utama berbasis React Inertia dengan animasi Framer Motion, serta didukung oleh backend Laravel 13 dan admin panel Filament PHP v4 untuk CRUD data proyek.

**Architecture:** Monolitik Hibrida di mana Laravel 13 menyajikan API JSON untuk proyek dan melayani panel admin Filament v4 pada rute `/admin`, sedangkan frontend utama dirender menggunakan Inertia React.

**Tech Stack:** Laravel 13, PHP 8.3+, React, Inertia.js, Framer Motion, Tailwind CSS v4, Filament PHP v4, SQLite.

## Global Constraints
* Seluruh penamaan variabel, fungsi, tabel database, komentar kode, dan dokumentasi WAJIB menggunakan Bahasa Indonesia yang bersih dan profesional.
* Gunakan istilah modern seperti "Pengembangan", "Implementasi", "Sistem Informasi", atau "Otomatisasi". Hindari istilah usang.
* Semua database kolom, tabel, variabel, konfigurasi model, dan kontroler harus berbahasa Indonesia (contoh: tabel `proyek`, kolom `nama_proyek`, method `ambilSemua`).
* Menggunakan fitur Laravel 13 terbaru (PHP Attributes untuk model Eloquent, PHP 8.3 type-safety).

---

## 1. STRUKTUR FILE YANG AKAN DIBUAT/DIUBAH

* `bootstrap/app.php` (Diubah): Menambahkan rute API.
* `routes/api.php` (Dibuat): Menambahkan rute API proyek.
* `database/migrations/2026_06_17_000000_buat_tabel_proyek.php` (Dibuat): Skema tabel proyek.
* `database/seeders/SeederProyek.php` (Dibuat): Seeder data proyek awal.
* `app/Models/Proyek.php` (Dibuat): Model Eloquent menggunakan PHP Attributes.
* `app/Http/Controllers/API/KontrolerProyek.php` (Dibuat): Kontroler API proyek.
* `tests/Feature/API/KontrolerProyekTest.php` (Dibuat): Tes fitur API proyek.
* `app/Filament/Resources/ProyekResource.php` (Dibuat): Filament Resource untuk manajemen proyek.
* `resources/views/app.blade.php` (Dibuat): Template layout utama Laravel untuk Inertia.
* `resources/js/Pages/Portofolio.jsx` (Dibuat): Halaman portofolio utama React + Framer Motion.
* `resources/js/app.jsx` (Dibuat): Setup Inertia React.
* `vite.config.js` (Diubah): Konfigurasi Vite untuk memuat aset React.

---

## DAFTAR TUGAS IMPLEMENTASI

### Task 1: Konfigurasi Awal API & Registrasi Rute

**Files:**
* Modify: `bootstrap/app.php`
* Create: `routes/api.php`
* Create: `tests/Feature/API/RuteApiTest.php`

**Interfaces:**
* Produces: `/api/ping` endpoint returning status ok.

- [ ] **Step 1: Buat berkas tes unit untuk rute API**

Buat file baru di `tests/Feature/API/RuteApiTest.php`:
```php
<?php

namespace Tests\Feature\API;

use Tests\TestCase;

class RuteApiTest extends TestCase
{
    public function test_api_ping_berhasil(): void
    {
        $response = $this->getJson('/api/ping');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'aktif',
                     'pesan' => 'API Portofolio Cyberpunk siap'
                 ]);
    }
}
```

- [ ] **Step 2: Jalankan tes untuk memverifikasi kegagalan**

Jalankan:
```bash
php artisan test --filter=RuteApiTest
```
Expected: FAIL (karena rute API belum terdaftar dan file api.php belum dibuat)

- [ ] **Step 3: Daftarkan file rute API di bootstrap/app.php**

Ubah file `bootstrap/app.php` agar memuat routes/api.php:
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
```

- [ ] **Step 4: Buat file routes/api.php dan tambahkan rute ping**

Buat file baru `routes/api.php`:
```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'aktif',
        'pesan' => 'API Portofolio Cyberpunk siap'
    ]);
});
```

- [ ] **Step 5: Jalankan tes kembali untuk memverifikasi kelulusan**

Jalankan:
```bash
php artisan test --filter=RuteApiTest
```
Expected: PASS

- [ ] **Step 6: Komit perubahan ke Git**

Jalankan:
```bash
git add bootstrap/app.php routes/api.php tests/Feature/API/RuteApiTest.php
git commit -m "feat: daftarkan rute api dan buat rute ping awal"
```

---

### Task 2: Migrasi Database & Seeder Proyek

**Files:**
* Create: `database/migrations/2026_06_17_000000_buat_tabel_proyek.php`
* Create: `database/seeders/SeederProyek.php`

**Interfaces:**
* Produces: Tabel `proyek` di database SQLite dengan data contoh awal.

- [ ] **Step 1: Buat berkas migrasi database**

Buat file baru `database/migrations/2026_06_17_000000_buat_tabel_proyek.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');
            $table->string('tautan_slug')->unique();
            $table->text('deskripsi');
            $table->json('teknologi_utama');
            $table->string('tautan_langsung')->nullable();
            $table->string('tautan_github')->nullable();
            $table->string('jalur_gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
```

- [ ] **Step 2: Jalankan migrasi ke SQLite**

Jalankan:
```bash
php artisan migrate --force
```
Expected: Migration table `proyek` created successfully.

- [ ] **Step 3: Buat berkas SeederProyek**

Buat file baru `database/seeders/SeederProyek.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederProyek extends Seeder
{
    public function run(): void
    {
        DB::table('proyek')->truncate();

        DB::table('proyek')->insert([
            [
                'nama_proyek' => 'Sistem Informasi Keamanan Neo-Tokyo',
                'tautan_slug' => 'sistem-informasi-keamanan-neo-tokyo',
                'deskripsi' => 'Website pemantauan keamanan kota berbasis Cyberpunk dengan visual HUD real-time.',
                'teknologi_utama' => json_encode(['Laravel 13', 'React', 'Framer Motion', 'Tailwind CSS']),
                'tautan_langsung' => 'https://neo-tokyo-demo.example.com',
                'tautan_github' => 'https://github.com/arifrenggy00/neo-tokyo-security',
                'jalur_gambar' => 'gambar/neo-tokyo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_proyek' => 'Otomatisasi Grid Daya Distrik 7',
                'tautan_slug' => 'otomatisasi-grid-daya-distrik-7',
                'deskripsi' => 'Dashboard analitik interaktif untuk mengontrol grid energi nuklir distrik urban.',
                'teknologi_utama' => json_encode(['Laravel 13', 'Alpine.js', 'Chart.js', 'SQLite']),
                'tautan_langsung' => null,
                'tautan_github' => 'https://github.com/arifrenggy00/district7-grid',
                'jalur_gambar' => 'gambar/distrik7.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
```

- [ ] **Step 4: Daftarkan seeder di DatabaseSeeder**

Ubah file `database/seeders/DatabaseSeeder.php` agar memanggil `SeederProyek`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SeederProyek::class,
        ]);
    }
}
```

- [ ] **Step 5: Jalankan seeder**

Jalankan:
```bash
php artisan db:seed
```
Expected: Database seeding completed successfully.

- [ ] **Step 6: Komit perubahan ke Git**

Jalankan:
```bash
git add database/migrations/2026_06_17_000000_buat_tabel_proyek.php database/seeders/SeederProyek.php database/seeders/DatabaseSeeder.php
git commit -m "feat: buat migrasi tabel proyek dan seeder data awal"
```

---

### Task 3: Model Eloyek `Proyek` & Kontroler API `KontrolerProyek`

**Files:**
* Create: `app/Models/Proyek.php`
* Create: `app/Http/Controllers/API/KontrolerProyek.php`
* Modify: `routes/api.php`
* Create: `tests/Feature/API/KontrolerProyekTest.php`

**Interfaces:**
* Consumes: Data dari tabel `proyek`.
* Produces: `/api/proyek` endpoint yang menyajikan seluruh proyek dalam JSON.

- [ ] **Step 1: Buat berkas tes fitur KontrolerProyek**

Buat file baru di `tests/Feature/API/KontrolerProyekTest.php`:
```php
<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KontrolerProyekTest extends TestCase
{
    use RefreshDatabase;

    public function test_ambil_semua_proyek_berhasil(): void
    {
        $this->seed(\Database\Seeders\SeederProyek::class);

        $response = $this->getJson('/api/proyek');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'sukses',
                     'data' => [
                         '*' => [
                             'id',
                             'nama_proyek',
                             'tautan_slug',
                             'deskripsi',
                             'teknologi_utama',
                             'tautan_langsung',
                             'tautan_github',
                             'jalur_gambar'
                         ]
                     ]
                 ])
                 ->assertJsonFragment([
                     'nama_proyek' => 'Sistem Informasi Keamanan Neo-Tokyo',
                     'tautan_slug' => 'sistem-informasi-keamanan-neo-tokyo'
                 ]);
    }
}
```

- [ ] **Step 2: Jalankan tes untuk memverifikasi kegagalan**

Jalankan:
```bash
php artisan test --filter=KontrolerProyekTest
```
Expected: FAIL (karena Model, Kontroler, dan Rute belum ada)

- [ ] **Step 3: Buat Model Proyek dengan PHP Attributes Laravel 13**

Buat file baru `app/Models/Proyek.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

#[Table('proyek')]
#[Fillable([
    'nama_proyek',
    'tautan_slug',
    'deskripsi',
    'teknologi_utama',
    'tautan_langsung',
    'tautan_github',
    'jalur_gambar'
])]
#[Casts([
    'teknologi_utama' => 'array'
])]
class Proyek extends Model
{
    // Model dikonfigurasi sepenuhnya via Attributes bawaan Laravel 13
}
```

- [ ] **Step 4: Buat Kontroler API KontrolerProyek**

Buat file baru `app/Http/Controllers/API/KontrolerProyek.php`:
```php
<?php

namespace App\Http/Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class KontrolerProyek extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        try {
            $daftarProyek = Proyek::all();
            
            return response()->json([
                'sukses' => true,
                'data' => $daftarProyek
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data proyek: ' . $e->getMessage());
            
            return response()->json([
                'sukses' => false,
                'pesan' => 'Terjadi kesalahan sistem internal.'
            ], 500);
        }
    }
}
```

- [ ] **Step 5: Daftarkan rute API di routes/api.php**

Ubah file `routes/api.php` untuk menambahkan rute `/proyek`:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KontrolerProyek;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'aktif',
        'pesan' => 'API Portofolio Cyberpunk siap'
    ]);
});

Route::get('/proyek', [KontrolerProyek::class, 'ambilSemua']);
```

- [ ] **Step 6: Jalankan tes kembali untuk memverifikasi kelulusan**

Jalankan:
```bash
php artisan test --filter=KontrolerProyekTest
```
Expected: PASS

- [ ] **Step 7: Komit perubahan ke Git**

Jalankan:
```bash
git add app/Models/Proyek.php app/Http/Controllers/API/KontrolerProyek.php routes/api.php tests/Feature/API/KontrolerProyekTest.php
git commit -m "feat: buat model Proyek dengan attributes dan KontrolerProyek API"
```

---

### Task 4: Instalasi & Konfigurasi Filament PHP v4

**Files:**
* Create: `app/Filament/Resources/ProyekResource.php`

**Interfaces:**
* Produces: Panel admin CRUD pada `/admin` untuk memanipulasi data proyek.

- [ ] **Step 1: Install Filament PHP v4 via Composer**

Jalankan:
```bash
composer require filament/filament:"^4.0" -W
```
Expected: Filament and dependencies successfully installed.

- [ ] **Step 2: Jalankan instalasi panel Filament**

Jalankan:
```bash
php artisan filament:install --panels
```
Expected: Panel admin `admin` has been created.

- [ ] **Step 3: Buat Resource untuk Proyek**

Jalankan:
```bash
php artisan make:filament-resource Proyek --simple
```
Expected: File `app/Filament/Resources/ProyekResource.php` created.

- [ ] **Step 4: Konfigurasi form dan tabel di ProyekResource**

Ubah file `app/Filament/Resources/ProyekResource.php` dengan implementasi skema form dan tabel berbahasa Indonesia:
```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyekResource\Pages;
use App\Models\Proyek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProyekResource extends Resource
{
    protected static ?string $model = Proyek::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Kelola Proyek';
    protected static ?string $modelLabel = 'Proyek';
    protected static ?string $pluralModelLabel = 'Daftar Proyek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_proyek')
                            ->label('Nama Proyek')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('tautan_slug', Str::slug($state))),
                        
                        Forms\Components\TextInput::make('tautan_slug')
                            ->label('Tautan Slug')
                            ->required()
                            ->unique(Proyek::class, 'tautan_slug', ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Proyek')
                            ->required()
                            ->rows(4),
                        
                        Forms\Components\TagsInput::make('teknologi_utama')
                            ->label('Teknologi Utama')
                            ->required()
                            ->placeholder('Tambah teknologi...'),
                        
                        Forms\Components\FileUpload::make('jalur_gambar')
                            ->label('Gambar Sampul Proyek')
                            ->directory('gambar-proyek')
                            ->image()
                            ->nullable(),
                        
                        Forms\Components\TextInput::make('tautan_langsung')
                            ->label('Tautan Demo Live')
                            ->url()
                            ->nullable(),
                        
                        Forms\Components\TextInput::make('tautan_github')
                            ->label('Tautan Repositori GitHub')
                            ->url()
                            ->nullable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('jalur_gambar')
                    ->label('Sampul'),
                Tables\Columns\TextColumn::make('nama_proyek')
                    ->label('Nama Proyek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tautan_slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TagsColumn::make('teknologi_utama')
                    ->label('Teknologi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProyeks::route('/'),
        ];
    }
}
```

- [ ] **Step 5: Buat user admin awal untuk masuk ke Filament**

Jalankan perintah interaktif untuk membuat super user:
```bash
php artisan make:filament-user --name="Admin Cyberpunk" --email="admin@cyberpunk.io" --password="password"
```
Expected: User `admin@cyberpunk.io` successfully created.

- [ ] **Step 6: Komit perubahan ke Git**

Jalankan:
```bash
git add app/Filament/Resources/ProyekResource.php
git commit -m "feat: instalasi Filament v4 dan buat ProyekResource panel admin"
```

---

### Task 5: Setup Inertia.js React & Tailwind CSS v4

**Files:**
* Modify: `package.json`
* Modify: `vite.config.js`
* Create: `resources/views/app.blade.php`
* Create: `resources/js/app.jsx`
* Create: `app/Http/Middleware/HandleInertiaRequests.php`
* Modify: `bootstrap/app.php`

**Interfaces:**
* Produces: Lingkungan kerja frontend Inertia React dengan Tailwind CSS v4 siap pakai.

- [ ] **Step 1: Install Inertia React & Dependencies**

Jalankan:
```bash
npm install @inertiajs/react react react-dom framer-motion Lucide-react
```
Expected: Packages installed successfully.

- [ ] **Step 2: Jalankan instalasi Laravel Inertia di backend**

Jalankan:
```bash
php artisan inertia:middleware
```
Expected: Middleware `app/Http/Middleware/HandleInertiaRequests.php` created.

- [ ] **Step 3: Daftarkan middleware Inertia di bootstrap/app.php**

Ubah file `bootstrap/app.php` untuk mendaftarkan `HandleInertiaRequests`:
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
```

- [ ] **Step 4: Buat berkas resources/views/app.blade.php**

Buat file layout utama `resources/views/app.blade.php`:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portofolio Cyberpunk</title>
    @viteReactRefresh
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="bg-[#0a0a0c] text-gray-100 overflow-x-hidden antialiased">
    @inertia
</body>
</html>
```

- [ ] **Step 5: Buat berkas inisialisasi JS resources/js/app.jsx**

Buat file baru `resources/js/app.jsx`:
```jsx
import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    title: (title) => `${title} - Portofolio Cyberpunk`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});
```

- [ ] **Step 6: Sesuaikan vite.config.js agar mendukung React & Inertia**

Modifikasi file `vite.config.js`:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
});
```

- [ ] **Step 7: Tambahkan plugin React ke devDependencies**

Jalankan:
```bash
npm install -D @vitejs/plugin-react
```
Expected: Plugin Vite React terinstal.

- [ ] **Step 8: Komit konfigurasi frontend awal**

Jalankan:
```bash
git add package.json vite.config.js resources/views/app.blade.php resources/js/app.jsx app/Http/Middleware/HandleInertiaRequests.php bootstrap/app.php
git commit -m "feat: konfigurasi awal Inertia React dan plugin Vite React"
```

---

### Task 6: Komponen Frontend Halaman Portofolio React (Cyberpunk Theme)

**Files:**
* Create: `resources/js/Pages/Portofolio.jsx`
* Modify: `routes/web.php`
* Modify: `resources/css/app.css`

**Interfaces:**
* Consumes: Data proyek dari Inertia controller or API.
* Produces: Halaman utama interaktif dengan tema Cyberpunk gelap, efek pendar neon, scanlines, dan animasi kartu.

- [ ] **Step 1: Konfigurasi CSS Cyberpunk & Grid**

Modifikasi file `resources/css/app.css` untuk menambahkan styling cyberpunk kustom menggunakan Tailwind CSS v4:
```css
@import "tailwindcss";

@layer utilities {
  .neon-glow-cyan {
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.5), 0 0 20px rgba(0, 240, 255, 0.2);
  }
  .neon-glow-pink {
    box-shadow: 0 0 10px rgba(255, 0, 127, 0.5), 0 0 20px rgba(255, 0, 127, 0.2);
  }
  .grid-bg {
    background-image: 
      linear-gradient(rgba(0, 240, 255, 0.05) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0, 240, 255, 0.05) 1px, transparent 1px);
    background-size: 40px 40px;
  }
  .scanline {
    background: linear-gradient(
      to bottom,
      rgba(255, 255, 255, 0),
      rgba(255, 255, 255, 0) 50%,
      rgba(0, 0, 0, 0.3) 50%,
      rgba(0, 0, 0, 0.3)
    );
    background-size: 100% 4px;
  }
}
```

- [ ] **Step 2: Buat Halaman Utama React Portofolio.jsx**

Buat file baru `resources/js/Pages/Portofolio.jsx` yang mengimplementasikan visual grid cyberpunk, filter kategori teknologi, dan animasi Framer Motion:
```jsx
import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Terminal, ExternalLink, Github, Layers, ShieldAlert, Cpu } from 'lucide-react';

export default function Portofolio({ proyek: dataProyek }) {
  const [filterTeknologi, setFilterTeknologi] = useState('Semua');

  // Mengumpulkan seluruh teknologi unik untuk filter
  const semuaTeknologi = ['Semua', ...new Set(dataProyek.flatMap(p => p.teknologi_utama))];

  const proyekTersaring = filterTeknologi === 'Semua' 
    ? dataProyek 
    : dataProyek.filter(p => p.teknologi_utama.includes(filterTeknologi));

  // Animasi Framer Motion
  const animasiKontainer = {
    hidden: { opacity: 0 },
    show: {
      opacity: 1,
      transition: { staggerChildren: 0.15 }
    }
  };

  const animasiKartu = {
    hidden: { y: 30, opacity: 0 },
    show: { y: 0, opacity: 1, transition: { type: "spring", stiffness: 100 } },
    exit: { scale: 0.9, opacity: 0, transition: { duration: 0.2 } }
  };

  return (
    <div className="relative min-h-screen bg-[#0a0a0c] text-gray-100 font-sans grid-bg p-8 overflow-hidden">
      {/* Efek Scanline CRT Cyberpunk */}
      <div className="pointer-events-none absolute inset-0 scanline opacity-20 z-50"></div>

      {/* Header Portofolio */}
      <header className="max-w-7xl mx-auto mb-12 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-[#00f0ff]/30 pb-6 gap-6">
        <div>
          <div className="flex items-center gap-2 text-[#00f0ff] mb-2 font-mono text-sm tracking-wider uppercase">
            <Terminal size={16} />
            <span>Terminal Sistem Aktif</span>
          </div>
          <h1 className="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#00f0ff] via-[#ff007f] to-[#fee715] tracking-tight uppercase">
            Sistem Informasi Portofolio
          </h1>
        </div>
        <div className="text-right font-mono text-[#ff007f] text-sm">
          <span>TANGGAL: 17_06_2026</span>
          <br />
          <span>STATUS: ONLINE</span>
        </div>
      </header>

      <main className="max-w-7xl mx-auto">
        {/* Navigasi Filter Kategori */}
        <section className="mb-10 flex flex-wrap gap-3 items-center">
          <span className="font-mono text-[#fee715] text-sm flex items-center gap-1.5 mr-2">
            <Layers size={14} /> FILTER:
          </span>
          {semuaTeknologi.map((teknologi) => (
            <button
              key={teknologi}
              onClick={() => setFilterTeknologi(teknologi)}
              className={`px-4 py-1.5 font-mono text-xs uppercase tracking-wider border rounded-none transition-all duration-300 ${
                filterTeknologi === teknologi
                  ? 'bg-[#00f0ff] text-black border-[#00f0ff] neon-glow-cyan'
                  : 'bg-transparent text-gray-400 border-gray-700 hover:border-[#ff007f] hover:text-[#ff007f]'
              }`}
            >
              {teknologi}
            </button>
          ))}
        </section>

        {/* Grid Kartu Proyek */}
        <motion.section 
          variants={animasiKontainer}
          initial="hidden"
          animate="show"
          className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <AnimatePresence mode="popLayout">
            {proyekTersaring.map((proyek) => (
              <motion.article
                key={proyek.id}
                variants={animasiKartu}
                layout
                exit="exit"
                className="group relative bg-[#121214] border border-gray-800 hover:border-[#ff007f]/80 p-6 flex flex-col justify-between transition-all duration-300 neon-glow-pink"
              >
                {/* Efek Sudut Cyberpunk */}
                <div className="absolute top-0 right-0 w-4 h-4 bg-[#ff007f] clip-path-polygon"></div>

                <div>
                  {/* Gambar Portofolio */}
                  <div className="relative w-full h-48 bg-[#0a0a0c] mb-6 overflow-hidden border border-gray-800 group-hover:border-[#00f0ff]/50 transition-colors duration-300">
                    {proyek.jalur_gambar ? (
                      <img 
                        src={`/storage/${proyek.jalur_gambar}`} 
                        alt={proyek.nama_proyek} 
                        className="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500"
                      />
                    ) : (
                      <div className="w-full h-full flex flex-col justify-center items-center text-gray-600 font-mono text-xs">
                        <Cpu size={32} className="mb-2 text-[#00f0ff]/40 animate-pulse" />
                        <span>GAMBAR_TIDAK_TERSEDIA</span>
                      </div>
                    )}
                  </div>

                  {/* Judul & Deskripsi */}
                  <h3 className="text-xl font-bold tracking-tight text-white mb-2 group-hover:text-[#00f0ff] transition-colors duration-300 uppercase">
                    {proyek.nama_proyek}
                  </h3>
                  <p className="text-sm text-gray-400 font-sans leading-relaxed mb-6">
                    {proyek.deskripsi}
                  </p>
                </div>

                <div>
                  {/* Teknologi List */}
                  <div className="flex flex-wrap gap-1.5 mb-6">
                    {proyek.teknologi_utama.map((tech) => (
                      <span key={tech} className="px-2 py-0.5 font-mono text-[10px] bg-black text-[#fee715] border border-[#fee715]/20">
                        {tech}
                      </span>
                    ))}
                  </div>

                  {/* Tautan Proyek */}
                  <div className="flex justify-between items-center border-t border-gray-800 pt-4 font-mono text-xs">
                    {proyek.tautan_github ? (
                      <a 
                        href={proyek.tautan_github} 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        className="flex items-center gap-1.5 text-gray-400 hover:text-white transition-colors duration-300"
                      >
                        <Github size={14} /> KODE_SUMBER
                      </a>
                    ) : (
                      <span className="text-gray-600 flex items-center gap-1.5"><ShieldAlert size={14} /> PRIVAT</span>
                    )}

                    {proyek.tautan_langsung && (
                      <a 
                        href={proyek.tautan_langsung} 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        className="flex items-center gap-1 text-[#00f0ff] hover:text-white transition-colors duration-300"
                      >
                        DEMO <ExternalLink size={12} />
                      </a>
                    )}
                  </div>
                </div>
              </motion.article>
            ))}
          </AnimatePresence>
        </motion.section>
      </main>
    </div>
  );
}
```

- [ ] **Step 3: Hubungkan rute web.php ke halaman Portofolio**

Ubah file `routes/web.php` untuk merender halaman Inertia dengan data proyek yang ditarik dari database:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Models\Proyek;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Portofolio', [
        'proyek' => Proyek::all()
    ]);
});
```

- [ ] **Step 4: Jalankan build aset frontend**

Jalankan:
```bash
npm run build
```
Expected: Frontend build compiled successfully.

- [ ] **Step 5: Komit frontend portofolio**

Jalankan:
```bash
git add resources/js/Pages/Portofolio.jsx routes/web.php resources/css/app.css
git commit -m "feat: implementasi halaman utama portofolio React dengan Framer Motion bertema Cyberpunk"
```
