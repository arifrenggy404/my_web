# Spesifikasi Desain Widget Telemetri Pengunjung

Dokumen ini mendokumentasikan spesifikasi desain untuk menambahkan widget telemetri pengunjung (`Visitor Telemetry Widget`) di bawah header ASCII "ARIF RENGGY" pada layout utama portfolio.

## 1. Arsitektur Komponen & Penempatan

Widget telemetri ini akan diletakkan di dalam `ArsipLayout.jsx` di bawah component `<AsciiHeader />` dalam satu container fleksibel.

### 1.1 Struktur Layout Header Baru
```jsx
<header className="max-w-7xl mx-auto border-b border-terminal pb-6 mb-8 flex flex-col md:flex-row justify-between items-center md:items-end gap-4 relative z-10">
    <h1 className="sr-only">Arif Renggy - Portofolio Developer Laravel & React</h1>
    <div className="flex flex-col gap-3 w-full md:w-auto">
        <AsciiHeader />
        {/* WIDGET TELEMETRI DISINI */}
        <TelemetryWidget />
    </div>
    <div className="text-center md:text-right w-full md:w-auto">
        ...
    </div>
</header>
```

---

## 2. Struktur Visual & Sensor Telemetri

Widget akan dirender secara kompak dalam baris fleksibel (`flex row` atau `grid` kecil) agar tidak memakan terlalu banyak ruang vertikal di header.

### 2.1 Komponen Telemetri
1. **Radar Mini (Canvas-based)**:
   - Sebuah canvas kecil berukuran `50x50px` yang menggambar radar berputar secara real-time.
   - Menggunakan warna dinamis berdasarkan `--color-terminal-primary` untuk garis sapuan dan lingkaran radar.
2. **Metadata Sensor**:
   - **IP_UPLINK**: Memanggil endpoint `/api/ip` untuk mengambil IP publik nyata pengunjung. Menampilkan data IP tersebut, atau `127.0.0.1` jika terjadi error.
   - **NEURAL_NODE**: Membaca sistem operasi dan jenis browser dari `navigator.userAgent` dan menyederhanakannya (misal: `MAC_OS_SAFARI`, `WIN_11_CHROME`, `LINUX_FIREFOX`).
   - **BATT_CORE**: Membaca level baterai dari browser Battery API (`navigator.getBattery()`). Menampilkan persentase baterai serta bar status visual (misal: `[====  ] 80%`).
   - **SYS_LOAD**: Menampilkan beban CPU simulasi (mock) yang berfluktuasi secara acak antara `1.00%` sampai `4.50%` untuk estetika sci-fi.

### 2.2 Reaktivitas Warna
Seluruh elemen visual (radar, teks, bar baterai) akan terikat langsung ke variabel CSS `:root` (`--color-terminal-primary` dan `--color-terminal-accent`), sehingga jika tema diubah melalui CLI (misalnya ke `matrix`), warna widget telemetri akan langsung berubah warna secara instan.

---

## 3. Rencana Pengujian
1. **Verifikasi Tampilan**: Memastikan tata letak responsif dan tidak merusak estetika header di desktop maupun mobile.
2. **Sensor Baterai & User Agent**: Memverifikasi data baterai dan sistem operasi terbaca dengan benar di berbagai browser.
3. **Endpoint IP**: Memastikan request ke `/api/ip` di Laravel mengembalikan alamat IP klien dengan benar.
4. **Verifikasi Build**: Menjalankan `npm run build` dan `vendor/bin/phpunit` untuk memastikan semua build aman.
