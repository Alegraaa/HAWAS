# HAWAS (Hati-Hati Awasi Transaksi) 🛡️

**HAWAS** adalah platform edukasi & simulasi keamanan transaksi digital berbasis web yang dibangun menggunakan **Laravel Framework (PHP 8.3+)** dan arsitektur **MVC (Model-View-Controller)**.

---

## 🌟 Modul & Fitur Utama

1. **Beranda (`/`)**
   - Ringkasan statistik dinamis dari database (Skenario simulasi, pola deteksi, merchant terverifikasi, kontak darurat).
2. **Simulasi Transaksi (`/simulasi`)**
   - Latihan interaktif menghadapi berbagai skenario penipuan digital (undian palsu, kurir APK, OTP bank).
   - Pencatatan skor, saldo, dan riwayat simulasi di database (`/simulasi/riwayat`).
3. **Detektor Pesan Phishing (`/cek`)**
   - Analisis otomatis kata kunci mencurigakan berbasis database pola pesan penipuan.
   - Perhitungan bobot risiko (Aman, Waspada, Bahaya Tinggi) & pencatatan riwayat (`/cek/riwayat`).
4. **Validasi Merchant QRIS (`/qris`)**
   - Scanner kamera interaktif (didukung `jsQR`) & input manual string QRIS.
   - Pengecekan CRC checksum dan verifikasi kemiripan nama toko tujuan terhadap database merchant resmi.
5. **Belajar & Kuis (`/belajar`)**
   - Modul materi edukasi keamanan transaksi perbankan dan e-wallet.
   - Kuis interaktif dinamis dengan evaluasi jawaban otomatis dan pencatatan skor.
6. **Bantuan Darurat Penipuan SOS (`/sos`)**
   - Direktori hotline resmi bank, OJK 157, Kominfo (AduanKonten), dan Cyber Patrol Polri.
   - Formulir pengaduan insiden penipuan dengan pencatatan dan peninjauan laporan (`/sos/daftar-laporan`).

---

## 🏗️ Struktur Arsitektur Laravel

- **Routing**: `routes/web.php`
- **Controllers**: `app/Http/Controllers/`
  - `HomeController.php`
  - `SimulasiController.php`
  - `CekController.php`
  - `QrisController.php`
  - `BelajarController.php`
  - `SosController.php`
- **Eloquent Models**: `app/Models/` (11 Models: `SkenarioSimulasi`, `RiwayatSimulasi`, `PolaPesan`, `RiwayatCek`, `MerchantQris`, `LogCekQris`, `MateriEdukasi`, `SoalKuis`, `RiwayatKuis`, `KontakDarurat`, `LaporanInsiden`)
- **Blade Views & Layouts**: `resources/views/`
- **Static Assets (CSS, JS, Scanner)**: `public/assets/`
- **Database Migrations & Seeders**: `database/migrations/` & `database/seeders/` (SQLite / MySQL)

---

## 🚀 Cara Menjalankan

### Menggunakan PHP Built-in Server:
```bash
php artisan serve
```
Akses di browser: `http://localhost:8000`

### Menggunakan Laragon:
Aplikasi dapat langsung diakses melalui virtual host Laragon:
- `http://hawas.test`
- atau `http://localhost/HAWAS`

### Migrasi & Seeder Database:
Jika ingin me-reset atau memperbarui data:
```bash
php artisan migrate:fresh --seed
```
