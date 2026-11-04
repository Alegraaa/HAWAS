<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Skenario Simulasi
        Schema::create('skenario_simulasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konteks');
            $table->integer('nominal_rugi')->default(0);
            $table->string('opsi_berisiko');
            $table->string('opsi_aman');
            $table->text('tampilan_html');
            $table->text('penjelasan_risiko');
            $table->text('penjelasan_aman');
            $table->text('catatan_khusus')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 2. Riwayat Simulasi
        Schema::create('riwayat_simulasi', function (Blueprint $table) {
            $table->id();
            $table->integer('skor')->default(0);
            $table->integer('saldo_akhir')->default(1000000);
            $table->text('hasil_per_tahap')->nullable();
            $table->timestamps();
        });

        // 3. Pola Pesan
        Schema::create('pola_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pola');
            $table->integer('bobot')->default(1);
            $table->text('regex_pattern');
            $table->text('alasan_risiko');
            $table->text('solusi_aman');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 4. Riwayat Cek
        Schema::create('riwayat_cek', function (Blueprint $table) {
            $table->id();
            $table->text('pesan_ringkas');
            $table->string('tingkat_bahaya', 50);
            $table->integer('bobot_skor')->default(0);
            $table->integer('jumlah_indikasi')->default(0);
            $table->text('detail_temuan')->nullable();
            $table->timestamps();
        });

        // 5. Merchant QRIS
        Schema::create('merchant_qris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_merchant');
            $table->string('kota', 100)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('status_keaslian', 50)->default('resmi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 6. Log Cek QRIS
        Schema::create('log_cek_qris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_di_qris');
            $table->string('nama_toko_tujuan');
            $table->string('status_hasil', 50);
            $table->boolean('crc_valid')->default(true);
            $table->timestamps();
        });

        // 7. Materi Edukasi
        Schema::create('materi_edukasi', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_urut')->default(1);
            $table->string('judul');
            $table->text('deskripsi_risiko');
            $table->text('tindakan_aman');
            $table->string('link_skenario')->nullable();
            $table->timestamps();
        });

        // 8. Soal Kuis
        Schema::create('soal_kuis', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->string('kunci_jawaban', 5);
            $table->text('penjelasan');
            $table->timestamps();
        });

        // 9. Riwayat Kuis
        Schema::create('riwayat_kuis', function (Blueprint $table) {
            $table->id();
            $table->integer('skor');
            $table->integer('total_soal');
            $table->timestamps();
        });

        // 10. Kontak Darurat
        Schema::create('kontak_darurat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_institusi');
            $table->string('nomor_telepon', 100);
            $table->string('jenis', 50)->default('bank');
            $table->string('url_resmi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 11. Laporan Insiden
        Schema::create('laporan_insiden', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('bank_korban');
            $table->string('kontak_penipu');
            $table->integer('nominal_rugi')->default(0);
            $table->text('kronologi');
            $table->string('status', 50)->default('tercatat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_insiden');
        Schema::dropIfExists('kontak_darurat');
        Schema::dropIfExists('riwayat_kuis');
        Schema::dropIfExists('soal_kuis');
        Schema::dropIfExists('materi_edukasi');
        Schema::dropIfExists('log_cek_qris');
        Schema::dropIfExists('merchant_qris');
        Schema::dropIfExists('riwayat_cek');
        Schema::dropIfExists('pola_pesan');
        Schema::dropIfExists('riwayat_simulasi');
        Schema::dropIfExists('skenario_simulasi');
    }
};

