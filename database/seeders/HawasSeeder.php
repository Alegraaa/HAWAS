<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HawasSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Skenario Simulasi
        if (DB::table('skenario_simulasi')->count() == 0) {
            DB::table('skenario_simulasi')->insert([
                [
                    'judul' => 'Pesan dari “Kurir Paket”',
                    'konteks' => 'Anda sedang menunggu paket kiriman belanjaan online. Tiba-tiba masuk pesan WhatsApp dari nomor tidak dikenal.',
                    'nominal_rugi' => 300000,
                    'opsi_berisiko' => 'Unduh & Pasang APK',
                    'opsi_aman' => 'Tolak & Blokir Pengirim',
                    'tampilan_html' => '<p class="who">Kurir Ekspedisi (Nomor Baru)</p><p class="bub">Selamat siang kak, paket Anda tertahan di gudang karena alamat kurang lengkap. Silakan cek detail resi & foto paket di file berikut:</p><div class="file"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#14233F" stroke-width="1.7"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M12 11v5M9.5 14l2.5 2.5 2.5-2.5"/></svg><div><b>Foto_Paket_Lihat.apk</b><span style="font-size:12px;color:#666">3,2 MB · Format File Aplikasi Android</span></div></div>',
                    'penjelasan_risiko' => 'Pilihan ini sangat berisiko! File berakhiran .apk yang dikirim lewat chat adalah aplikasi penipu (malware/trojan) yang dapat membajak SMS, membaca kode OTP perbankan, dan menguras saldo rekening secara diam-diam.',
                    'penjelasan_aman' => 'Langkah cerdas! Kurir ekspedisi resmi TIDAK PERNAH mengirimkan file instalasi (.apk) untuk memeriksa paket atau resi. Selalu periksa nomor resi secara mandiri langsung di aplikasi marketplace atau website resmi ekspedisi.',
                    'catatan_khusus' => 'Ingat: File resi resmi biasanya berupa tautan pelacakan web atau file PDF/JPG, bukan file aplikasi .apk!',
                    'urutan' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'judul' => 'Nama Merchant QRIS Berbeda',
                    'konteks' => 'Anda sedang berada di kasir kedai "Kopi Berkah" dan hendak membayar segelas kopi seharga Rp 28.000 menggunakan QRIS.',
                    'nominal_rugi' => 150000,
                    'opsi_berisiko' => 'Tetap Langsung Bayar',
                    'opsi_aman' => 'Konfirmasi Kasir Dahulu',
                    'tampilan_html' => '<p class="shop">Toko yang Anda kunjungi: <b>Kopi Berkah</b></p><div class="pay"><dl><dt>Tujuan Transfer</dt><dd>REKENING PRIBADI BAMBANG S</dd><dt>Kota</dt><dd>Jakarta Timur</dd><dt>Tagihan</dt><dd class="amt">Rp 28.000</dd></dl></div>',
                    'penjelasan_risiko' => 'Pilihan ini berisiko! Nama merchant pada layar pembayaran menunjukkan rekening perorangan yang berbeda dengan toko tempat Anda berdiri. Bisa jadi stiker QRIS di meja kasir telah ditimpa/ditempeli stiker penipu.',
                    'penjelasan_aman' => 'Tepat sekali! Menanyakan dan memastikan nama merchant kepada kasir sebelum memasukkan PIN adalah langkah pencegahan utama. Jika kasir membenarkan nama tersebut, barulah transaksi aman dilanjutkan.',
                    'catatan_khusus' => 'Perbedaan nama tidak selalu berarti kejahatan (bisa jadi nama pemilik usaha), tetapi WAJIB dikonfirmasi sebelum Anda memproses pembayaran.',
                    'urutan' => 2,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'judul' => 'Telepon Darurat Meminta OTP',
                    'konteks' => 'Ponsel Anda berdering. Penelepon mengaku dari Pusat Layanan Darurat Bank dengan nada suara panik dan mendesak.',
                    'nominal_rugi' => 500000,
                    'opsi_berisiko' => 'Sebutkan Kode OTP',
                    'opsi_aman' => 'Tutup Telepon Segera',
                    'tampilan_html' => '<div class="call"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#D9AE45" stroke-width="1.7"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A15 15 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg><p class="nm">Customer Service Bank</p><p style="margin:0;font-size:13px;opacity:.85">Panggilan Masuk · Nomor Tidak Dikenal</p><p class="tx">“Halo! Akun m-Banking Anda sedang diretas dari kota lain. Untuk membatalkan transaksi penipu, sebutkan 6 angka kode OTP yang baru saja masuk ke SMS Anda sekarang juga!”</p></div>',
                    'penjelasan_risiko' => 'Pilihan ini fatal! Kode OTP (One-Time Password) adalah kunci otorisasi transaksi rahasia. Petugas resmi bank dilarang keras meminta OTP nasabah dalam situasi apa pun. Menyebutkannya berarti menyetujui penipu mengambil uang Anda.',
                    'penjelasan_aman' => 'Keputusan yang sangat tepat! Menutup telepon langsung mematahkan manipulasi psikologis penipu. Bank resmi tidak pernah meminta kode OTP atau PIN. Jika khawatir, hubungi call center resmi bank yang Anda cari sendiri.',
                    'catatan_khusus' => 'Bank punya tombol pembekuan mandiri di sistem internal mereka tanpa memerlukan OTP dari Anda.',
                    'urutan' => 3,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 2. Pola Pesan
        if (DB::table('pola_pesan')->count() == 0) {
            DB::table('pola_pesan')->insert([
                [
                    'nama_pola' => 'File atau ajakan instal aplikasi (APK)',
                    'bobot' => 3,
                    'regex_pattern' => '\\S*\\.apk\\b|\\b(?:unduh|download|instal+)\\s+(?:aplikasi|file|apk)\\b',
                    'alasan_risiko' => 'File pemasangan (.apk) yang dikirim lewat chat dapat memasang aplikasi berbahaya (trojan) pencuri SMS OTP.',
                    'solusi_aman' => 'Jangan pernah diunduh atau dipasang. Hanya unduh aplikasi dari Google Play Store atau Apple App Store resmi.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Permintaan OTP, PIN, atau kata sandi',
                    'bobot' => 3,
                    'regex_pattern' => '(?:\\b(?:kirim|sebut|berikan|beri tahu|masukkan|konfirmasi|balas|input|isi)\\w*\\b[^.\\n]{0,40}\\b(?:otp|pin|cvv|kata sandi|password|kode verifikasi|kode rahasia))',
                    'alasan_risiko' => 'Kode OTP/PIN adalah kunci rahasia utama otorisasi transaksi rekening Anda.',
                    'solusi_aman' => 'Jangan berikan kepada siapa pun! Pihak bank resmi tidak pernah meminta kode rahasia nasabah.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Menyebut OTP, PIN, atau kata sandi',
                    'bobot' => 1,
                    'regex_pattern' => '\\b(?:otp|pin|cvv|kata sandi|password|kode verifikasi|kode rahasia)\\b',
                    'alasan_risiko' => 'Istilah ini sering dipakai dalam skenario manipulasi penipu untuk mempersiapkan permintaan verifikasi palsu.',
                    'solusi_aman' => 'Waspadai jika percakapan mulai mengarahkan Anda membuka SMS kode keamanan.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Nada mendesak atau ancaman blokir',
                    'bobot' => 2,
                    'regex_pattern' => '\\b(?:segera|sekarang juga|hari ini juga|dalam \\d+ (?:jam|menit)|24 jam|batas waktu|terakhir|akan (?:di)?(?:blokir|bekukan|tutup|nonaktifkan)|diblokir|dibekukan)\\b',
                    'alasan_risiko' => 'Penipu sengaja menciptakan suasana panik dan tekanan waktu agar calon korban tidak sempat berpikir jernih.',
                    'solusi_aman' => 'Tarik napas dan berhenti sejenak. Pihak bank resmi selalu memberi mekanisme tertulis dan kanal verifikasi yang wajar.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Iming-iming Hadiah, undian, atau bonus',
                    'bobot' => 2,
                    'regex_pattern' => '\\b(?:hadiah|undian|pemenang|anda menang|gratis|bonus|klaim)\\b',
                    'alasan_risiko' => 'Umpan keuntungan instan atau hadiah undian sering dipakai untuk memancing korban mengklik link phising.',
                    'solusi_aman' => 'Periksa keaslian program undian langsung ke website resmi perusahaan terkait.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Mengaku dari pihak resmi (Bank/OJK/Kurir/Polisi)',
                    'bobot' => 1,
                    'regex_pattern' => '\\b(?:petugas|pusat bantuan|customer service|call center|ojk|bank indonesia|kurir|admin)\\b',
                    'alasan_risiko' => 'Penipu sering mencatut nama institusi terpercaya untuk membangun rasa percaya palsu.',
                    'solusi_aman' => 'Hubungi institusi terkait lewat nomor resmi yang Anda peroleh sendiri dari website resmi mereka.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_pola' => 'Menyinggung transfer atau data identitas',
                    'bobot' => 1,
                    'regex_pattern' => '\\b(?:transfer|rekening|nomor kartu|nik|ktp|kirim uang|biaya admin)\\b',
                    'alasan_risiko' => 'Segala instruksi pemindahan dana ke rekening pribadi atau pengisian NIK/kartu di situs tidak resmi wajib dicurigai.',
                    'solusi_aman' => 'Jangan lakukan transfer sebelum keabsahan nomor rekening tujuan dipastikan.',
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 3. Merchant QRIS
        if (DB::table('merchant_qris')->count() == 0) {
            DB::table('merchant_qris')->insert([
                [
                    'nama_merchant' => 'KOPI BERKAH',
                    'kota' => 'Jakarta Selatan',
                    'kategori' => 'F&B Cafe',
                    'status_keaslian' => 'resmi',
                    'catatan' => 'Kedai kopi resmi rekanan terverifikasi.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_merchant' => 'WARUNG MAKAN SEDAP',
                    'kota' => 'Bandung',
                    'kategori' => 'Restoran',
                    'status_keaslian' => 'resmi',
                    'catatan' => 'Rumah makan resmi.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_merchant' => 'REK PRIBADI AHMAD S',
                    'kota' => 'Jakarta Timur',
                    'kategori' => 'Perorangan',
                    'status_keaslian' => 'rekening_pribadi',
                    'catatan' => 'Indikasi stiker rekening pribadi yang ditempelkan menimpa QRIS toko.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_merchant' => 'BAMBANG SUDIBYO PERSONAL',
                    'kota' => 'Surabaya',
                    'kategori' => 'Perorangan',
                    'status_keaslian' => 'mencurigakan',
                    'catatan' => 'Bukan nama toko komersial, terdeteksi akun perseorangan.',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 4. Materi Edukasi
        if (DB::table('materi_edukasi')->count() == 0) {
            DB::table('materi_edukasi')->insert([
                [
                    'nomor_urut' => 1,
                    'judul' => 'Jangan Pernah Instal File APK dari Chat / Pesan WhatsApp',
                    'deskripsi_risiko' => 'File APK adalah paket aplikasi Android yang dapat disusupi trojan penyadap SMS OTP secara otomatis.',
                    'tindakan_aman' => 'Hanya pasang aplikasi dari toko resmi Google Play Store atau App Store. Tolak file berdalih resi kurir, undangan pernikahan, atau surat tilang.',
                    'link_skenario' => 'simulasi',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nomor_urut' => 2,
                    'judul' => 'Jangan Bagikan Kode OTP atau PIN ke Siapa Pun',
                    'deskripsi_risiko' => 'OTP dan PIN adalah persetujuan akhir perpindahan uang. Pihak bank tidak pernah meminta kode rahasia nasabah.',
                    'tindakan_aman' => 'Segera tutup telepon yang meminta OTP. Jangan panik oleh ancaman akun diblokir.',
                    'link_skenario' => 'simulasi',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nomor_urut' => 3,
                    'judul' => 'Cocokkan Nama Toko di QRIS Sebelum Menekan PIN',
                    'deskripsi_risiko' => 'Penipu kerap menempelkan stiker barcode pribadi menimpa QRIS meja kasir.',
                    'tindakan_aman' => 'Periksa nama merchant yang muncul di layar HP Anda. Pastikan sama dengan kedai tujuan dan tanyakan ke kasir bila ragu.',
                    'link_skenario' => 'qris',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 5. Soal Kuis
        if (DB::table('soal_kuis')->count() == 0) {
            DB::table('soal_kuis')->insert([
                [
                    'pertanyaan' => 'Anda menerima pesan WhatsApp dari nomor asing: "Paket Anda tertahan. Cek foto paket di: Resi_JNT.apk". Apa tindakan yang tepat?',
                    'opsi_a' => 'Unduh dan buka file karena sedang menunggu paket',
                    'opsi_b' => 'Tolak, jangan pernah pasang, dan cek nomor resi mandiri di aplikasi resmi ekspedisi',
                    'opsi_c' => 'Teruskan file ke teman untuk meminta mereka membukanya',
                    'kunci_jawaban' => 'B',
                    'penjelasan' => 'Kurir resmi tidak pernah mengirimkan file aplikasi .apk untuk pelacakan barang.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'pertanyaan' => 'Ada telepon mengaku dari Bank mengabarkan akun Anda diretas dan meminta Anda menyebutkan 6 digit kode OTP SMS. Sikap yang benar?',
                    'opsi_a' => 'Langsung sebutkan kode OTP agar akun cepat diselamatkan',
                    'opsi_b' => 'Langsung matikan telepon. Bank resmi dilarang keras meminta OTP nasabah',
                    'opsi_c' => 'Minta penelepon menunggu sebentar lalu kirimkan nomor kartu debit',
                    'kunci_jawaban' => 'B',
                    'penjelasan' => 'Petugas bank resmi dilarang meminta OTP nasabah dalam keadaan apa pun.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'pertanyaan' => 'Saat memindai QRIS di kedai "Kopi Nusantara", nama yang muncul di layar adalah "REKENING PRIBADI JOKO". Apa yang harus dilakukan?',
                    'opsi_a' => 'Langsung masukkan PIN dan selesaikan transaksi',
                    'opsi_b' => 'Konfirmasi dan tanyakan dulu kepada kasir apakah nama tersebut benar sebelum membayar',
                    'opsi_c' => 'Tinggalkan kedai tanpa membayar',
                    'kunci_jawaban' => 'B',
                    'penjelasan' => 'Pastikan ke kasir sebelum bayar karena barcode meja rawan ditimpa stiker penipu.',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'pertanyaan' => 'Siapa sajakah pihak yang berhak mengetahui kode rahasia PIN dan OTP Anda?',
                    'opsi_a' => 'Petugas Customer Service Bank dan Polisi',
                    'opsi_b' => 'Hanya Anda sendiri, tidak seorang pun selain Anda',
                    'opsi_c' => 'Admin marketplace saat membantu proses refund',
                    'kunci_jawaban' => 'B',
                    'penjelasan' => 'PIN dan OTP adalah rahasia pribadi mutlak pemilik rekening.',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 6. Kontak Darurat
        if (DB::table('kontak_darurat')->count() == 0) {
            DB::table('kontak_darurat')->insert([
                [
                    'nama_institusi' => 'Bank BCA (HaloBCA)',
                    'nomor_telepon' => '1500888',
                    'jenis' => 'bank',
                    'url_resmi' => 'https://www.bca.co.id',
                    'deskripsi' => 'Call center resmi 24 jam Bank Central Asia',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_institusi' => 'Bank Mandiri (Mandiri Call)',
                    'nomor_telepon' => '14000',
                    'jenis' => 'bank',
                    'url_resmi' => 'https://www.bankmandiri.co.id',
                    'deskripsi' => 'Call center resmi 24 jam Bank Mandiri',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_institusi' => 'Bank BRI (Contact BRI)',
                    'nomor_telepon' => '1500017',
                    'jenis' => 'bank',
                    'url_resmi' => 'https://bri.co.id',
                    'deskripsi' => 'Call center resmi 24 jam Bank Rakyat Indonesia',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_institusi' => 'Bank BNI (BNI Call)',
                    'nomor_telepon' => '1500046',
                    'jenis' => 'bank',
                    'url_resmi' => 'https://www.bni.co.id',
                    'deskripsi' => 'Call center resmi 24 jam Bank Negara Indonesia',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_institusi' => 'Otoritas Jasa Keuangan (OJK)',
                    'nomor_telepon' => '157',
                    'jenis' => 'regulator',
                    'url_resmi' => 'https://kontak157.ojk.go.id',
                    'deskripsi' => 'Layanan pengaduan konsumen jasa keuangan',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nama_institusi' => 'Patroli Siber Polri',
                    'nomor_telepon' => 'patrolisiber.id',
                    'jenis' => 'aparat',
                    'url_resmi' => 'https://patrolisiber.id',
                    'deskripsi' => 'Portal resmi pelaporan tindak pidana siber Polri',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // 7. Laporan Contoh
        if (DB::table('laporan_insiden')->count() == 0) {
            DB::table('laporan_insiden')->insert([
                'nama_pelapor' => 'Budi Santoso (Contoh)',
                'bank_korban' => 'BCA - 89210291',
                'kontak_penipu' => '081299887766 / Rek Bank Mandiri 12345678 an. Bambang',
                'nominal_rugi' => 350000,
                'kronologi' => 'Menerima pesan WhatsApp mengaku kurir paket yang mengirim file APK kurir. Saldo terpotong otomatis sesaat setelah aplikasi dipasang.',
                'status' => 'tercatat',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

