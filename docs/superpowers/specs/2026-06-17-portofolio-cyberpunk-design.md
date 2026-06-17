# Spesifikasi Desain: Portofolio Cyberpunk - Arsip Rahasia Sang Arsitek

**Pemilik:** Arif Renggy  
**Peran:** Fullstack Developer / Laravel Expert  
**Tema:** Cyberpunk (Dashboard Arsitek Sistem & Arsip Rahasia)  
**Bahasa:** Indonesia  

## 1. Pendahuluan
Dokumen ini merinci desain portofolio pribadi Arif Renggy yang telah ditingkatkan. Fokus utama adalah menciptakan pengalaman yang imersif dengan estetika cyberpunk, menonjolkan keahlian mendalam di Laravel, dan menggunakan narasi "Arsip Rahasia" untuk menampilkan identitas dan proyek.

## 2. Arsitektur & Teknologi
*   **Backend:** Laravel 13 (PHP 8.3) sebagai mesin utama.
*   **Frontend:** React 19 dengan Inertia.js untuk navigasi antar-halaman yang instan.
*   **Styling:** Tailwind CSS 4 dengan kustomisasi efek neon, grid, dan scanline.
*   **Animasi:** Framer Motion untuk transisi folder dan elemen UI dinamis.
*   **Database:** SQLite untuk penyimpanan data proyek dan identitas.
*   **Deployment:** Docker di Railway dengan volume persisten.

## 3. Komponen Antarmuka (UI)
Seluruh antarmuka menggunakan Bahasa Indonesia dengan istilah teknis cyberpunk.

### 3.1 Header (Status Sistem)
*   Menampilkan: `ARIF RENGGY`
*   Label: `STATUS: AKSES DIIZINKAN`
*   Widget: Indikator beban CPU dan waktu aktif sistem (Uptime) yang bergerak secara acak untuk kesan realistis.

### 3.2 Navigasi Folder (Sidebar)
Struktur menu menggunakan gaya path direktori:
*   `/identitas`: Profil personal, bio, dan data inti.
*   `/misi`: Katalog proyek yang disajikan sebagai laporan operasional.
*   `/arsenal`: Visualisasi keahlian teknologi dengan fokus utama pada Laravel.
*   `/jalur-komunikasi`: Kontak sosial media sebagai saluran komunikasi terenkripsi.

### 3.3 Tampilan Utama (Terminal Display)
*   Visual: Background gelap dengan grid transparan, efek scanline CRT, dan cahaya neon (cyan & pink).
*   Font: Monospaced (seperti JetBrains Mono atau Fira Code) untuk nuansa terminal.

## 4. Detail Konten
### 4.1 Folder /identitas
*   **Bio:** Kalimat berani tentang keahlian Laravel dan visi membangun sistem digital.
*   **Data Arsitek:** Menampilkan Region, Bahasa Utama, dan Spesialisasi.

### 4.2 Folder /misi
*   **Daftar Proyek:** Diambil dari database `Proyek`.
*   **Status Misi:** Tiap proyek memiliki badge status: `TERSELESAIKAN`, `DALAM PENGEMBANGAN`, atau `ARSIP RAHASIA`.
*   **Modul Teknologi:** Daftar teknologi yang digunakan dalam proyek tersebut.

### 4.3 Folder /arsenal
*   **Primary Core:** Laravel (Visualisasi paling menonjol).
*   **Supporting Modules:** React, Tailwind CSS, Docker, SQLite, Git.
*   **Energi Keahlian:** Bar progres dinamis yang menunjukkan tingkat kemahiran.

### 4.4 Folder /jalur-komunikasi
*   Tautan ke GitHub, LinkedIn, dan WhatsApp dengan ikon yang berpendar.

## 5. Konvensi Kode
*   Penamaan komponen dan variabel yang berkaitan dengan UI menggunakan Bahasa Indonesia (misal: `KotakProyek.jsx`, `NavigasiArsip.jsx`).
*   Logika backend Laravel tetap mengikuti standar PSR-12.

## 6. Kriteria Keberhasilan
*   Halaman memuat dengan efek animasi transisi yang mulus.
*   Seluruh teks UI menggunakan Bahasa Indonesia yang konsisten.
*   Visual cyberpunk (neon/glow) terlihat konsisten di seluruh halaman.
*   Data proyek berhasil ditarik dari SQLite dan ditampilkan dalam format "Laporan Misi".
