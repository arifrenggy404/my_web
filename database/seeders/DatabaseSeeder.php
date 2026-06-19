<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user admin
        if (\App\Models\User::count() === 0) {
            \App\Models\User::create([
                'name' => 'Arif Renggy',
                'email' => 'admin@arifrenggy.site',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]);
        }

        // Seed proyek
        $this->call([
            SeederProyek::class,
        ]);

        // Seed Keahlian
        \App\Models\Keahlian::create([
            'nama' => 'Laravel', 
            'level' => 95, 
            'warna' => 'accent', 
            'apakah_core' => true, 
            'deskripsi' => 'Mesin utama untuk backend berkinerja tinggi.'
        ]);
        \App\Models\Keahlian::create([
            'nama' => 'React', 
            'level' => 85, 
            'warna' => 'primary', 
            'apakah_core' => false, 
            'deskripsi' => 'Antarmuka reaktif dan dinamis.'
        ]);
        \App\Models\Keahlian::create([
            'nama' => 'Tailwind CSS', 
            'level' => 90, 
            'warna' => 'primary', 
            'apakah_core' => false, 
            'deskripsi' => 'Sistem desain utilitas atomik.'
        ]);
        \App\Models\Keahlian::create([
            'nama' => 'Docker', 
            'level' => 75, 
            'warna' => 'warning', 
            'apakah_core' => false, 
            'deskripsi' => 'Kontainerisasi infrastruktur.'
        ]);
        \App\Models\Keahlian::create([
            'nama' => 'SQLite', 
            'level' => 80, 
            'warna' => 'warning', 
            'apakah_core' => false, 
            'deskripsi' => 'Penyimpanan data lokal yang persisten.'
        ]);
        \App\Models\Keahlian::create([
            'nama' => 'Inertia.js', 
            'level' => 88, 
            'warna' => 'primary', 
            'apakah_core' => false, 
            'deskripsi' => 'Penghubung mulus antara Laravel dan React.'
        ]);

        // Seed Profil
        \App\Models\Profil::create([
            'nama_lengkap' => 'Arif Renggy',
            'peran' => 'Fullstack Developer',
            'spesialisasi' => 'Laravel Expert',
            'wilayah' => 'Indonesia',
            'kutipan' => 'Arsitek Sistem yang berspesialisasi dalam membangun infrastruktur digital yang kokoh dan efisien menggunakan Laravel.',
        ]);

        // Seed Kontak
        \App\Models\Kontak::create([
            'nama' => 'GitHub',
            'username_label' => '@arifrenggy00',
            'tautan_url' => 'https://github.com/arifrenggy00',
            'tipe_ikon' => 'github',
            'warna_hover' => 'white',
            'urutan' => 1,
        ]);
        \App\Models\Kontak::create([
            'nama' => 'LinkedIn',
            'username_label' => 'Arif Renggy',
            'tautan_url' => '#',
            'tipe_ikon' => 'linkedin',
            'warna_hover' => 'primary',
            'urutan' => 2,
        ]);
        \App\Models\Kontak::create([
            'nama' => 'WhatsApp',
            'username_label' => 'MISI_LANGSUNG',
            'tautan_url' => '#',
            'tipe_ikon' => 'whatsapp',
            'warna_hover' => 'warning',
            'urutan' => 3,
        ]);
        \App\Models\Kontak::create([
            'nama' => 'Email',
            'username_label' => 'arifrenggy404@gmail.com',
            'tautan_url' => 'mailto:arifrenggy404@gmail.com',
            'tipe_ikon' => 'email',
            'warna_hover' => 'accent',
            'urutan' => 4,
        ]);
        \App\Models\Kontak::create([
            'nama' => 'Website',
            'username_label' => 'PORT_PROD_V1',
            'tautan_url' => 'https://portofolio-cyberpunk-production.up.railway.app',
            'tipe_ikon' => 'website',
            'warna_hover' => 'primary',
            'urutan' => 5,
        ]);

        // Seed Pengaturan
        \App\Models\Pengaturan::create([
            'nama_aplikasi' => 'Arif Renggy Portfolio',
            'ascii_header' => "  ___  ____  ___ _____   ____  ____ _   _  ____  ______   __\n / _ \\|  _ \\|_ _|  ___| |  _ \\| ___| \\ | |/ ___|/ ___\\ \\ / /\n| |_| | |_) || || |_    | |_) |  _| |  \\| | |  _| |  _ \\ V / \n|  _  |  _ < | ||  _|   |  _ <| |___| |\\  | |_| | |_| | | |  \n|_| |_|_| \\_\\___|_|     |_| \\_\\_____|_| \\_|\\____|\\____| |_|  ",
            'versi' => 'v4.0.1',
            'analisis_tambahan_arsenal' => 'Sistem arsenal terus diperbarui. Memiliki kemahiran mendalam dalam integrasi modul Laravel 13, optimalisasi database SQLite, dan pengembangan antarmuka reaktif menggunakan React 19.',
            'catatan_pengembang_kontak' => 'Pesan yang dikirim melalui saluran ini akan diproses melalui pipeline enkripsi asimetris.',
            'waktu_respons_kontak' => '12-24 Jam Standar Sektor',
        ]);
    }
}
