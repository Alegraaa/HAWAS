<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SimulasiController;
use App\Http\Controllers\CekController;
use App\Http\Controllers\QrisController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\SosController;

// 1. Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Modul Simulasi Transaksi
Route::prefix('simulasi')->name('simulasi.')->group(function () {
    Route::get('/', [SimulasiController::class, 'index'])->name('index');
    Route::post('/save', [SimulasiController::class, 'save'])->name('save');
    Route::get('/riwayat', [SimulasiController::class, 'riwayat'])->name('riwayat');
});

// 3. Modul Cek Pesan & Tautan
Route::prefix('cek')->name('cek.')->group(function () {
    Route::get('/', [CekController::class, 'index'])->name('index');
    Route::post('/analyze', [CekController::class, 'analyze'])->name('analyze');
    Route::get('/riwayat', [CekController::class, 'riwayat'])->name('riwayat');
});

// 4. Modul Validasi QRIS
Route::prefix('qris')->name('qris.')->group(function () {
    Route::get('/', [QrisController::class, 'index'])->name('index');
    Route::post('/validate', [QrisController::class, 'validateQris'])->name('validate');
});

// 5. Modul Belajar & Kuis
Route::prefix('belajar')->name('belajar.')->group(function () {
    Route::get('/', [BelajarController::class, 'index'])->name('index');
    Route::post('/submit-quiz', [BelajarController::class, 'submitQuiz'])->name('submit-quiz');
});

// 6. Modul Layanan Darurat (SOS)
Route::prefix('sos')->name('sos.')->group(function () {
    Route::get('/', [SosController::class, 'index'])->name('index');
    Route::post('/lapor', [SosController::class, 'lapor'])->name('lapor');
    Route::get('/daftar-laporan', [SosController::class, 'daftarLaporan'])->name('daftar-laporan');
    Route::post('/hapus-laporan', [SosController::class, 'hapusLaporan'])->name('hapus-laporan');
});
